<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // ================= USER KIRIM =================
    public function sendUser(Request $req)
    {
        $message = Message::create([
            'sender_id'     => auth::id(), // user
            'sender_type'   => 'user',
            'receiver_id'   => $req->receiver_id,
            'receiver_type' => 'admin',
            'message'       => $req->message
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }

    // ================= ADMIN KIRIM =================
    public function sendAdmin(Request $req)
    {
        $message = Message::create([
            'sender_id'     => auth()->guard('admin')->id(),
            'sender_type'   => 'admin',
            'receiver_id'   => $req->receiver_id,
            'receiver_type' => 'user',
            'message'       => $req->message
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }


    // ================= USER FETCH CHAT =================
    public function fetch(Request $req)
    {
        return Message::where(function ($q) use ($req) {
            $q->where('sender_id', auth::id())
              ->where('receiver_id', $req->receiver_id);
        })->orWhere(function ($q) use ($req) {
            $q->where('sender_id', $req->receiver_id)
              ->where('receiver_id', auth::id());
        })
        ->orderBy('id', 'ASC')
        ->get();
    }



    // ================= ADMIN CHAT PAGE =================
    public function adminChatPage()
    {
        return view('Admin.ChatAdmin');
    }

    // List semua user yang pernah chat
    public function adminUsers()
    {
        return User::select('id', 'name')
                ->orderBy('name')
                ->get();
    }

    // Admin ambil pesan user tertentu
    public function adminFetchMessages($userId)
    {
        return Message::where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->where('receiver_id', auth()->guard('admin')->id());
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', auth()->guard('admin')->id())
              ->where('receiver_id', $userId);
        })
        ->orderBy('id', 'ASC')
        ->get();
    }
}
