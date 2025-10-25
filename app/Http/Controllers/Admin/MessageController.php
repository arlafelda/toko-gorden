<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    // Ambil pesan untuk chat tertentu (by chatId or user pair)
    public function index()
    {
        return view('Admin.ChatAdmin');
    }

    // Ambil daftar user yang pernah chat dengan admin
    public function getUsers()
{
    if (!auth('admin')->check()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $adminId = auth('admin')->id();

    $users = Message::where('receiver_id', $adminId)
        ->orWhere('sender_id', $adminId)
        ->with(['sender', 'receiver'])
        ->get()
        ->flatMap(function ($msg) use ($adminId) {
            $users = collect();
            if ($msg->sender && $msg->sender->id != $adminId) {
                $users->push($msg->sender);
            }
            if ($msg->receiver && $msg->receiver->id != $adminId) {
                $users->push($msg->receiver);
            }
            return $users;
        })
        ->unique('id')
        ->values();

    return response()->json(['users' => $users]);
}


    // Ambil pesan antara admin dan user tertentu
    public function getMessages($userId)
    {
        $adminId = auth('admin')->id();

        $messages = Message::where(function ($query) use ($adminId, $userId) {
            $query->where('sender_id', $adminId)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($adminId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $adminId);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    // Admin kirim pesan ke user
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $adminId = auth('admin')->id();

        $message = Message::create([
            'sender_id' => $adminId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Kirim broadcast
        event(new MessageSent($message));

        return response()->json(['success' => true, 'message' => $message]);
    }
}