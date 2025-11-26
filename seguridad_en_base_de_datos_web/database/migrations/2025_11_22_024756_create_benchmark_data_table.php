<?php
// database/migrations/2024_01_01_create_benchmark_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenchmarkDataTable extends Migration
{
    public function up()
    {
        Schema::create('benchmark_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password_hash');
            $table->text('address');
            $table->text('notes');
            $table->text('encrypted_data')->nullable();
            $table->string('encryption_type');
            $table->string('key_size')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('benchmark_data');
    }
}