<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','user_id', 'name', 'url','type'
    ];
    
}
