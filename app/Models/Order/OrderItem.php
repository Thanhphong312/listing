<?php

namespace Vanguard\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'order_id',
        'product_name',
        'product_id',
        'sku_id',
        'quantity',
        'sku_image',
        'sku_name',
        'fteeck_item_id'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
