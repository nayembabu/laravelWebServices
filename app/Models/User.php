<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'email_verified_at',
        'referral_code',
        'status',
        'show_password',
        'remember_token',
        'created_at',
        'updated_at',
        'role',
        'last_login_at',
        'referred_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'show_password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function serviceOrders()
    {
        return $this->hasMany(UserServiceOrder::class, 'user_id');
    }

    public function balanceAdds()
    {
        return $this->hasMany(UserBalanceAdd::class, 'user_id');
    }

    public function voter()
    {
        return $this->hasMany(Voter::class, 'user_id');
    }

    public function balanceCuts()
    {
        return $this->hasMany(UserBalanceCut::class, 'user_id');
    }

    public function userRecharge()
    {
        return $this->hasMany(UserRecharge::class, 'user_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    // 🔹 ইউজারের মোট ব্যালেন্স হিসাব
    public function balance()
    {
        $adds = $this->balanceAdds()->sum('amount');
        $cuts = $this->balanceCuts()->sum('amount');
        return $adds - $cuts;
    }
}
