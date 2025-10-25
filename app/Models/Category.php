<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Category extends Model
{
     use HasFactory;


    protected $fillable = ["name,parent_id"];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Ako je ova kategorija roditelj, ovo su njene subkategorije
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function auctions(): HasMany{
        return $this->hasMany(Auction::class);
    }
    
}
