<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashDeals extends Model
{
    use HasFactory;
    protected $table = "flashdeal";
    protected $fillable = [
        'activity_id','store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status','renew','message','create_new'
    ];
    public function productflashdeal(){
        return $this->hasMany(ProductFlashdeals::class, 'flashdeal_id', 'activity_id');
    }
    public function getFldSuccess(){
        return $this->productflashdeal->where('message', 'success')->count();
    }
}
