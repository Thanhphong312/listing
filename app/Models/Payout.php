<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'store_id', 'user_id','payout_id', 'payout_amout', 'settlement_amount', 'amount_before_exchange', 'reserve_amount', 'date', 'date_complete','status','bank_account'
    ];
}
