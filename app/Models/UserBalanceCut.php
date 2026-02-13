<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalanceCut extends Model
{
    use HasFactory;

    protected $table = 'user_balance_cuts'; // টেবিল নাম

    protected $fillable = [
        'user_id',
        'amount',
        'reason',
        'note',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}