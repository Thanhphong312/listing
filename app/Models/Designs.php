<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\User;

class Designs extends Model
{
    use HasFactory;
    protected $table = 'designs';
    protected $fillable = [
        'user_id', 'title', 'niche', 'mix', 'sku', 'tag', 'thumbnail'
    ];
    public function designItems()
    {
        return $this->hasMany(DesignItems::class, 'design_id', 'id');
    }

 
    public function idea()
    {
        return $this->belongsTo(Ideas::class, 'idea_id', 'id');
    }

    public function designcolors()
    {
        return $this->hasMany(DesignColor::class, 'design_id', 'id');
    }
    public function designMetas()
    {
        return $this->hasMany(DesignMetas::class, 'design_id', 'id');
    }

    public function metas()
    {
        return $this->hasMany(DesignMetas::class, 'design_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');

    }
}
