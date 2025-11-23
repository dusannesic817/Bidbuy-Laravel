<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function show(string $id)
    {
        $user = User::with(['reviews'])->findOrFail($id);
        return new UserResource($user);
    }

    public function userAuctions(string $id){
       $user= User::with(['activeAuctions','reviews'])->findOrFail($id);
       return new UserResource($user);
    }

}
