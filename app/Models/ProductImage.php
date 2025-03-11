<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'sku',
        'image_url',
        'api_width',
        'api_height',
        'actual_width',
        'actual_height',
        'status',
    ];
}
