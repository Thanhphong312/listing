<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Models\Order\Order;
use Vanguard\User;

class Transaction extends Model
{
    protected $table = "transactions";
    protected $fillable = [
        'seller_id',
        'order_id',
        'amount',
        'fee',
        'remaining_balance',
        'type',
        'status',
        'note',
        'created_at',
        'updated_at',
        'object'
    ];
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }
}
