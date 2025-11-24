<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colors extends Model
{
    use HasFactory;
    protected $table = "colors";
    protected $fillable = [
        'id','name','type','status'
    ];
    public function colordesigns(){
        return $this->hasMany(DesignColor::class,'color_id','id');
    }
}
