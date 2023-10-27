<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'abi',
    ];

    protected $casts = [
    ];
}
