<?php
// app/Models/BenchmarkData.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenchmarkData extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'address',
        'notes',
        'encrypted_data',
        'encryption_type',
        'key_size'
    ];
}