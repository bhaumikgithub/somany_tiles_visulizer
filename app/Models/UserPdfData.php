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
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'state',
        'city',
        'user_account',
        'unique_id',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
