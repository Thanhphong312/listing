<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDesignItems extends Model
{
    use HasFactory;
    protected $table = "category_design_items";
    protected $fillable = [
        'id','design_item_id','category_id'
    ];
    public function designItem(){
        return $this->belongsTo(Designs::class, 'design_item_id', 'id');
    }
    public function category(){
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
