<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Product;

class StoreProducts extends Model
{
    use HasFactory;
    protected $table = "store_products";
    protected $fillable = [
        'store_id', 'product_id', 'data', 'remote_id', 'message'
    ];
    public function store(){
        return $this->belongsTo(Store::class,'store_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
}
