<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeRewardHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'amount',
        'accumulated_amount',
        'last_timestamp'
    ];
}
