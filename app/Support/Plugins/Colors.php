<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Colors extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Colors'))
            ->route('colors.index')
            ->icon('fas fa-palette')
            ->active("colors*")
            ->permissions('colors.manage');
    }
}