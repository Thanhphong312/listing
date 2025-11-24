<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Stores extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Stores'))
            ->route('stores.index')
            ->icon('fas fa-warehouse')
            ->active("store*")
            ->permissions('stores.manage');
    }
}
