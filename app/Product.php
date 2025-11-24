<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Vanguard\Models\Categories;
use Vanguard\Models\StoreProducts;

class Product extends Model
{
  use HasFactory;

  const SORTABLE = ['created_at'];

  protected $fillable = ['id', 'carogory_id','user_id', 'data', 'status','templete_id','discount'];

  public function category(){
    return $this->hasMany(Categories::class,'carogory_id','id');
  }
  public function storeproducts(){
    return $this->hasMany(StoreProducts::class, 'product_id','id');
  }
}
