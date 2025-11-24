<?php

namespace Vanguard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCrawl extends Model
{
    use HasFactory;
    protected $table = 'product_crawl';
    protected $fillable = ['listing_id', 'title', 'description', 'price'];

    public function designs()
    {
        return $this->hasMany(DesignCrawl::class, 'product_id');
    }
}
