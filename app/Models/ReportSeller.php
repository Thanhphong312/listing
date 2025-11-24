<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSeller extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'total_order',
        'total_item',
        'total_cost'
    ];
}
