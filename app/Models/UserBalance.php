<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    use HasFactory;

    protected $appends = [
        'last_stake_date'
    ];

    public function getLastStakeDateAttribute()
    {
        $stake = Stake::where('address', $this->address)->orderBy('created_at', 'desc')->first();
        if ($stake) {
            return $stake->created_at;
        }
        return null;
    }
}
