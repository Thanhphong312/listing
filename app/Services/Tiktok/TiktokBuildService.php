<?php

namespace App\Services\Tiktok;

use GuzzleHttp\RequestOptions;
use EcomPHP\TiktokShop\Resource;

use App\Repositories\Email\Tiktik;
use App\Repositories\Tiktok\TiktokAppRepository;

class TiktokBuildService extends Resource
{
    protected $category = 'product';

    public function checkpPoductListing($params)
    {
        return $this->call('GET', 'products/diagnoses', [
            RequestOptions::QUERY => $params,
        ]);
    }

}
