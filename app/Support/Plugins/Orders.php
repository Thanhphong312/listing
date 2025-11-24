<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Orders extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Orders'))
            ->route('orders.index')
            ->icon('fas fa-list')
            ->active("orders*")
            ->permissions('orders.manage');
    }
}