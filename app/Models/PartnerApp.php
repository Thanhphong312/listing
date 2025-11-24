<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\User;

class PartnerApp extends Model
{
    use HasFactory;
    const SORTABLE = [
        'created_at'
    ];
    protected $primaryKey = 'id';
    protected $fillable = [
        'id','app_name','app_key','app_secret','auth_link','proxy','seller_id','status','webhook_domain','count_shop_connect','created_at','updated_at'
    ];
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }
    public function stores()
    {
        return $this->hasMany(Store::class, 'partner_id', 'id');
    }
}
