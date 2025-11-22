<x-mail::message>
# Offer Status Update

Dear {{ $bidderName }},

Your offer for auction **{{ $auctionTitle }}**  
in the amount of **{{ number_format($bidAmount, 2) }} RSD**

@if($status === 'Accepted')
has been <span style="color:green;">accepted 🎉</span>.

<x-mail::button :url="url('/auctions/'.$auctionId)">
View Auction
</x-mail::button>

Congratulations, you are the winning bidder!
@elseif($status === 'Rejected')
has been <span style="color:red;">rejected</span>.

<x-mail::button :url="url('/auctions/'.$auctionId)">
View Auction
</x-mail::button>

Thank you for participating, we hope to see you in future auctions!
@endif

---

Thanks for using our platform!  
<br>
{{ config('app.name') }}
</x-mail::message>