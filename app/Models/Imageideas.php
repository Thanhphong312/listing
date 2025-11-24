<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imageideas extends Model
{
    use HasFactory;

    protected $table = "image_ideas";
    protected $fillable = [
        'id', 'idea_id', 'url'
    ];
    public function idea(){
        return $this->belongsTo(Ideas::class, 'id', 'idea_id');
    }
}
