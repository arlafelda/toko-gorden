import { StreamChat } from 'stream-chat';

document.addEventListener('DOMContentLoaded', async () => {
  const apiKey = "{{ config('services.stream.key') }}";
  const userId = "{{ auth()->check() ? auth()->id() : '' }}";
  const tokenResponse = await fetch(`/api/stream/token?user_id=${userId}&user_type=user`);
  const { token } = await tokenResponse.json();

  const client = StreamChat.getInstance(apiKey);
  await client.connectUser({ id: userId }, token);

  const channel = client.channel('messaging', 'chat_{{ $chat->user_id }}_{{ $chat->admin_id }}');
  await channel.watch();

  const messageList = document.getElementById('messages');
  const input = document.getElementById('messageInput');

  // Render pesan lama
  channel.state.messages.forEach(msg => {
    const div = document.createElement('div');
    div.textContent = `${msg.user.id}: ${msg.text}`;
    messageList.appendChild(div);
  });

  // Event: pesan baru masuk real-time
  channel.on('message.new', event => {
    const div = document.createElement('div');
    div.textContent = `${event.user.id}: ${event.message.text}`;
    messageList.appendChild(div);
  });

  // Kirim pesan
  document.getElementById('sendBtn').addEventListener('click', async () => {
    const text = input.value.trim();
    if (text) {
      await channel.sendMessage({ text });
      input.value = '';
    }
  });
});
