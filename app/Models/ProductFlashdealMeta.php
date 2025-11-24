<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFlashdealMeta extends Model
{
    use HasFactory;
    const SORTABLE = ['created_at'];
    protected $table = 'product_flashdeal_meta';
    protected $fillable = ['id','product_flashdeal_id','meta_key','meta_value'];
}
