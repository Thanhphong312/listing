<?php

namespace Vanguard\Providers;

use Vanguard\Plugins\VanguardServiceProvider as BaseVanguardServiceProvider;
use Vanguard\Support\Plugins\Dashboard\Widgets\BannedUsers;
use Vanguard\Support\Plugins\Dashboard\Widgets\LatestRegistrations;
use Vanguard\Support\Plugins\Dashboard\Widgets\NewUsers;
use Vanguard\Support\Plugins\Dashboard\Widgets\RegistrationHistory;
use Vanguard\Support\Plugins\Dashboard\Widgets\TotalUsers;
use Vanguard\Support\Plugins\Dashboard\Widgets\UnconfirmedUsers;
use Vanguard\Support\Plugins\Dashboard\Widgets\UserActions;
use \Vanguard\UserActivity\Widgets\ActivityWidget;

class VanguardServiceProvider extends BaseVanguardServiceProvider
{
    /**
     * List of registered plugins.
     *
     * @return array
     */
    protected function plugins()
    {
        return [
            \Vanguard\Support\Plugins\Dashboard\Dashboard::class,
            \Vanguard\Support\Plugins\Reports::class,
            \Vanguard\Support\Plugins\Tiktoks::class,
            \Vanguard\Support\Plugins\Categories::class,
            \Vanguard\Support\Plugins\Colors::class,
            \Vanguard\Support\Plugins\Ideas::class,
            \Vanguard\Support\Plugins\Designs::class,
            \Vanguard\Support\Plugins\DesignItems::class,
            \Vanguard\Support\Plugins\DesignMetas::class,
            \Vanguard\Support\Plugins\Images::class,
            \Vanguard\Support\Plugins\Templetes::class,
            \Vanguard\Support\Plugins\Templetes2::class,
            \Vanguard\Support\Plugins\Products::class,
            \Vanguard\Support\Plugins\Orders::class,
            \Vanguard\Support\Plugins\Craw::class,
            \Vanguard\Support\Plugins\Payouts::class,
            \Vanguard\Support\Plugins\Teams::class,
            \Vanguard\Support\Plugins\Systems::class,
            \Vanguard\UserActivity\UserActivity::class,
            \Vanguard\Announcements\Announcements::class,
            
        ];
    }

    /**
     * Dashboard widgets.
     *
     * @return array
     */
    protected function widgets()
    {
        return [
            UserActions::class,
            TotalUsers::class,
            NewUsers::class,
            BannedUsers::class,
            UnconfirmedUsers::class,
            RegistrationHistory::class,
            LatestRegistrations::class,
            ActivityWidget::class,
        ];
    }
}
