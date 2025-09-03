<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    // Kirim pesan
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => true]);
    }

    // Ambil semua pesan antara user login dan user lain
    public function getMessages($withUserId)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($q) use ($authId, $withUserId) {
            $q->where('sender_id', $authId)
              ->where('receiver_id', $withUserId);
        })->orWhere(function ($q) use ($authId, $withUserId) {
            $q->where('sender_id', $withUserId)
              ->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }
}
