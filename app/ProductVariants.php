<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Models\Category;
use Vanguard\Models\Store\Store;
use Vanguard\Models\Store\StoreProduct;
use Spatie\Tags\HasTags;

class ProductVariants extends Model
{
  use HasFactory;
  use HasTags;

  const SORTABLE = ['created_at'];

  protected $primaryKey = 'variant_id';

  protected $fillable = ['product_id', 'price','sku','style','color','size','stock','mockup_src','weight','length','width','height','basecost','private_price', 'pricetwoside_public', 'pricetwoside_private', 'sleeve_print_public', 'sleeve_print_private', 'shipping_free_public', 'shipping_free_private','brand','warehouse_name','variant_id','active'];

  public function product(){
    return $this->hasOne(Product::class,'id','product_id');
  }

}
