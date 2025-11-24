<?php

namespace App\Services\Tiktok;

use App\Repositories\Email\Tiktik;
use App\Repositories\Tiktok\TiktokAppRepository;

class TiktokAppService
{
    protected $tiktokappRepository;

    public function __construct(TiktokAppRepository $tiktokappRepository)
    {
        $this->tiktokappRepository = $tiktokappRepository;
    }

    public function getTiktokApps(array $filter = [], $orderBy = null, $perPage = 10, $status = null)
    {
        return $this->tiktokappRepository->paginate($perPage, $filter, $orderBy, $status);
    }
    public function getTiktokProxys(array $filter = [], $orderBy = null, $perPage = 10, $status = null)
    {
        return $this->tiktokappRepository->paginateTiktokProxy($perPage, $filter, $orderBy, $status);
    }

    public function getTiktokApp($tiktokapp)
    {
        return $this->tiktokappRepository->find($tiktokapp);

    }

    public function createTiktokApp(array $data)
    {
        return $this->tiktokappRepository->create($data);
    }
    public function createTiktokProxy(array $data)
    {
        return $this->tiktokappRepository->createTiktokProxy($data);
    }


    public function updateTiktokApp($email, array $data)
    {
        return $this->tiktokappRepository->update($email, $data);
    }

    public function deleteTiktokApp($email)
    {
        return $this->tiktokappRepository->delete($email);
    }
}
