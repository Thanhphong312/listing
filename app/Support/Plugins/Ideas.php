<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Ideas extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Ideas'))
            ->route('ideas.index')
            ->icon('fas fa-lightbulb')
            ->active("ideas*")
            ->permissions('ideas.manage');
    }
}