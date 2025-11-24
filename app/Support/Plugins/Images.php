<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Images extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Images'))
            ->route('images.index')
            ->icon('fas fa-warehouse')
            ->active("images*")
            ->permissions('images.manage');
    }
}
