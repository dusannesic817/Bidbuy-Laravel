<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return Category::with('children')->get();
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('auctions.highestOffer')->findOrFail($id);
        return new CategoryResource($category);
    }

    public function subcategory(string $id){
        $category = Category::with('children.auctions.highestOffer')->findOrFail($id);
        return new CategoryResource($category);
    }

}
