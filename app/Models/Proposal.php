<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'owner',
        'meta_data',
        'txHash',
        'proposal_id',
    ];

    protected $casts = [
        'meta_data' => 'json'
    ];
}
