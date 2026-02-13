<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRecharge extends Model
{
    use HasFactory;

    protected $table = 'user_recharges';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'payment_method',
        'payment_id',
        'trx_id',
        'note',
        'approved_at',
        'approved_by',
        'created_at',
        'updated_at',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship
    public function paymentmethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}
