{{-- === CHAT SUPPORT === --}}
@auth
@php
    \Carbon\Carbon::setLocale('id');
    $receiverId = 5; // ID admin
    $userId = auth()->id();

    $messages = \App\Models\Message::where(function ($query) use ($userId, $receiverId) {
        $query->where('sender_id', $userId)->where('receiver_id', $receiverId)
              ->orWhere(function ($q) use ($userId, $receiverId) {
                  $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
              });
    })->orderBy('created_at')->get();
@endphp

<!-- Tombol Chat -->
<button onclick="toggleChat()" id="chatButton"
    class="fixed bottom-6 right-6 bg-black text-white rounded-full p-4 shadow-lg z-50 hover:bg-gray-800 transition">
    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 3C6.48 3 2 6.92 2 11.5c0 2.43 1.4 4.6 3.58 6.1L4 21l4.17-1.67
                 c1.03.3 2.13.47 3.33.47 5.52 0 10-3.92 10-8.5S17.52 3 12 3z"/>
    </svg>
</button>

<!-- Data Chat -->
<div id="chatData" data-user-id="{{ $userId }}" data-receiver-id="{{ $receiverId }}"></div>

<!-- Popup Chat -->
<div id="chatPopup"
    class="fixed bottom-24 right-6 bg-white w-80 rounded-xl shadow-lg border border-gray-200 hidden z-50 flex flex-col max-h-[28rem]">
    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 rounded-t-xl">
        <h3 class="font-semibold text-gray-700">Chat Toko Gorden</h3>
        <button onclick="toggleChat()" class="text-gray-500 hover:text-red-500 text-xl leading-none">&times;</button>
    </div>
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-2 text-sm text-gray-700">
        @foreach ($messages as $message)
            <div class="{{ $message->sender_id == $userId ? 'bg-blue-100 self-end ml-auto' : 'bg-gray-100' }} rounded-lg p-2 w-max">
                {{ $message->message }}
            </div>
        @endforeach
    </div>
    <form onsubmit="sendMessage(event)" class="border-t px-4 py-2 flex gap-2">
        <input type="text" id="chatInput" placeholder="Tulis pesan..." class="flex-1 border rounded px-3 py-1 text-sm focus:outline-none" required>
        <button type="submit" class="text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
        </button>
    </form>
</div>

<!-- Script Chat -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chatData = document.getElementById('chatData');
        const userId = parseInt(chatData.dataset.userId);
        const receiverId = parseInt(chatData.dataset.receiverId);
        const chatBox = document.getElementById('chatMessages');

        window.toggleChat = function () {
            const popup = document.getElementById('chatPopup');
            popup.classList.toggle('hidden');
            if (!popup.classList.contains('hidden')) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        window.sendMessage = function (event) {
            event.preventDefault();
            const input = document.getElementById('chatInput');
            const msg = input.value.trim();
            if (!msg) return;

            const myMsgEl = document.createElement('div');
            myMsgEl.className = 'bg-blue-100 rounded-lg p-2 w-max self-end ml-auto';
            myMsgEl.textContent = msg;
            chatBox.appendChild(myMsgEl);
            chatBox.scrollTop = chatBox.scrollHeight;

            axios.post('/chat/send', {
                receiver_id: receiverId,
                message: msg
            }).then(() => {
                input.value = '';
            });
        }

        // === REALTIME CHAT DENGAN PUSHER ECHO ===
        if (window.Echo) {
            window.Echo.private(`chat.${userId}`)
                .listen('MessageSent', (e) => {
                    // Hanya tampilkan jika pengirim adalah admin
                    if (parseInt(e.message.sender_id) !== receiverId) return;

                    const msgEl = document.createElement('div');
                    msgEl.className = 'bg-gray-100 rounded-lg p-2 w-max';
                    msgEl.textContent = e.message.message;
                    chatBox.appendChild(msgEl);
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        } else {
            console.error('Laravel Echo tidak dimuat dengan benar');
        }
    });
</script>
@endauth
