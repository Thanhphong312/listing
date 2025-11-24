<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
namespace EcomPHP\TiktokShop\Resources;

use GuzzleHttp\RequestOptions;
use EcomPHP\TiktokShop\Resource;

class TiktokBuildController extends Controller
{
    private $resource;
    public function __construct() {
        $this->resource = new ConcreteResource();
    }
    public function checkpPoductListing(){
        return $this->resource->call('GET', 'products/diagnoses');
    }
}
