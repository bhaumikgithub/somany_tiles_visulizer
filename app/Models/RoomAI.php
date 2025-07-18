<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAI extends Model
{
    use HasFactory;

    protected $table = 'room_ais';

    protected $fillable = ['thumbnailUrl','file','visitorId'];
}
