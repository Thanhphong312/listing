<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templetes extends Model
{
    use HasFactory;
    protected $table = "templetes";
    protected $fillable = [
        'id',
        'data',
        'user_id',
        'discount'
    ];
    function stafftemplate(){
        return $this->belongsTo(StaffTemplate::class, 'id','template_id');
    }
}
