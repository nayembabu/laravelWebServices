<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;

    protected $table = 'voters';

    protected $fillable = [
        'nid',
        'pin',
        'formNo',
        'sl_no',
        'father_nid',
        'mother_nid',
        'religion',
        'mobile',
        'voterNo',
        'voterArea',
        'education',
        'occupation',
        'status',
        'nameBangla',
        'nameEnglish',
        'dateOfBirth',
        'birthPlace',
        'fatherName',
        'motherName',
        'spouseName',
        'gender',
        'bloodGroup',
        'presentAddress',
        'permanentAddress',
        'address',
        'image_photo',
        'image_sign',
        'issueDate',
        'user_id',
    ];

    protected $casts = [
        'dateOfBirth' => 'date',
        'issueDate'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



