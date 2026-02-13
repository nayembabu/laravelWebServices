<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
        protected $table = 'services';

        protected $fillable = [
            'name',
            'description',
            'rate',
            'status',
            'category',
            'description'
        ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}


