<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('benchmark_results', function (Blueprint $table) {
            $table->id();
            $table->string('algorithm_name');
            $table->string('dataset_size');
            $table->decimal('encryption_time_ms', 10, 6);
            $table->decimal('decryption_time_ms', 10, 6);
            $table->decimal('memory_usage_mb', 10, 4);
            $table->decimal('cpu_usage_percent', 5, 2);
            $table->integer('throughput_ops_sec');
            $table->integer('operations_count');
            $table->text('test_data_sample')->nullable();
            $table->json('performance_metrics')->nullable();
            $table->json('security_metrics')->nullable();
            $table->json('attack_results')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
            
            // Índices para búsquedas eficientes
            $table->index(['algorithm_name', 'dataset_size']);
            $table->index('created_at');
        });
        
        Schema::create('benchmark_attack_simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benchmark_result_id')->constrained()->onDelete('cascade');
            $table->string('attack_type');
            $table->boolean('success');
            $table->integer('attempts')->nullable();
            $table->decimal('time_taken', 10, 2)->nullable();
            $table->decimal('data_compromised_percent', 5, 2)->nullable();
            $table->json('attack_metrics')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('benchmark_attack_simulations');
        Schema::dropIfExists('benchmark_results');
    }
};