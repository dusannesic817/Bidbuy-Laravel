<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    


    protected $fillable = ["name"];


    public function auctions(): HasMany{
        return $this->hasMany(Auction::class);
    }
    public function subcategories(): HasMany{
        return $this->hasMany(Subcategory::class);
    }

}
