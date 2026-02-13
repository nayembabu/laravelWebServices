<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'type',
        'label',
        'address',
        'account_name',
        'bank_name',
        'branch',
        'routing_number',
        'wallet_network',
        'note',
        'status',
        'created_at',
        'updated_at',
    ];

    public function userRecharge()
    {
        return $this->hasMany(UserRecharge::class, 'payment_id');
    }

}
