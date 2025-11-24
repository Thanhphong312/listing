<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTiktoks extends Model
{
    use HasFactory;
    protected $table = "product_tiktoks";
    protected $fillable = [
        'store_id',
        'remote_id',
        'title',
        'status',
        'skus',
        'is_flashdeal',
        'discount'
    ];

    protected $casts = [
        'remote_id' => 'string',
    ];
    
    public function flashdealproduct()
    {
        return $this->hasOne(ProductFlashdeals::class, 'product_id', 'remote_id');
    }
    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
    public function storeProduct(){
        return $this->belongsTo(StoreProducts::class, 'remote_id', 'remote_id');
    }
    // public function flashdeal()
    // {
    //     return $this->hasOne(FlashDeals::class, 'id', 'remote_id');
    // }
}
