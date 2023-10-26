<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Stake extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'address',
        'amount',
        'txHash',
    ];
}
