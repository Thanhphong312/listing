<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class PartnerApps extends Plugin
{
    public function sidebar()
    {
        return Item::create(__('PartnerApps'))
            ->route('partner-apps.index')
            ->icon('fas fa-warehouse')
            ->active("partner-apps*")
            ->permissions('partner-apps.manage');
    }
}
