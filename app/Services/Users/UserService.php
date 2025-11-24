<?php

namespace Vanguard\Services\Users;

use Vanguard\User;
use Vanguard\Services\ModelService;
use Illuminate\Support\Facades\DB;
use Vanguard\Role;

class UserService extends ModelService
{
    public function __construct()
    {
        $this->model = resolve(User::class);
    }

    public function getAll() {
        return $this->model->all();
    }

    public function getUsersByRoleId($roleId)
    {
        return $this->model->whereHas('roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
            $query->where('status', 'active');
        })->get();
    }

    public function getUserSocialLogins(int $userId): \Illuminate\Support\Collection
    {
        return DB::table('social_logins')
            ->where('user_id', $userId)
            ->get();
    }
    public function findUserById($id)
    {
        return $this->model->find($id);
    }

    public function getActiveSellersCount()
    {
        return $this->model->where('role_id', Role::ROLE_SELLER)
            ->where('status', 'active')
            ->count();
    }

    public function getUserReportFF($user_id){
        $userFulfills = $this->model->select('id')
            ->whereRaw("FIND_IN_SET(?, fulfiller_id)", [$user_id])
            ->where('role_id', Role::ROLE_SELLER)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();
        return $userFulfills;
    }
}
