<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Templetes extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Templates'))
            ->route('templates.index')
            ->icon('fas fa-pencil-square')
            ->active("templates*")
            ->permissions('templates.manage');
    }
}
