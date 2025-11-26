<?php

use App\Http\Controllers\BenchmarkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/benchmark');
});

// Benchmark Routes
Route::get('/benchmark', [BenchmarkController::class, 'index'])->name('benchmark.index');
Route::post('/benchmark/run', [BenchmarkController::class, 'runBenchmark'])->name('benchmark.run');
Route::get('/benchmark/test-python', [BenchmarkController::class, 'testPython'])->name('benchmark.test.python');
Route::get('/benchmark/history', [BenchmarkController::class, 'showHistory'])->name('benchmark.history');
Route::get('/benchmark/algorithm/{algorithm}', [BenchmarkController::class, 'showAlgorithmStats'])->name('benchmark.algorithm.stats');
Route::get('/benchmark/results/{id}', [BenchmarkController::class, 'showResultDetails'])->name('benchmark.details');