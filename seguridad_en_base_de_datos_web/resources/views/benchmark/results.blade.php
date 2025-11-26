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
                            @if(isset($results['analysis']['overall_security_score']))
                                {{ number_format($results['analysis']['overall_security_score'], 1) }}%
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-orange-800">Puntuación Seguridad</div>
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
                            <span class="{{ ($results['metadata']['status'] ?? '') === 'completado' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $results['metadata']['status'] ?? 'desconocido' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-semibold">Criptografía:</span>
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
                        @if(isset($results['performance']['aes']['operations']))
                            ({{ $results['performance']['aes']['operations'] }} operaciones AES)
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
                                            {{ isset($metrics['avg_time_encrypt']) ? number_format($metrics['avg_time_encrypt'], 3) : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4 border">
                                            @if($algoName !== 'hash')
                                                {{ isset($metrics['avg_time_decrypt']) ? number_format($metrics['avg_time_decrypt'], 3) : 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border">
                                            {{ isset($metrics['throughput']) ? number_format($metrics['throughput'], 0) : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4 border">
                                            {{ isset($metrics['avg_memory']) ? number_format($metrics['avg_memory'], 2) : 'N/A' }}
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
                @if(isset($results['performance']) && count($results['performance']) > 0)
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
                        @else
                            <p class="text-green-700">No disponible</p>
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
                        @else
                            <p class="text-blue-700">No disponible</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Security Analysis -->
            @if(isset($results['security']) && count($results['security']) > 0)
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
                            @foreach($results['security'] as $algoName => $metrics)
                                @if(is_array($metrics))
                                <tr>
                                    <td class="py-2 px-4 border font-medium">{{ strtoupper($algoName) }}</td>
                                    <td class="py-2 px-4 border">
                                        {{ isset($metrics['key_strength']) ? number_format($metrics['key_strength'] * 100, 1) . '%' : 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        {{ isset($metrics['vulnerability']) ? number_format($metrics['vulnerability'] * 100, 1) . '%' : 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        {{ isset($metrics['resistance']) ? number_format($metrics['resistance'] * 100, 1) . '%' : 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4 border text-sm">{{ $metrics['recommendation'] ?? 'N/A' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- SIMULACIONES DE ATAQUE PRINCIPALES -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-6">🛡️ Simulaciones de Ataque</h2>
                
                <!-- SQL Injection -->
                <div class="mb-8 p-6 border-2 border-red-200 rounded-lg bg-red-50">
                    <h3 class="text-xl font-semibold text-red-700 mb-4">🗃️ Ataque de Inyección SQL</h3>
                    
                    @if(isset($results['attacks']['sql_injection']))
                        @php $sqlAttack = $results['attacks']['sql_injection']; @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Éxito del Ataque:</span>
                                <span class="ml-2 {{ $sqlAttack['success'] ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">
                                    {{ $sqlAttack['success'] ? 'VULNERABLE' : 'PROTEGIDO' }}
                                </span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Tasa de Éxito:</span>
                                <span class="ml-2 {{ $sqlAttack['success_rate'] > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                    {{ $sqlAttack['success_rate'] ?? 0 }}%
                                </span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Datos Comprometidos:</span>
                                <span class="ml-2 {{ $sqlAttack['data_compromised'] > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                    {{ $sqlAttack['data_compromised'] ?? 0 }}%
                                </span>
                            </div>
                        </div>

                        @if(isset($sqlAttack['successful_injections']) && count($sqlAttack['successful_injections']) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-red-700 mb-2">💉 Inyecciones Exitosas Detectadas:</h4>
                            <div class="space-y-2">
                                @foreach($sqlAttack['successful_injections'] as $injection)
                                    <div class="bg-red-100 p-3 rounded border border-red-300">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                                            <div><strong>Input Vulnerable:</strong> {{ $injection['input'] ?? 'N/A' }}</div>
                                            <div><strong>Patrón de Ataque:</strong> <code class="bg-red-200 px-1 rounded">{{ $injection['pattern'] ?? 'N/A' }}</code></div>
                                            <div><strong>Impacto:</strong> 
                                                @php
                                                    $impact = $injection['impact'] ?? 'N/A';
                                                    $impactClass = ($impact === 'Alto') ? 'text-red-600 font-bold' : 'text-orange-600';
                                                @endphp
                                                <span class="{{ $impactClass }}">
                                                    {{ $impact }}
                                                </span>
                                            </div>
                                        </div>
                                        @if(isset($injection['query']))
                                        <div class="mt-2">
                                            <strong>Consulta Simulada:</strong>
                                            <div class="bg-black text-green-400 p-2 rounded mt-1 font-mono text-xs overflow-x-auto">
                                                {{ $injection['query'] }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="bg-yellow-50 p-4 rounded border border-yellow-200">
                            <h4 class="font-semibold text-yellow-700 mb-2">🛡️ Recomendación de Seguridad:</h4>
                            <p class="text-yellow-800">{{ $sqlAttack['prevention'] ?? 'Usar consultas parametrizadas y ORM' }}</p>
                        </div>
                    @else
                        <div class="text-center p-4 bg-gray-100 rounded">
                            <p class="text-gray-600">No se realizó simulación de inyección SQL</p>
                        </div>
                    @endif
                </div>

                <!-- Fuerza Bruta -->
                <div class="mb-8 p-6 border-2 border-orange-200 rounded-lg bg-orange-50">
                    <h3 class="text-xl font-semibold text-orange-700 mb-4">🔓 Ataque de Fuerza Bruta</h3>
                    
                    @if(isset($results['attacks']['brute_force']))
                        @php $bruteForce = $results['attacks']['brute_force']; @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Éxito del Ataque:</span>
                                <span class="ml-2 {{ $bruteForce['success'] ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">
                                    {{ $bruteForce['success'] ? 'VULNERABLE' : 'RESISTENTE' }}
                                </span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Intentos Realizados:</span>
                                <span class="ml-2 font-bold">{{ number_format($bruteForce['attempts'] ?? 0) }}</span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Tiempo de Ataque:</span>
                                <span class="ml-2">{{ number_format($bruteForce['time_taken'] ?? 0, 2) }}s</span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Algoritmo Objetivo:</span>
                                <span class="ml-2">{{ $bruteForce['algorithm_target'] ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Complejidad Computacional:</span>
                                <span class="ml-2 font-mono text-sm">{{ $bruteForce['complexity'] ?? 'N/A' }}</span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Tiempo Total Estimado:</span>
                                <span class="ml-2 font-mono text-sm">{{ $bruteForce['estimated_total_time'] ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded border border-blue-200">
                            <h4 class="font-semibold text-blue-700 mb-2">🔐 Método de Protección:</h4>
                            <p class="text-blue-800">Usar claves largas (256-bit), implementar rate limiting y sistemas de detección de intentos fallidos.</p>
                        </div>
                    @else
                        <div class="text-center p-4 bg-gray-100 rounded">
                            <p class="text-gray-600">No se realizó simulación de fuerza bruta</p>
                        </div>
                    @endif
                </div>

                <!-- Acceso sin Credenciales -->
                <div class="p-6 border-2 border-purple-200 rounded-lg bg-purple-50">
                    <h3 class="text-xl font-semibold text-purple-700 mb-4">👤 Acceso sin Credenciales a Base de Datos</h3>
                    
                    @if(isset($results['attacks']['unauthorized_access']))
                        @php $accessAttack = $results['attacks']['unauthorized_access']; @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Acceso Obtenido:</span>
                                <span class="ml-2 {{ $accessAttack['success'] ? 'text-red-600 font-bold' : 'text-green-600 font-bold' }}">
                                    {{ $accessAttack['success'] ? 'VULNERABLE' : 'PROTEGIDO' }}
                                </span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Nivel de Acceso:</span>
                                @php
                                    $accessLevel = $accessAttack['access_level'] ?? 'N/A';
                                    if ($accessLevel === 'Completo') {
                                        $accessClass = 'text-red-600 font-bold';
                                    } elseif ($accessLevel === 'Parcial') {
                                        $accessClass = 'text-orange-600';
                                    } else {
                                        $accessClass = 'text-green-600';
                                    }
                                @endphp
                                <span class="ml-2 {{ $accessClass }}">
                                    {{ $accessLevel }}
                                </span>
                            </div>
                            <div class="bg-white p-4 rounded border">
                                <span class="font-semibold">Datos Expuestos:</span>
                                <span class="ml-2 {{ $accessAttack['data_exposed'] > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                    {{ $accessAttack['data_exposed'] ?? 0 }}%
                                </span>
                            </div>
                        </div>

                        @if(isset($accessAttack['vulnerable_endpoints']) && count($accessAttack['vulnerable_endpoints']) > 0)
                        <div class="mb-4">
                            <h4 class="font-semibold text-purple-700 mb-2">🚪 Endpoints Vulnerables:</h4>
                            <div class="space-y-2">
                                @foreach($accessAttack['vulnerable_endpoints'] as $endpoint)
                                    <div class="bg-purple-100 p-3 rounded border border-purple-300">
                                        <div class="flex justify-between items-center">
                                            <span class="font-mono text-sm">{{ $endpoint['url'] ?? 'N/A' }}</span>
                                            @php
                                                $risk = $endpoint['risk'] ?? 'N/A';
                                                if ($risk === 'Alto') {
                                                    $riskClass = 'bg-red-500 text-white';
                                                } elseif ($risk === 'Medio') {
                                                    $riskClass = 'bg-orange-500 text-white';
                                                } else {
                                                    $riskClass = 'bg-yellow-500 text-black';
                                                }
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $riskClass }}">
                                                {{ $risk }}
                                            </span>
                                        </div>
                                        @if(isset($endpoint['description']))
                                        <p class="text-sm text-purple-800 mt-1">{{ $endpoint['description'] }}</p>
                                        @endif
                                        @if(isset($endpoint['data_exposed_percent']))
                                        <p class="text-sm text-purple-600 mt-1">
                                            <strong>Datos expuestos:</strong> {{ $endpoint['data_exposed_percent'] }}%
                                        </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="bg-green-50 p-4 rounded border border-green-200">
                            <h4 class="font-semibold text-green-700 mb-2">✅ Medidas de Protección:</h4>
                            <ul class="list-disc list-inside text-green-800 space-y-1">
                                <li>Implementar autenticación multi-factor (MFA)</li>
                                <li>Usar principios de mínimo privilegio</li>
                                <li>Configurar firewalls de base de datos</li>
                                <li>Auditar regularmente los permisos de acceso</li>
                                <li>{{ $accessAttack['recommendation'] ?? 'Implementar controles de acceso estrictos' }}</li>
                            </ul>
                        </div>
                    @else
                        <div class="text-center p-4 bg-gray-100 rounded">
                            <p class="text-gray-600">No se realizó simulación de acceso sin credenciales</p>
                            <p class="text-sm text-gray-500 mt-2">Esta simulación evalúa la exposición de datos sin autenticación adecuada</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Security Analysis Results -->
            @if(isset($results['analysis']) && is_array($results['analysis']))
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">📊 Análisis de Seguridad General</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg text-center">
                        <div class="text-3xl font-bold mb-2">
                            {{ $results['analysis']['overall_security_score'] ?? 0 }}
                        </div>
                        <div class="text-lg">Puntuación General</div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg text-center">
                        <div class="text-3xl font-bold mb-2">
                            {{ $results['analysis']['security_level'] ?? 'N/A' }}
                        </div>
                        <div class="text-lg">Nivel de Seguridad</div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg text-center">
                        <div class="text-3xl font-bold mb-2">
                            {{ count($results['analysis']['strengths'] ?? []) }}
                        </div>
                        <div class="text-lg">Fortalezas</div>
                    </div>
                </div>

                @if(isset($results['analysis']['strengths']) && count($results['analysis']['strengths']) > 0)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-green-700 mb-3">✅ Fortalezas</h3>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($results['analysis']['strengths'] as $strength)
                                <li class="text-green-800">{{ $strength }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if(isset($results['analysis']['weaknesses']) && count($results['analysis']['weaknesses']) > 0)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-red-700 mb-3">⚠️ Debilidades</h3>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($results['analysis']['weaknesses'] as $weakness)
                                <li class="text-red-800">{{ $weakness }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if(isset($results['analysis']['recommendations']) && count($results['analysis']['recommendations']) > 0)
                <div>
                    <h3 class="text-xl font-semibold text-blue-700 mb-3">💡 Recomendaciones</h3>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($results['analysis']['recommendations'] as $recommendation)
                                <li class="text-blue-800">{{ $recommendation }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            @endif

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
                <div><strong>Dataset Usado:</strong> {{ $results['metadata']['dataset_used'] ?? 'N/A' }}</div>
                @if(isset($results['metadata']['error']))
                    <div class="mt-2 text-red-400">
                        <strong>Error:</strong> {{ $results['metadata']['error'] }}
                    </div>
                @endif
            </div>
        </details>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Efectos visuales para las secciones de ataque
            const attackSections = document.querySelectorAll('[class*="border-2"]');
            attackSections.forEach(section => {
                section.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease';
                });
                
                section.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            console.log('Benchmark de seguridad cargado correctamente');
        });
    </script>
</body>
</html>