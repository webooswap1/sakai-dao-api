<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferrerRewardHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'amount',
        'last_timestamp'
    ];
}
