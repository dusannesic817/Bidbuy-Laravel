<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Notifications\AuctionActionNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuctionExpiredMail;

class ExpireAuctions extends Command
{
    
    protected $signature = 'auctions:expire';
    protected $description = 'Set status to 0 for expired auctions and notify owners';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
       
        $expiredAuctions = Auction::where('expiry_time', '<', now())
                                  ->where('status', '!=', 0)
                                  ->get();

        foreach ($expiredAuctions as $auction) {
            $auction->user->notify(new AuctionActionNotification($auction, null, 'expired'));
            $auction->update(['status' => 0]);
            Mail::to($auction->user->email)->send(new AuctionExpiredMail($auction));

        }

        $this->info("Expired " . count($expiredAuctions) . " auctions and notified owners.");
    }

   
    public function schedule($schedule): void
    {
        $schedule->command('auctions:expire')->everyMinute();
    }
}
