<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveRowJsonData extends Model
{
    use HasFactory;

    protected $table = 'save_row_json_data'; // কারণ নামটা default plural না

    protected $fillable = [
        'row_data',
        'nid',
        'pin',
        'dob',
    ];

    protected $casts = [
        'row_data' => 'array', // JSON auto array হবে
    ];

}
