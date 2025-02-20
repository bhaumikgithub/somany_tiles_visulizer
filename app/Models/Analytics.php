<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = ['session_id','pincode','zone','category','room','viewed_tiles','used_tiles','tile_usage_count','visited_at','user_logged_in','downloaded_pdf','showroom'];
}
