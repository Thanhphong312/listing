<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignColor extends Model
{
    use HasFactory;
    protected $table = "design_colors";
    protected $fillable = [
        'id', 'design_id', 'color_id'
    ];
    public function design(){
        return $this->belongsTo(Designs::class,'design_id', 'id');
    }
    public function color(){
        return $this->belongsTo(Colors::class,'color_id', 'id');
    }
}
