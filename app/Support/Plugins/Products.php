<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Products extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Products'))
            ->route('products.index')
            ->icon('fas fa-tshirt')
            ->active("product*")
            ->permissions('products.manage');
    }
}
