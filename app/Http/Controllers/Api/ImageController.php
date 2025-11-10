<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    

    public function store(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        
        if($auction->user_id !==  Auth::id()) {
           return $this->errorMessage('You are not authorized to add images to this auction', 403);
        }

        $validated=$request->validate([
            'images' => ['required', 'array', 'max:6'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        if ($auction->images()->count() + count($validated['images']) > 6) {
            return $this->errorMessage('Maximum 6 images allowed per auction.', 422);
        }
        
        foreach($validated['images'] as $image) {
            $img_name = 'auction-'.$auction->id.'-'.time().rand(1,1000).'.'.$image->extension();
            $imagePath = $image->storeAs('auction_images', $img_name, 'public');

            $image=Image::create(attributes: [
                'auction_id' => $auction->id,
                'img_path'  => $imagePath,
            ]);
        }

        return $this->successMessage('Images uploaded successfully', $image=[], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = Image::findOrFail($id);
        
        $auction = $image->auction;
        if($auction->user_id !==  Auth::id()) {
           return $this->errorMessage('You are not authorized to delete images from this auction', 403);
        }

        if (Storage::disk('public')->exists($image->img_path)) {
            Storage::disk('public')->delete($image->img_path);
        }
        $image->delete();

        return $this->successMessage('Image deleted successfully');
    }

    public function destroyAll(Auction $auction)
    {
        if ($auction->user_id !== Auth::id()) {
            return $this->errorMessage('You are not authorized to delete images from this auction', 403);
        }

        $images = $auction->images;

        foreach ($images as $image) {
           if (Storage::disk('public')->exists($image->img_path)) {
                Storage::disk('public')->delete($image->img_path);
            }
            
            $image->delete();
        }

        return $this->successMessage('All images deleted successfully');
    }
}
