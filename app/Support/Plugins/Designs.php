<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Designs extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Designs'))
            ->route('designs.index')
            ->icon('fas fa-fill')
            ->active("designs*")
            ->permissions('designs.manage');
    }
}