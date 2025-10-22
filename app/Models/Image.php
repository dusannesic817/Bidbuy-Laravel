<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    
    protected $fillable = ['auciton_id','img_path'];


    public function auction(): BelongsTo{
        return $this->belongsTo(Auction::class, 'auction_id');
    }

}
