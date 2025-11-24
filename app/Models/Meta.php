<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;
    const SORTABLE = ['created_at'];

    protected $fillable = ['id','email_id','user_id','store_id','key','value'];
    
    public function store(){
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
