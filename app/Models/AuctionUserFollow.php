<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Routing\Loader\ProtectedPhpFileLoader;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionUserFollow extends Model
{
    protected $filable=['auction_id', 'user_id'];

    public function auction(): BelongsTo {
        return $this->belongsTo(Auction::class,'auction_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }

}
