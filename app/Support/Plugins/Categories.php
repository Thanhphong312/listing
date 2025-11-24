<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Categories extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Categories'))
            ->route('categories.index')
            ->icon('fas fa-bars')
            ->active("categories*")
            ->permissions('categories.manage');
    }
}