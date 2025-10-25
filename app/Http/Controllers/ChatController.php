<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GetStream\StreamChat\Client as StreamClient;

class ChatController extends Controller
{
    protected StreamClient $client;

    public function __construct()
    {
        $this->client = new StreamClient(
            config('services.stream.key'),
            config('services.stream.secret')
        );
    }

    // API: buat channel di Stream + simpan di DB
    public function createChat(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_id' => 'required|exists:admins,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $admin = Admin::findOrFail($request->admin_id);
        $channelId = "chat_{$user->id}_{$admin->id}";

        try {
            $channel = $this->client->channel('messaging', $channelId, [
                'members' => [(string)$user->id, "admin-{$admin->id}"],
                'created_by_id' => (string)$user->id,
            ]);
            $channel->create('');

            $chat = Chat::firstOrCreate(
                ['user_id' => $user->id, 'admin_id' => $admin->id],
                ['channel_id' => $channelId]
            );

            return response()->json(['success' => true, 'chat' => $chat]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // API: generate token untuk frontend Stream
    public function generateToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'user_type' => 'required|in:user,admin',
        ]);

        $userId = $request->user_type === 'admin' ? 'admin-' . $request->user_id : (string)$request->user_id;

        try {
            $token = $this->client->createToken($userId);
            return response()->json([
                'success' => true,
                'api_key' => config('services.stream.key'),
                'user_id' => $userId,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // View user: chat page with adminId
    public function userChatView($adminId)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $chat = Chat::firstOrCreate(
            ['user_id' => $user->id, 'admin_id' => $adminId],
            ['channel_id' => "chat_{$user->id}_{$adminId}"]
        );
        $admin = $chat->admin;

        // view minimal user page (see views below)
        return view('layouts.Chat', compact('chat', 'user', 'admin'));
    }

    // View admin: admin page (list + panel)
    public function adminChatView($userId)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login');

        $chat = Chat::firstOrCreate(
            ['user_id' => $userId, 'admin_id' => $admin->id],
            ['channel_id' => "chat_{$userId}_{$admin->id}"]
        );

        return view('admin.chat', compact('chat', 'admin'));
    }

    // Halaman admin list (no userId) — li  sting users/chats
    public function chatList()
    {
        $admin = Auth::guard('admin')->user();
        $users = User::select('id', 'name')->get();
        // generate token for admin to use in view
        $token = $this->client->createToken('admin-' . $admin->id);
        return view('admin.chatlist', compact('users', 'token', 'admin'));
    }

    // API: list users (json) untuk sidebar
    public function userList()
    {
        $users = User::select('id', 'name')->get();
        return response()->json(['users' => $users]);
    }
}
