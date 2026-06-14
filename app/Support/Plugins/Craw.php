<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Craw extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Crawl'))
            ->icon('fas fa-spider')
            ->permissions('craw.manage')
            ->addChildren([
                Item::create(__('Etsy Crawler'))
                    ->route('etsy-crawler')
                    ->icon('fas fa-store')
                    ->active('etsy-crawler*')
                    ->permissions('craw.manage'),
                Item::create(__('TikTok Crawler'))
                    ->route('tiktok-crawl')
                    ->icon('fab fa-tiktok')
                    ->active('tiktok-crawl*')
                    ->permissions('craw.manage'),
            ]);
    }
}