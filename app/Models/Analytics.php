<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','session_id','event_type','event_details','event_data','ip_address','user_agent'];

    protected $casts = ['event_data' => 'array'];
}
