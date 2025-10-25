
<?php if(auth()->guard()->check()): ?>
<?php
    $userId = auth()->id();
    $adminId = 5; // ID admin tetap
?>

<!-- Tombol Chat -->
<button onclick="toggleChat()" id="chatButton"
    class="fixed bottom-6 right-6 bg-black text-white rounded-full p-4 shadow-lg z-50 hover:bg-gray-800 transition">
    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 3C6.48 3 2 6.92 2 11.5c0 2.43 1.4 4.6 3.58 6.1L4 21l4.17-1.67
                 c1.03.3 2.13.47 3.33.47 5.52 0 10-3.92 10-8.5S17.52 3 12 3z"/>
    </svg>
</button>

<!-- Popup Chat -->
<div id="chatPopup"
    class="fixed bottom-24 right-6 bg-white w-80 rounded-xl shadow-lg border border-gray-200 hidden z-50 flex flex-col max-h-[28rem]">
    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 rounded-t-xl">
        <h3 class="font-semibold text-gray-700">Chat Toko Gorden</h3>
        <button onclick="toggleChat()" class="text-gray-500 hover:text-red-500 text-xl leading-none">&times;</button>
    </div>
    <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-2 text-sm text-gray-700"></div>
    <div class="border-t px-4 py-2 flex gap-2">
        <input type="text" id="messageInput" placeholder="Tulis pesan..." 
               class="flex-1 border rounded px-3 py-1 text-sm focus:outline-none">
        <button id="sendBtn" class="text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
        </button>
    </div>
</div>

<!-- Script Chat Stream -->
<script src="https://cdn.jsdelivr.net/npm/stream-chat@latest/dist/browser/index.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const userId = "<?php echo e($userId); ?>";
    const adminId = "<?php echo e($adminId); ?>";
    
    window.toggleChat = function() {
        const popup = document.getElementById('chatPopup');
        popup.classList.toggle('hidden');
        if (!popup.classList.contains('hidden')) {
            document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
        }
    }

    // Ambil token dari server
    const tokenRes = await fetch(`/api/stream/token?user_id=${userId}&user_type=user`);
    const { token } = await tokenRes.json();

    // Inisialisasi Stream Chat
    const client = StreamChat.getInstance("<?php echo e(config('services.stream.key')); ?>");
    await client.connectUser({ id: userId }, token);

    // Channel unik user-admin
    const channel = client.channel('messaging', `chat_${userId}_${adminId}`);
    await channel.watch();

    const messageList = document.getElementById('messages');
    const input = document.getElementById('messageInput');

    // Render pesan lama
    channel.state.messages.forEach(msg => {
        const div = document.createElement('div');
        div.className = msg.user.id == userId ? 'bg-blue-100 rounded-lg p-2 w-max self-end ml-auto' : 'bg-gray-100 rounded-lg p-2 w-max';
        div.textContent = msg.user.id + ": " + msg.text;
        messageList.appendChild(div);
    });

    // Event: pesan baru masuk
    channel.on('message.new', event => {
        const div = document.createElement('div');
        div.className = event.message.user.id == userId ? 'bg-blue-100 rounded-lg p-2 w-max self-end ml-auto' : 'bg-gray-100 rounded-lg p-2 w-max';
        div.textContent = event.message.user.id + ": " + event.message.text;
        messageList.appendChild(div);
        messageList.scrollTop = messageList.scrollHeight;
    });

    // Kirim pesan
    document.getElementById('sendBtn').addEventListener('click', async () => {
        const text = input.value.trim();
        if (!text) return;
        await channel.sendMessage({ text });
        input.value = '';
    });

    input.addEventListener('keypress', async (e) => {
        if (e.key === 'Enter') document.getElementById('sendBtn').click();
    });
});
</script>
<?php endif; ?>
<?php /**PATH D:\Website\website-gorden\resources\views/layouts/Chat.blade.php ENDPATH**/ ?>