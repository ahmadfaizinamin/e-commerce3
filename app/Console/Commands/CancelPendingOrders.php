<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:cancel-pending-orders')]
#[Description('Cancel expired orders')]
class CancelPendingOrders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(OrderService $orderService)
    {
        Log::channel('toko')->info('[CRON] Mengecek pesanan expired');

        $timeLimit = Carbon::now()->subMinute();

        $totalCancel = $orderService->cancelPendingOrders($timeLimit);
        
        $this->info("Berhasil cancel {$totalCancel} pesanan");
        Log::channel('toko')->warning("[SYSTEM] Total pesanan ter-cancel: {$totalCancel}");
    }
}
