<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Exports\ReportExport;
use Vanguard\Models\Order\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ExportReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $role;
    private $userId;
    private $username;

    public function __construct($request, $role, $userId, $username)
    {
        $this->request = $request;
        $this->role = $role;
        $this->userId = $userId;
        $this->username = $username;
    }

    public function handle(): void
    {
        $created = Carbon::now()->subDays(30)->startOfDay();

        $query = Order::with('items')
            ->select('id', 'ref_id', 'seller_id', 'store_id', 'fulfill_status', 'total_cost', 'created_at')
            ->whereNotIn('fulfill_status', ['test_order', 'cancelled']);

        if ($this->role === "Seller") {
            $query->where('seller_id', $this->userId);
        }

        $this->applyFilters($query, $this->request);

        $dateRange = $this->getDateRange($this->request['datefrom'] ?? null, $this->request['dateto'] ?? null);
        $datefrom = Carbon::parse($dateRange[0])->format('Y_m_d');
        $dateto = Carbon::parse($dateRange[1])->format('Y_m_d');

        \Log::info('Start Export Report');

        $totalAmount = $query->sum('total_cost');
        $totalOrder = $query->count();

        \Log::info("Total Order: $totalOrder");
        \Log::info("Total Amount: $totalAmount");

        $orders = $query->orderBy('created_at', 'ASC')->get();
        $data = $this->prepareData($orders, $totalOrder, $totalAmount);

        $fileName = $this->generateFileName($datefrom, $dateto);
        $filePath = storage_path("app/$fileName");

        \Log::info("Start exporting report to $fileName");
        Excel::store(new ReportExport($data), $fileName);

        $reportUrl = $this->storeReportAndGetUrl($fileName);

        \Log::info("$datefrom - $dateto Exported report to $reportUrl");
        \Log::info('Export Report Complete');

        $message = "{$this->username} report order from $datefrom - to $dateto. link: $reportUrl";
        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHAT_ID_REPORTS'),
            'text' => $message,
        ]);
    }

    private function applyFilters($query, $request): void
    {
        if (!empty($request['nameSearch'])) {
            $query->where(function ($query) use ($request) {
                $query->where('id', $request['nameSearch'])
                    ->orWhere('ref_id', $request['nameSearch']);
            });
        }

        if (!empty($request['fillterstore'])) {
            $query->where('store_id', $request['fillterstore']);
        }

        if (!empty($request['fillteruser'])) {
            $query->where('seller_id', $request['fillteruser']);
        }

        if (!empty($request['datefrom'])) {
            $dateRange = $this->getDateRange($request['datefrom'], $request['dateto']);
            $query->whereBetween('created_at', $dateRange);
        }
    }

    private function getDateRange($dateFrom, $dateTo): array
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = $dateTo ? Carbon::parse($dateTo)->endOfDay() : $startDate->endOfDay();

        return [$startDate->toDateTimeString(), $endDate->toDateTimeString()];
    }

    private function prepareData($orders, $totalOrder, $totalAmount): array
    {
        $data = [
            ['Total order', $totalOrder, 'Total amount', $totalAmount],
            ['Id', 'Ref', 'Seller', 'Store', 'Fulfill Status', 'Total Item', 'Total Cost', 'Create At']
        ];

        foreach ($orders as $order) {
            $data[] = [
                $order->id,
                $order->ref_id,
                getSellerNameById($order->seller_id),
                getstorebyid($order->store_id),
                $order->fulfill_status,
                $order->items->count(),
                $order->total_cost,
                $order->created_at->toDateTimeString()
            ];
        }

        return $data;
    }

    private function generateFileName($datefrom, $dateto): string
    {
        if(isset($this->request['fillterstore'])&&isset($this->request['fillteruser'])){
            return $this->username . '_report_from_'.$datefrom.'_to_'.$dateto.'_store_'.getstorebyid($this->request['fillterstore']).'_user_'.getUsernameById($this->request['fillteruser']).'_date_'. now()->format('Y_m_d_H') . '.xlsx'; 
        }else{
            if(isset($this->request['fillterstore'])){
                return $this->username . '_report_from_'.$datefrom.'_to_'.$dateto.'_store_'.getstorebyid($this->request['fillterstore']).'_date_'. now()->format('Y_m_d_H') . '.xlsx'; 
            }
            if(isset($this->request['fillteruser'])){
                return $this->username . '_report_from_'.$datefrom.'_to_'.$dateto.'_user_'.getUsernameById($this->request['fillteruser']).'_date_'. now()->format('Y_m_d_H') . '.xlsx'; 
            }
        }
    }

    private function storeReportAndGetUrl($fileName): string
    {
        $filePath = storage_path("app/$fileName");
        $fileUrlPath = "/reports/$fileName";

        Storage::disk('b2')->put($fileUrlPath, file_get_contents($filePath), 'public');
        return "https://zipimgs.com/file/pressifypod$fileUrlPath";
    }
}
