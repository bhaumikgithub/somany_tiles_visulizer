<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory,softDeletes;

    protected $fillable = [
        'room_id','room_name'.'room_type',
        'current_room_design','current_room_thumbnail',
        'tiles_json','cart_id'
    ];
}
