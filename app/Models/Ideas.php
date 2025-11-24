<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ideas extends Model
{
    use HasFactory;

    protected $table = "ideas";
    protected $fillable = [
        'id', 'user_id', 'title', 'description'
    ];
    public function imageideas(){
        return $this->hasMany(Imageideas::class, 'idea_id', 'id');
    }
}
