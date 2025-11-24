<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class DesignItems extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Design 2'))
            ->route('designItems.index')
            ->icon('fas fa-image')
            ->active("designItems*")
            ->permissions('designItems.manage');
    }
}