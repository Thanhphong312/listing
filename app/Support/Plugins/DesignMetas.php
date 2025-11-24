<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class DesignMetas extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('DesignMetas'))
            ->route('designMetas.index')
            ->icon('fas fa-images')
            ->active("designMetas*")
            ->permissions('designMetas.manage');
    }
}