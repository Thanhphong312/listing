<?php

namespace Vanguard\Console\Commands;

use App\Services\SyncSchedule\SyncScheduleService;
use Illuminate\Console\Command;

class SyncFlashDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-flash-deals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new SyncScheduleService();
        
        $this->info('Bắt đầu đồng bộ FlashDeals...');
        
        $result = $service->syncScheduleFlashDeals();
        
        if ($result) {
            $this->info('Đồng bộ FlashDeals thành công!');
        } else {
            $this->error('Đồng bộ FlashDeals thất bại!');
        }
        
        return $result ? 0 : 1;
    }
}
