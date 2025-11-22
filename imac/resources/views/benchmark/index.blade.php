<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benchmark Criptográfico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">🔐 Herramienta de Benchmark Criptográfico</h1>
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Configuración -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">⚙️ Configuración del Benchmark</h2>
                <form action="{{ route('benchmark.run') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="dataset_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Tamaño del Dataset
                        </label>
                        <select name="dataset_size" id="dataset_size" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="100">Pequeño (100 registros)</option>
                            <option value="1000">Mediano (1,000 registros)</option>
                            <option value="10000">Grande (10,000 registros)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        🚀 Ejecutar Benchmark
                    </button>
                </form>
            </div>

            <!-- Información -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">📊 Algoritmos Evaluados</h2>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span><strong>AES</strong> - Cifrado Simétrico</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span><strong>RSA</strong> - Cifrado Asimétrico</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span><strong>Cifrado en Flujo</strong> - Cifrado de Flujo Continuo</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                        <span><strong>Funciones Hash</strong> - Cifrado Hash Criptográfico</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('benchmark.test.python') }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                        Probar Conexión con Python
                    </a>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                    <h3 class="font-semibold text-yellow-800">📋 Métricas Evaluadas</h3>
                    <ul class="text-sm text-yellow-700 mt-2 space-y-1">
                        <li>• Tiempos de cifrado/descifrado</li>
                        <li>• Consumo de memoria RAM</li>
                        <li>• Uso de CPU</li>
                        <li>• Resistencia a ataques</li>
                        <li>• Vulnerabilidades de seguridad</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">ℹ️ Acerca de este Benchmark</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-700">🎯 Objetivo</h3>
                    <p class="text-gray-600 text-sm mt-1">
                        Comparar la eficiencia, seguridad y consumo de recursos de diferentes algoritmos 
                        criptográficos implementados en una aplicación web Laravel con base de datos MySQL.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700">🛡️ Simulaciones de Ataque</h3>
                    <p class="text-gray-600 text-sm mt-1">
                        Se evalúa la resistencia contra: Fuerza Bruta, Inyección SQL y Acceso No Autorizado.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>