<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Models\Order\Order;
use Vanguard\User;

class TimeLine extends Model
{
    use HasFactory;
    protected $table = "timeline";
    protected $fillable = [
        'object',
        'object_id',
        'owner_id',
        'action',
        'note',
    ];
    public function order(){
        return $this->belongsTo(Order::class, 'object_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
