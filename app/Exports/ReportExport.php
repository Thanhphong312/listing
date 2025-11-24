<?php

namespace Vanguard\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Vanguard\Models\Order\Order;
use Illuminate\Support\Facades\Auth;

class ReportExport implements FromCollection
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        // dd(collect([$this->order->toArray()]));
        // Chuyển đối tượng Order thành một mảng và đặt vào trong một mảng để tạo một bộ sưu tập
        return collect($this->data);
    }
}
