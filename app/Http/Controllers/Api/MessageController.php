<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

       
        if (!$chatRoom->users->contains($user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        
        $message = Message::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $user->id,
            'content' => $request->input('content')
        ]);

        $message->load('user');

        
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $message,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function myMessages(){

       return Auth::user()->chatRooms()->get();

    }

    public function show(ChatRoom $chatRoom)
    {
        $user = Auth::user();

        if (!$chatRoom->users->contains($user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $chatRoom->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
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
        //
    }
}
