<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    public function findOrCreate(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id|not_in:' . Auth::id(),
    ]);

   
    $otherId = $request->input('user_id');

    $room = ChatRoom::whereHas('users', fn($q) => $q->where('id', Auth::id()))
        ->whereHas('users', fn($q) => $q->where('id', $otherId))
        ->withCount('users')
        ->having('users_count', 2)
        ->first();

    if (!$room) {
        $room = ChatRoom::create();
        $room->users()->attach([Auth::id(), $otherId]);
    }

    return response()->json([
        'room_id' => $room->id,
        'message' => 'Chat room ready.',
    ]);
}

}
