<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'order_id', 'gateway', 'payment_id', 'trx_id', 'amount',
        'status', 'create_response', 'execute_response', 'error_message', 'user_id'
    ];

    protected $casts = [
        'create_response' => 'array',
        'execute_response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}


