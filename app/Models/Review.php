<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'reviewer_id',
        'mark'
    ];

     public function user(): BelongsTo{
        return $this->belongsTo(User::class,'user_id'); 
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function auction(): BelongsTo{
        return $this->belongsTo(Auction::class,'auction_id'); 
    }
}
