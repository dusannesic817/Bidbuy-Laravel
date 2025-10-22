<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class View extends Model
{
    protected $fillable = ['auction_id','ip_address','user_agent'];


     public function category(): BelongsTo{
        return $this->belongsTo(Auction::class, 'auction_id');
    }
}
