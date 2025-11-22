<x-mail::message>
# Auction Expired

Dear {{ $ownerName }},

Your auction **{{ $auction->name }}** has expired  
on **{{ $auction->expiry_time->format('d.m.Y H:i') }}**.

The auction status has been set to **closed**.

<x-mail::button :url="url('/auctions/'.$auction->id)">
View Auction
</x-mail::button>

Thanks for using our platform!  
<br>
{{ config('app.name') }}
</x-mail::message>