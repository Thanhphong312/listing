<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Payouts extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('Payouts'))
            ->route('payouts.index')
            ->icon('fas fa-dollar')
            ->active("payouts*")
            ->permissions('payouts.manage');
    }
}