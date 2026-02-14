<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceOrder extends Model
{
    use HasFactory;

    protected $table = 'user_service_orders';

    protected $fillable = [
        'user_id',
        'service_id',
        'order_details',
        'amount',
        'status',
        'admin_id',
        'check_admin_id',
        'admin_file',
        'admin_assign_time',
        'delivary_time',
        'admin_note',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
