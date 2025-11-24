<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignMetas extends Model
{
    use HasFactory;

    protected $table = 'design_metas';
    protected $fillable = ['design_id', 'thumbnail', 'key', 'value'];

    public function design()
    {
        return $this->belongsTo(Designs::class, 'design_id');
    }
}
