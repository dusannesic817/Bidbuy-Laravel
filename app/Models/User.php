<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'username',
        'address',
        'number',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function auctions()
    {
        return $this->hasMany(Auction::class, 'user_id'); 
    }
    public function expiredAuctions() {
        return $this->hasMany(Auction::class)->where('status', 0);
    }

    public function offers(): HasMany{
        return $this->hasMany(Offer::class);
    }

    public function reviews(): HasMany{
        return $this->hasMany(Review::class);
    }


    public function followedAuctions(): BelongsToMany {
        return $this->belongsToMany(
            Auction::class,
            'action_user_follows',
            'user_id',
            'auction_id'
        )->withTimestamps();
    }

    public function activeAuctions(){
        return $this->hasMany(Auction::class)
            ->where('expiry_time', '>', now())
            ->where("status" ,"=", "1");
    }

}
