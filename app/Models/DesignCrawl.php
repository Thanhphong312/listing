<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignCrawl extends Model
{
    use HasFactory;
    protected $table = 'design_crawls';
    protected $fillable = ['product_id', 'url', 'type'];

    public function product()
    {
        return $this->belongsTo(ProductCrawl::class, 'product_id');
    }
}
