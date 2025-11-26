<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\BenchmarkResult;
use App\Models\BenchmarkAttackSimulation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BenchmarkController extends Controller
{
    public function index()
    {
        $recentResults = BenchmarkResult::orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $algorithmStats = BenchmarkResult::select(
                'algorithm_name',
                DB::raw('AVG(encryption_time_ms) as avg_encryption_time'),
                DB::raw('AVG(memory_usage_mb) as avg_memory_usage'),
                DB::raw('AVG(throughput_ops_sec) as avg_throughput'),
                DB::raw('COUNT(*) as total_tests')
            )
            ->groupBy('algorithm_name')
            ->get();

        return view('benchmark.index', compact('recentResults', 'algorithmStats'));
    }

    public function runBenchmark(Request $request)
    {
        $request->validate([
            'dataset_size' => 'required|integer|min:10|max:10000'
        ]);

        $datasetSize = $request->input('dataset_size', 100);
        
        $pythonScript = storage_path('app/python_scripts/crypto_benchmark.py');
        
        if (!file_exists($pythonScript)) {
            return back()->with('error', 'Script Python no encontrado en: ' . $pythonScript);
        }
        
        try {
            DB::beginTransaction();
            
            $process = new Process(['python', $pythonScript, '--size', $datasetSize]);
            $process->setTimeout(180); // Aumentado a 3 minutos
            $process->setWorkingDirectory(base_path());
            $process->run();

            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();

            Log::info('Python Benchmark Output', ['output' => $output]);
            Log::info('Python Benchmark Error Output', ['error' => $errorOutput]);

            if (!$process->isSuccessful()) {
                DB::rollBack();
                Log::error('Python process failed', [
                    'exit_code' => $process->getExitCode(),
                    'error_output' => $errorOutput
                ]);
                return back()->with('error', 'Error ejecutando benchmark: ' . $errorOutput);
            }

            $results = json_decode($output, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                DB::rollBack();
                Log::error('JSON decode error', ['error' => json_last_error_msg()]);
                return back()->with('error', 'Error decodificando JSON: ' . json_last_error_msg());
            }

            if (isset($results['error'])) {
                DB::rollBack();
                return back()->with('error', 'Error en el benchmark: ' . $results['error']);
            }

            // GUARDAR RESULTADOS EN LA BASE DE DATOS
            $savedResults = $this->saveBenchmarkResults($results, $datasetSize);
            
            DB::commit();

            return view('benchmark.results', [
                'results' => $results,
                'datasetSize' => $datasetSize,
                'savedResults' => $savedResults,
                'databaseId' => $savedResults->isNotEmpty() ? $savedResults->first()->id : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Benchmark exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Excepción: ' . $e->getMessage());
        }
    }

    private function saveBenchmarkResults(array $results, int $datasetSize)
    {
        $savedResults = collect();
        
        // Guardar resultados de cada algoritmo
        foreach (['aes', 'rsa', 'stream', 'hash'] as $algorithmName) {
            if (isset($results['performance'][$algorithmName])) {
                $metrics = $results['performance'][$algorithmName];
                
                $benchmarkResult = BenchmarkResult::create([
                    'algorithm_name' => strtoupper($algorithmName),
                    'dataset_size' => $datasetSize,
                    'encryption_time_ms' => $metrics['avg_time_encrypt'] ?? 0,
                    'decryption_time_ms' => $metrics['avg_time_decrypt'] ?? 0,
                    'memory_usage_mb' => $metrics['avg_memory'] ?? 0,
                    'cpu_usage_percent' => $metrics['cpu_usage'] ?? 0,
                    'throughput_ops_sec' => $metrics['throughput'] ?? 0,
                    'operations_count' => $metrics['operations'] ?? 0,
                    'test_data_sample' => json_encode(['size' => $datasetSize]),
                    'performance_metrics' => $metrics,
                    'security_metrics' => $results['security'][$algorithmName] ?? [],
                    'attack_results' => $results['attacks'] ?? [],
                    'status' => 'completado'
                ]);

                // Guardar simulaciones de ataque
                $this->saveAttackSimulations($benchmarkResult->id, $results['attacks'] ?? []);
                
                $savedResults->push($benchmarkResult);
            }
        }
        
        return $savedResults;
    }

    private function saveAttackSimulations($benchmarkResultId, array $attacks)
    {
        foreach ($attacks as $attackType => $attackData) {
            BenchmarkAttackSimulation::create([
                'benchmark_result_id' => $benchmarkResultId,
                'attack_type' => $attackType,
                'success' => $attackData['success'] ?? false,
                'attempts' => $attackData['attempts'] ?? null,
                'time_taken' => $attackData['time_taken'] ?? null,
                'data_compromised_percent' => $attackData['data_compromised'] ?? 0,
                'attack_metrics' => $attackData
            ]);
        }
    }

    public function testPython()
    {
        $process = new Process(['python', '--version']);
        $process->run();
        
        return response()->json([
            'python_version' => trim($process->getOutput()),
            'python_error' => trim($process->getErrorOutput()),
            'python_success' => $process->isSuccessful()
        ]);
    }
}