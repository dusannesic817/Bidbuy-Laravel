<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class AuctionActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $auction,
        public $sender, 
        public string $type // 'bid', 'accepted', 'rejected'
    ) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast']; 
    }

    public function toArray($notifiable)
    {
        $message = match ($this->type) {
            'bid' => "{$this->sender->name} placed a bid on your auction: {$this->auction->name},{$this->auction->highestOffer->price} RSD",
            'accepted' => "Your offer on {$this->auction->name} was accepted by {$this->sender->name}, {$this->auction->highestOffer->price} RSD",
            'rejected' => "Your offer on {$this->auction->name} was rejected by {$this->sender->name}, {$this->auction->highestOffer->price} RSD",
            'expired' => "Your auction {$this->auction->name} has expired!",
            'followed' => "{$this->sender->name} is now following your auction: {$this->auction->name}",
            'review' => "{$this->sender->name} has left a review for you on the auction: {$this->auction->name}",
            default => "You have a new update regarding {$this->auction->title}",
        };

        return [
            'message' => $message,
            'auction_id' => $this->auction->id,
            'type' => $this->type,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

  /* public function toMail($notifiable)
    {
        $message = match ($this->type) {
            'bid' => "{$this->sender->name} placed a bid on your auction: {$this->auction->title}",
            'accepted' => "Your offer on {$this->auction->title} was accepted by {$this->sender->name}",
            'rejected' => "Your offer on {$this->auction->title} was rejected by {$this->sender->name}",
            default => "You have a new update regarding {$this->auction->title}",
        };

        return (new MailMessage)
            ->subject("Auction update: {$this->auction->title}")
            ->line($message)
            ->action('View Auction', url("/auctions/{$this->auction->id}"));
    }*/
}
