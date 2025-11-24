<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\User;

class TiktokProxy extends Model
{
    use HasFactory;
    const SORTABLE = [
        'created_at'
    ];
    protected $table = 'tiktok_proxy';

    protected $fillable = [
        'ip','seller_id','note','status','status_class_c'
    ];
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }
}
