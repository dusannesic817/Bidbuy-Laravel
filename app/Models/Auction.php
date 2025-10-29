<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'short_description',
        'description',
        'started_price',
        'condition',
        'expiry_time',
        'status',

    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class, 'category_id');
    }

     public function images(): HasMany{
        return $this->hasMany(Image::class);
    }

    public function views(): HasMany{
        return $this->hasMany(View::class);
    }

    public function offers(): HasMany{
        return $this->hasMany(Offer::class);
    }

    public function followers() {
        return $this->belongsToMany(
            User::class,
            'action_user_follows',
            'auction_id',
            'user_id'
        )->withTimestamps();
    }


    

    public function highestOffer()
    {
        return $this->hasOne(Offer::class)
                    ->orderByDesc('price'); 
    }

}
     

