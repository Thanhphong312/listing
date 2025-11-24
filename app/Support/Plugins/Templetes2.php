<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Templetes2 extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Templates2'))
            ->route('templates2.index')
            ->icon('fas fa-pencil-square')
            ->active("templatess*")
            ->permissions('templates.manage');
    }
}
