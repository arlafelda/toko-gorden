{{-- === CHAT SUPPORT REALTIME Pusher === --}}
@auth
@php
    $userId = auth()->id();
    $adminId = 1; // ID Admin default
@endphp

<!-- Tombol Chat -->
<button onclick="toggleChat()" id="chatButton"
    class="fixed bottom-6 right-6 bg-black text-white rounded-full p-4 shadow-lg z-50 hover:bg-gray-800 transition">
    💬
</button>

<!-- Popup Chat -->
<div id="chatPopup"
    class="fixed bottom-24 right-6 bg-white w-80 rounded-xl shadow-lg border border-gray-200 
           flex flex-col max-h-112 z-50 
           invisible opacity-0 pointer-events-none transition-all duration-200">

    <!-- Header -->
    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 rounded-t-xl">
        <h3 class="font-semibold text-gray-700">Chat Admin</h3>
        <button onclick="toggleChat()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
    </div>

    <!-- Box Pesan -->
    <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-2 text-sm text-gray-700"></div>

    <!-- Input -->
    <div class="border-t px-4 py-2 flex gap-2">
        <input type="text" id="messageInput" placeholder="Tulis pesan..."
               class="flex-1 border rounded px-3 py-1 text-sm focus:outline-none">
        <button id="sendBtn" class="text-blue-600 hover:text-blue-800">➤</button>
    </div>
</div>

<script>
    const userId = "{{ $userId }}";
    const adminId = "{{ $adminId }}";

    // === Toggle Popup Chat ===
    function toggleChat() {
        const popup = document.getElementById('chatPopup');

        if (popup.classList.contains('invisible')) {
            popup.classList.remove('invisible', 'opacity-0', 'pointer-events-none');
            popup.classList.add('opacity-100');
        } else {
            popup.classList.add('invisible', 'opacity-0', 'pointer-events-none');
            popup.classList.remove('opacity-100');
        }

        setTimeout(() => {
            let m = document.getElementById('messages');
            m.scrollTop = m.scrollHeight;
        }, 200);
    }

    // === Load pesan lama ===
    async function loadMessages() {
        const res = await fetch(`/chat/fetch?receiver_id=${adminId}`);
        const data = await res.json();

        const box = document.getElementById('messages');
        box.innerHTML = "";

        data.forEach(msg => {
            const div = document.createElement('div');
            div.className = msg.sender_type === 'user'
                ? "bg-blue-100 rounded-lg p-2 w-max ml-auto"
                : "bg-gray-200 rounded-lg p-2 w-max";

            div.textContent = msg.message;
            box.appendChild(div);
        });

        box.scrollTop = box.scrollHeight;
    }

    loadMessages();

    // === Kirim pesan ===
    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageInput').addEventListener('keypress', e => {
        if (e.key === 'Enter') sendMessage();
    });

    async function sendMessage() {
        let text = document.getElementById('messageInput').value.trim();
        if (!text) return;

        await fetch('/chat/send', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                receiver_id: adminId,
                receiver_type: "admin",
                message: text
            })
        });

        document.getElementById('messageInput').value = "";
        loadMessages();
    }

    // === Realtime Listener ===
    window.Echo.private(`chat.${userId}.user`)
        .listen("MessageSent", () => {
            loadMessages();
        });
</script>

@endauth
