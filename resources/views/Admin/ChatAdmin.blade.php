<!-- resources/views/Admin/ChatAdmin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Obrolan - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" data-admin-id="{{ auth('admin')->id() }}">

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r shadow-sm">
      <div class="h-16 flex items-center justify-center border-b">
        <h1 class="text-lg font-bold text-gray-800">Admin Panel</h1>
      </div>
      <nav class="p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">dashboard</span>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">inventory_2</span>
          <span>Produk</span>
        </a>
        <a href="{{ route('pesananAdmin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">receipt</span>
          <span>Pesanan</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">groups</span>
          <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-gray-100">
          <span class="material-icons">bar_chart</span>
          <span>Laporan</span>
        </a>
        <a href="{{ route('chat.admin') }}" class="flex items-center gap-3 px-3 py-2 rounded-full bg-gray-100">
          <span class="material-icons">chat</span>
          <span>Obrolan</span>
        </a>
        <a href="{{ route('admin.trafik') }}" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4]">
        <span class="material-icons">analytics</span>
        <span class="text-sm font-medium">Trafik</span>
      </a>
      <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-full hover:bg-[#f0f2f4] w-full text-left">
          <span class="material-icons">logout</span>
          <span class="text-sm font-medium">Logout</span>
        </button>
      </form>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      <!-- Header -->
      <header class="h-16 bg-white border-b px-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Obrolan</h2>
      </header>

      <!-- Chat Section -->
      <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar Chat List -->
        <div class="w-80 bg-white border-r overflow-y-auto flex flex-col">
          <div class="p-4 border-b">
            <input
              type="text"
              id="searchUserInput"
              placeholder="Cari pengguna..."
              class="w-full px-3 py-2 border rounded-full focus:ring focus:ring-blue-200"
              oninput="filterUsers()"
            />
          </div>
          <div id="userList" class="flex-1 overflow-y-auto">
            <!-- User list will be loaded here -->
          </div>
        </div>

        <!-- Chat Main Panel -->
        <div class="flex-1 flex flex-col bg-gray-50">
          <div id="chatMessages" class="flex-1 p-6 space-y-4 overflow-y-auto">
            <p class="text-gray-500 text-center">Pilih pengguna untuk memulai obrolan</p>
          </div>
          <div class="p-4 border-t bg-white">
            <div class="flex items-center gap-2">
              <input type="text" id="chatInput" placeholder="Ketik pesan..." class="flex-1 px-4 py-2 border rounded-full focus:ring focus:ring-blue-200" />
              <button id="sendBtn" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700" disabled>Kirim</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

 <script>
let selectedUserId = null;
const adminId = document.body.dataset.adminId;
let allUsers = [];

// === LOAD USER LIST ===
function loadUsers() {
    axios.get('/admin/chat/users')
        .then(res => {
            allUsers = res.data.users;
            renderUserList(allUsers);
        });
}

function renderUserList(users) {
    const userList = document.getElementById('userList');
    userList.innerHTML = '';

    users.forEach(user => {
        const el = document.createElement('div');
        el.className = 'p-4 hover:bg-gray-100 cursor-pointer';
        el.innerHTML = `<h3 class="text-sm font-semibold">${user.name}</h3>`;
        el.onclick = () => selectUser(user.id);
        userList.appendChild(el);
    });
}

function selectUser(userId) {
    selectedUserId = userId;

    axios.get(`/admin/chat/messages/${userId}`)
        .then(res => {
            const messages = res.data;
            const box = document.getElementById('chatMessages');
            box.innerHTML = "";

            messages.forEach(msg => {
                const div = document.createElement('div');
                const isAdmin = msg.sender_type === 'admin';

                div.className = isAdmin
                    ? "flex justify-end"
                    : "flex justify-start";

                div.innerHTML = `
                    <div class="${isAdmin ? 'bg-blue-100' : 'bg-gray-200'} rounded-xl px-4 py-2 max-w-lg">
                        ${msg.message}
                    </div>
                `;

                box.appendChild(div);
            });

            box.scrollTop = box.scrollHeight;
        });
}

// === SEND MESSAGE ===
document.getElementById('sendBtn').addEventListener('click', () => {
    const msg = document.getElementById('chatInput').value.trim();
    if (!msg || !selectedUserId) return;

    axios.post('/admin/chat/send', {
        receiver_id: selectedUserId,
        receiver_type: "user",
        message: msg
    });

    document.getElementById('chatInput').value = "";
    selectUser(selectedUserId);
});

// === LISTEN REALTIME ADMIN CHANNEL ===
window.Echo.private(`chat.${adminId}.admin`)
    .listen("MessageSent", (e) => {
        if (selectedUserId == e.message.sender_id) {
            selectUser(selectedUserId);
        }
        loadUsers();
    });

document.addEventListener('DOMContentLoaded', loadUsers);
</script>


</body>
</html>