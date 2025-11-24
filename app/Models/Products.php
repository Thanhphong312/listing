<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';

    // Đặt các trường có thể mass assignable
    protected $fillable = [
        'store_data_id', 'data', 'status',
    ];
}
