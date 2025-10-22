<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;
    
    protected $fillable = ['auction_id','user_id','price'];



    public function auction(): BelongsTo{
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class,'user_id'); 
    }
}
