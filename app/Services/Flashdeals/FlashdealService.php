<?php

namespace Vanguard\Services\Flashdeals;

use Illuminate\Support\Facades\Auth;
use Vanguard\Models\Designs;
use Vanguard\Models\FlashDeals;
use Vanguard\Services\ModelService;

class FlashdealService extends ModelService
{
    public function __construct(private readonly ProductFlashdealService $productFlashdealService)
    {
        $this->model = resolve(FlashDeals::class);
    }

    public function panigate($filter, $store_id)
    {
        $query = $this->model->with('productflashdeal');
        if (isset($filter['statusfld']) && !empty($filter['statusfld'])) {
            $query->where('status_fld', $filter['statusfld']);
        }
        if (isset($filter['autorenew']) && !empty($filter['autorenew'])) {
            $query->where('auto', $filter['autorenew']);
        }
        if (isset($filter['renewed']) && !empty($filter['renewed'])) {
            $query->where('renew', $filter['renewed']);
        }
        $query->where('store_id', $store_id);
        $flashdeals = $query->orderBy('created_at', 'DESC')->paginate(20);
        return $flashdeals;
    }
}
