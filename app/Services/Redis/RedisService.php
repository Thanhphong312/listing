<?php

namespace Vanguard\Services\Redis;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    protected $redisKeyPrefix;

    public function __construct($prefix)
    {
        $this->redisKeyPrefix = $prefix;
    }

    // Tạo mới dữ liệu vào Redis
    public function createRedis($id, $data)
    {
        $redisKey = $this->redisKeyPrefix . $id;
        Redis::set($redisKey, json_encode($data));
    }

    // Cập nhật dữ liệu trong Redis
    public function updateRedis($id, $data)
    {
        $redisKey = $this->redisKeyPrefix . $id;
        if (Redis::exists($redisKey)) {
            Redis::set($redisKey, json_encode($data));
        } else {
            throw new \Exception("Key does not exist in Redis.");
        }
    }

    // Xóa dữ liệu trong Redis
    public function deleteRedis($id)
    {
        $redisKey = $this->redisKeyPrefix . $id;
        Redis::del($redisKey);
    }

    // Lấy dữ liệu từ Redis
    public function getRedis($id)
    {
        $redisKey = $this->redisKeyPrefix . $id;
        $data = Redis::get($redisKey);

        return $data ? json_decode($data, true) : null;
    }
}
