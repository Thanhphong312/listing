<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFlashdeals extends Model
{
    use HasFactory;
    protected $table = "product_flashdeal";
    protected $fillable = [
        'flashdeal_id', 'product_id', 'discount', 'quantity_limit', 'quantity_per_user', 'skus','message','success','status','total_sku',
    ];
    
    public function flashdeal(){
        return $this->belongsTo(FlashDeals::class, 'flashdeal_id', 'activity_id');
    }
    public function tiktok(){
        return $this->belongsTo(ProductTiktoks::class, 'product_id', 'remote_id');
    }

}
