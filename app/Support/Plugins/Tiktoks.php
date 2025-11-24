<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Tiktoks extends Plugin
{
    public function sidebar()
    {
        $partnerapps = Item::create(__('PartnerApps'))
            ->route('partner-apps.index')
            // ->icon('fas fa-warehouse')
            ->active("partner-apps*")
            ->permissions('partner-apps.manage');
        
        $stores = Item::create(__('Stores'))
            ->route('stores.index')
            ->active("store/*")
            ->permissions('stores.manage');
        $flashdeals = Item::create(__('Flashdeals'))
            ->route('flashdeals.index')
            // ->icon('fas fa-warehouse')
            ->active("flashdeals/*")
            ->permissions('flashdeals.manage');
            
        return Item::create(__('Tiktoks'))
            ->href('#roles-dropdown')
            ->icon('fab fa-tiktok')  // Corrected the TikTok icon class
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['tiktoks.manage'],
                    allRequired: false
                );
            })
            ->addChildren([
                $partnerapps,
                $stores,
                // $flashdeals
            ]);
    
    }
}
