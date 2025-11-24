<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Systems extends Plugin
{
    public function sidebar()
    {
        $users = Item::create(__('Users'))
            ->route('users.index')
            ->active("users*")
            ->permissions('users.manage');

        $roles = Item::create(__('Roles'))
            ->route('roles.index')
            ->active("roles*")
            ->permissions('roles.manage');
        
        $permissions = Item::create(__('Permissions'))
            ->route('permissions.index')
            ->active("permissions*")
            ->permissions('permissions.manage');
            $general = Item::create(__('General'))
            ->route('settings.general')
            ->active("settings")
            ->permissions('settings.general');

        $authAndRegistration = Item::create(__('Auth & Registration'))
            ->route('settings.auth')
            ->active("settings/auth")
            ->permissions('settings.auth');

        $notifications = Item::create(__('Notifications'))
            ->route('settings.notifications')
            ->active("settings/notifications")
            ->permissions(function (User $user) {
                return $user->hasPermission('settings.notifications');
            });

        $announcements = Item::create(__('Announcement'))
            ->route('announcements.index')
            ->active("announcements")
            ->permissions('announcements.manage');
        
        $userActivity = Item::create(__('Activity Logs'))
            ->route('activity.index')
            ->active("activity")
            ->permissions('users.activity');

        return Item::create(__('Systems'))
            ->href('#roles-dropdown')
            ->icon('fas fa-cogs')
            ->permissions(['systems.manage'])
            ->addChildren([
                $roles,
                $permissions,
                $users,
                $general,
                $authAndRegistration,
                $notifications,
                $announcements,
                $userActivity
            ]);
    }
}
