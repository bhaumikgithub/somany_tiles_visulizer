<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPdfData extends Model
{
    use HasFactory;

    protected $table = 'user_pdf_data';

    protected $fillable = [
        'name',
        'mobile',
        'pincode',
        'user_account',
        'unique_id',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
