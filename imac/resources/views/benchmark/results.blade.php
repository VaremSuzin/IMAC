<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del Benchmark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">📊 Resultados del Benchmark Criptográfico</h1>
        
        <div class="mb-4">
            <a href="{{ route('benchmark.index') }}" class="text-blue-500 hover:text-blue-700">← Volver al Benchmark</a>
        </div>

        @if(isset($results['error']))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> {{ $results['error'] }}
            </div>
        @else
            <!-- Resumen Ejecutivo -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">📈 Resumen Ejecutivo</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">
                            @if(isset($results['performance']['aes']['avg_time_encrypt']))
                                {{ number_format($results['performance']['aes']['avg_time_encrypt'], 2) }}ms
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-blue-800">AES Avg Time</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            @if(isset($results['performance']['rsa']['avg_time_encrypt']))
                                {{ number_format($results['performance']['rsa']['avg_time_encrypt'], 2) }}ms
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-green-800">RSA Avg Time</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">
                            @if(isset($results['performance']['hash']['avg_time_encrypt']))
                                {{ number_format($results['performance']['hash']['avg_time_encrypt'], 2) }}ms
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-purple-800">Hash Avg Time</div>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-orange-600">
                            @if(isset($results['security']['aes']['resistance']))
                                {{ number_format($results['security']['aes']['resistance'] * 100, 1) }}%
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-orange-800">Mejor Resistencia</div>
                    </div>
                </div>
                
                <!-- Información del Dataset -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <span class="font-semibold">Tamaño del Dataset:</span>
                            <span>{{ $datasetSize }} registros</span>
                        </div>
                        <div>
                            <span class="font-semibold">Estado:</span>
                            <span class="{{ $results['metadata']['status'] === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $results['metadata']['status'] }}
                            </span>
                        </div>
                        <div>
                            <span class="font-semiband">Criptografía:</span>
                            <span class="{{ ($results['metadata']['crypto_available'] ?? true) ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ($results['metadata']['crypto_available'] ?? true) ? 'Disponible' : 'Modo Simulación' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados de Rendimiento -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">⏱️ Resultados de Rendimiento</h2>
                
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800">
                        Dataset: {{ $datasetSize }} registros
                        @if(isset($results['performance']['aes']['total_operations']))
                            ({{ $results['performance']['aes']['total_operations'] }} operaciones totales)
                        @endif
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="py-2 px-4 border">Algoritmo</th>
                                <th class="py-2 px-4 border">Tiempo Cifrado (ms)</th>
                                <th class="py-2 px-4 border">Tiempo Descifrado (ms)</th>
                                <th class="py-2 px-4 border">Rendimiento (ops/s)</th>
                                <th class="py-2 px-4 border">Memoria (MB)</th>
                                <th class="py-2 px-4 border">Operaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['aes', 'rsa', 'stream', 'hash'] as $algoName)
                                @if(isset($results['performance'][$algoName]))
                                    @php $metrics = $results['performance'][$algoName]; @endphp
                                    <tr>
                                        <td class="py-2 px-4 border font-medium">
                                            {{ $metrics['algorithm'] ?? strtoupper($algoName) }}
                                        </td>
                                        <td class="py-2 px-4 border">
                                            {{ number_format($metrics['avg_time_encrypt'] ?? 0, 3) }}
                                        </td>
                                        <td class="py-2 px-4 border">
                                            @if($algoName !== 'hash')
                                                {{ number_format($metrics['avg_time_decrypt'] ?? 0, 3) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border">
                                            {{ number_format($metrics['throughput'] ?? 0, 0) }}
                                        </td>
                                        <td class="py-2 px-4 border">
                                            {{ number_format($metrics['avg_memory'] ?? 0, 2) }}
                                        </td>
                                        <td class="py-2 px-4 border">
                                            {{ $metrics['operations'] ?? 0 }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Información adicional de rendimiento -->
                @if(isset($results['performance']['aes']))
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">🏆 Mejor Rendimiento</h3>
                        @php
                            $bestAlgorithm = null;
                            $bestThroughput = 0;
                            foreach (['aes', 'rsa', 'stream', 'hash'] as $algo) {
                                if (isset($results['performance'][$algo]['throughput']) && 
                                    $results['performance'][$algo]['throughput'] > $bestThroughput) {
                                    $bestThroughput = $results['performance'][$algo]['throughput'];
                                    $bestAlgorithm = $algo;
                                }
                            }
                        @endphp
                        @if($bestAlgorithm)
                            <p class="text-green-700">
                                <strong>{{ strtoupper($bestAlgorithm) }}</strong> con 
                                {{ number_format($bestThroughput, 0) }} ops/segundo
                            </p>
                        @endif
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">⚡ Más Rápido</h3>
                        @php
                            $fastestAlgorithm = null;
                            $fastestTime = PHP_FLOAT_MAX;
                            foreach (['aes', 'rsa', 'stream', 'hash'] as $algo) {
                                if (isset($results['performance'][$algo]['avg_time_encrypt']) && 
                                    $results['performance'][$algo]['avg_time_encrypt'] < $fastestTime) {
                                    $fastestTime = $results['performance'][$algo]['avg_time_encrypt'];
                                    $fastestAlgorithm = $algo;
                                }
                            }
                        @endphp
                        @if($fastestAlgorithm)
                            <p class="text-blue-700">
                                <strong>{{ strtoupper($fastestAlgorithm) }}</strong> con 
                                {{ number_format($fastestTime, 3) }} ms por operación
                            </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Security Analysis -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">🔒 Análisis de Seguridad</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="py-2 px-4 border">Algoritmo</th>
                                <th class="py-2 px-4 border">Fortaleza de Clave</th>
                                <th class="py-2 px-4 border">Vulnerabilidad</th>
                                <th class="py-2 px-4 border">Resistencia</th>
                                <th class="py-2 px-4 border">Recomendación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['security'] ?? [] as $algoName => $metrics)
                                <tr>
                                    <td class="py-2 px-4 border font-medium">{{ strtoupper($algoName) }}</td>
                                    <td class="py-2 px-4 border">{{ number_format(($metrics['key_strength'] ?? 0) * 100, 1) }}%</td>
                                    <td class="py-2 px-4 border">{{ number_format(($metrics['vulnerability'] ?? 0) * 100, 1) }}%</td>
                                    <td class="py-2 px-4 border">{{ number_format(($metrics['resistance'] ?? 0) * 100, 1) }}%</td>
                                    <td class="py-2 px-4 border text-sm">{{ $metrics['recommendation'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Attack Simulations -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-semibold mb-4">🛡️ Simulaciones de Ataque</h2>
                @foreach($results['attacks'] ?? [] as $attackName => $attackResults)
                    <div class="mb-4 p-4 border rounded">
                        <h3 class="text-lg font-medium capitalize">
                            @if($attackName === 'brute_force')
                                🔓 Fuerza Bruta
                            @elseif($attackName === 'sql_injection')
                                🗃️ Inyección SQL
                            @elseif($attackName === 'unauthorized_access')
                                👤 Acceso No Autorizado
                            @else
                                {{ str_replace('_', ' ', $attackName) }}
                            @endif
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                            @foreach($attackResults as $key => $value)
                                <div>
                                    <span class="font-medium capitalize">
                                        @if($key === 'success')
                                            Éxito
                                        @elseif($key === 'attempts')
                                            Intentos
                                        @elseif($key === 'time_taken')
                                            Tiempo Requerido
                                        @elseif($key === 'data_compromised')
                                            Datos Comprometidos
                                        @elseif($key === 'most_resistant')
                                            Más Resistente
                                        @elseif($key === 'vulnerable_endpoints')
                                            Endpoints Vulnerables
                                        @elseif($key === 'prevention')
                                            Prevención
                                        @elseif($key === 'success_rate')
                                            Tasa de Éxito
                                        @elseif($key === 'average_time')
                                            Tiempo Promedio
                                        @elseif($key === 'most_vulnerable')
                                            Más Vulnerable
                                        @elseif($key === 'recommendation')
                                            Recomendación
                                        @else
                                            {{ str_replace('_', ' ', $key) }}
                                        @endif
                                        :
                                    </span>
                                    <span class="ml-2">
                                        @if(is_bool($value))
                                            <span class="{{ $value ? 'text-red-600' : 'text-green-600' }} font-bold">
                                                {{ $value ? 'Sí' : 'No' }}
                                            </span>
                                        @elseif(is_numeric($value))
                                            @if($key === 'data_compromised' || $key === 'success_rate')
                                                {{ number_format($value, 1) }}%
                                            @elseif($key === 'time_taken' || $key === 'average_time')
                                                {{ number_format($value, 2) }}s
                                            @else
                                                {{ number_format($value, 0) }}
                                            @endif
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Información de Base de Datos -->
            @if(isset($databaseId) && $databaseId)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-6">
                <div class="flex items-center">
                    <div class="text-green-500 mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-green-800">✅ Resultados Guardados</h3>
                        <p class="text-green-700 text-sm">
                            Los resultados han sido almacenados en la base de datos con ID: {{ $databaseId }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        @endif

        <!-- Debug Information -->
        <details class="mt-8">
            <summary class="cursor-pointer text-blue-500 hover:text-blue-700">Información de Depuración</summary>
            <div class="bg-gray-800 text-green-400 p-4 rounded mt-2 font-mono text-sm">
                <div><strong>Tamaño del Dataset:</strong> {{ $datasetSize }}</div>
                <div><strong>Timestamp:</strong> {{ date('Y-m-d H:i:s', $results['metadata']['timestamp'] ?? time()) }}</div>
                <div><strong>Estado:</strong> {{ $results['metadata']['status'] ?? 'desconocido' }}</div>
                <div><strong>Criptografía Disponible:</strong> {{ ($results['metadata']['crypto_available'] ?? true) ? 'Sí' : 'No' }}</div>
                @if(isset($results['metadata']['error']))
                    <div class="mt-2 text-red-400">
                        <strong>Error:</strong> {{ $results['metadata']['error'] }}
                    </div>
                @endif
            </div>
        </details>
    </div>
</body>
</html>