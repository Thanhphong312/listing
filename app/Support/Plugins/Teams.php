<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Teams extends Plugin
{
    public function sidebar()
    {
     
        return Item::create(__('Teams'))
            ->route('teams.index')
            ->icon('fas fa-users')
            ->active("teams*")
            ->permissions('teams.manage');
    }
}
