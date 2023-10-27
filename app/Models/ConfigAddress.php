<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
    ];
}
