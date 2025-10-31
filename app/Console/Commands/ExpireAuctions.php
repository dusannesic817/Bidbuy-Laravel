<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Auction;

class ExpireAuctions extends Command
{
    protected $signature = 'auctions:expire';
    protected $description = 'Set status to 0 for expired auctions';

    public function handle(): void
    {
        $count = Auction::where('expiry_time', '<', now())
                        ->where('status', '!=', 0)
                        ->update(['status' => 0]);

        $this->info("Expired $count auctions.");
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->everyMinute();
    }
}