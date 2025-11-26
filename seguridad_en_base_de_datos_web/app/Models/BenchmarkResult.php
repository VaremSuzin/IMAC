<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BenchmarkResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'algorithm_name',
        'dataset_size',
        'encryption_time_ms',
        'decryption_time_ms',
        'memory_usage_mb',
        'cpu_usage_percent',
        'throughput_ops_sec',
        'operations_count',
        'test_data_sample',
        'performance_metrics',
        'security_metrics',
        'attack_results',
        'status'
    ];

    protected $casts = [
        'performance_metrics' => 'array',
        'security_metrics' => 'array',
        'attack_results' => 'array',
        'encryption_time_ms' => 'decimal:6',
        'decryption_time_ms' => 'decimal:6',
        'memory_usage_mb' => 'decimal:4',
        'cpu_usage_percent' => 'decimal:2',
    ];

    public function attackSimulations(): HasMany
    {
        return $this->hasMany(BenchmarkAttackSimulation::class);
    }
}