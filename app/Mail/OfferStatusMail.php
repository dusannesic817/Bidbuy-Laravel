<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Offer;

class OfferStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $status;
    public $offer;
    public $auctionTitle;
    public $bidderName;
    public $bidAmount;
    public $auctionId;

   public function __construct(Offer $offer, string $status)
{
    $this->offer = $offer;
    $this->status = $status;
    $this->bidderName = $offer->user->name;
    $this->auctionTitle = $offer->auction->name;
    $this->bidAmount = $offer->price;
    $this->auctionId = $offer->auction_id;
}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Offer Status Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.offer-status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
