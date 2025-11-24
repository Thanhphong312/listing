<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = "images";
    protected $fillable = [
        'order_id',
        'order_item_id',
        'file_url',
    ];
}
