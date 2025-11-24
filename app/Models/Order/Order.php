<?php

namespace Vanguard\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Vanguard\Models\Store\Store;
use Vanguard\User;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'tiktok_order_id',
        'user_id',
        'store_id',
        'tracking_number',
        'original_shipping_fee',
        'original_total_product_price',
        'seller_discount',
        'shipping_fee',
        'total_amount',
        'order_status',
        'tiktok_create_date',
        'net_revenue',
        'base_cost',
        'net_profits',
        'design_fee',
        'created_at',
        'updated_at',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class,'order_id', 'id');
    }
}

