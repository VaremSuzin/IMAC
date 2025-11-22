<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenchmarkAttackSimulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'benchmark_result_id',
        'attack_type',
        'success',
        'attempts',
        'time_taken',
        'data_compromised_percent',
        'attack_metrics'
    ];

    protected $casts = [
        'attack_metrics' => 'array',
        'time_taken' => 'decimal:2',
        'data_compromised_percent' => 'decimal:2',
    ];

    public function benchmarkResult()
    {
        return $this->belongsTo(BenchmarkResult::class);
    }
}