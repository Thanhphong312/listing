<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Craw extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Craw'))
            ->route('etsy-crawler')
            ->icon('fas fa-palette')
            ->active("etsy-crawler*")
            ->permissions('craw.manage');
    }
}