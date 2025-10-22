<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class View extends Model
{
    use HasFactory;
    protected $fillable = ['auction_id','ip_address','user_agent'];


     public function category(): BelongsTo{
        return $this->belongsTo(Auction::class, 'auction_id');
    }
}
