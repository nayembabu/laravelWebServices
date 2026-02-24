<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'invoice', 'amount', 'status', 'customer_name', 'customer_phone', 'user_id'
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
