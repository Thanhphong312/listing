<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\User;
use Vanguard\Models\ProductTiktoks;

class Store extends Model
{
    use HasFactory;
    const SORTABLE = ['created_at'];

  protected $fillable = [
    'id','user_id', 'name', 'type', 'status', 'timezone','keyword','watermark', 'open_id','partner_id','syncfld', 'create_flashdeal','shop_code'
  ];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];


  /**
   * Get the metadata record associated with the post.
   */
  public function metadata()
  {
    return $this->morphOne(Meta::class, 'metadata');
  }
  public function user(){
    return $this->belongsTo(User::class);
  }
  public function staff(){
    return $this->belongsTo(User::class,'staff_id');
  }
  public function storeproducts(){
    return $this->hasMany(StoreProducts::class, 'store_id','id');
  }
  public function producttiktoks(){
    return $this->hasMany(ProductTiktoks::class, 'store_id', 'id');
}
}
