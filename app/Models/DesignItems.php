<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignItems extends Model
{
    use HasFactory;

    protected $table = "design_items";
    protected $fillable = [
        'id', 'design_id', 'number_side' ,'category_id', 'front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design'
    ];
    public function design(){
        return $this->belongsTo(Designs::class, 'design_id','id');
    }
    public function categoryDesignItems(){
        return $this->hasMany(CategoryDesignItems::class, 'design_item_id','id');
    }
    public function designmetas(){
        return $this->hasMany(DesignMetas::class, 'design_item_id', 'id');
    }
}
