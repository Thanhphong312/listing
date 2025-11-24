<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Reports extends Plugin
{
    public function sidebar()
    {
        $generareport = Item::create(__('Generate'))
            ->route('report.index')
            ->active("reports/generate")
            ->permissions('reports.manage');

        $reports = Item::create(__('Sellers'))
            ->route('report.sellers')
            ->active("reports/sellers")
            ->permissions('reports.manage');
        $payouts = Item::create(__('Payouts'))
            ->route('report.payouts')
            ->active("reports/payouts")
            ->permissions('reports.manage');
        return Item::create(__('Reports'))
            ->href('#roles-dropdown')
            ->icon('bx bxs-bar-chart-alt-2')
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['reports.manage'],
                );
            })
            ->addChildren([
                $generareport,
                $reports,
                $payouts
            ]);
    }
}
