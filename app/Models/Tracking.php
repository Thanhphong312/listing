<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItemMeta;

class Tracking extends Model
{
    use HasFactory;
    protected $table = "tracking";
    protected $fillable = [
        'tracking_id',
        'tracking_link',
        'order_id',
        'status',
        'method',
        'service',
        'total_day',
        'created_at',
        'updated_at',
        'update_time'
    ];
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function metas(){
        return $this->hasMany(OrderItemMeta::class, 'tracking_id', 'id');
    }
}
