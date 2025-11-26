<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Benchmarks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">📊 Historial de Benchmarks</h1>
        
        <div class="mb-4">
            <a href="{{ route('benchmark.index') }}" class="text-blue-500 hover:text-blue-700">← Volver al Benchmark</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left">Algoritmo</th>
                        <th class="py-3 px-4 text-left">Tamaño</th>
                        <th class="py-3 px-4 text-left">Tiempo Cifrado</th>
                        <th class="py-3 px-4 text-left">Memoria</th>
                        <th class="py-3 px-4 text-left">Rendimiento</th>
                        <th class="py-3 px-4 text-left">Fecha</th>
                        <th class="py-3 px-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr class="border-b">
                        <td class="py-3 px-4 font-medium">{{ $result->algorithm_name }}</td>
                        <td class="py-3 px-4">{{ $result->dataset_size }}</td>
                        <td class="py-3 px-4">{{ number_format($result->encryption_time_ms, 3) }} ms</td>
                        <td class="py-3 px-4">{{ number_format($result->memory_usage_mb, 2) }} MB</td>
                        <td class="py-3 px-4">{{ number_format($result->throughput_ops_sec, 0) }} ops/s</td>
                        <td class="py-3 px-4">{{ $result->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-4">
                            <a href="{{ route('benchmark.details', $result->id) }}" class="text-blue-500 hover:text-blue-700">
                                Ver Detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $results->links() }}
        </div>
    </div>
</body>
</html>