<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Produk\ProductController;
use App\Http\Controllers\Produk\KeranjangController;
use App\Http\Controllers\Produk\MidtransController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPelangganController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Produk\ReviewController;
use App\Http\Controllers\User\UserOrderController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AdminChatController;


Route::get('/tentang_kami', function () {
    return view('User.TentangKami');
})->name('tentangkami');


// ----------------------
//        USER
// ----------------------

// Login & Register
Route::get('/', [UserController::class, 'landingPage'])->name('landing');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth-google-callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');

// Protected user pages
Route::middleware('auth')->group(function () {

    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
     Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
});

Route::get('/produk-user', [ProductController::class, 'showUserProducts'])->name('produk.user');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::middleware(['auth'])->group(function () {
    Route::post('/keranjang/tambah/{productId}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::get('/keranjang', [KeranjangController::class, 'tampil'])->name('keranjang.tampil');
    Route::delete('/keranjang/hapus/{itemId}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [MidtransController::class, 'index'])->name('transaksi'); });
    Route::post('/midtrans/token', [MidtransController::class, 'getSnapToken'])->name('midtrans.token');
    Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');

Route::post('/ulasan', [ReviewController::class, 'store'])->name('ulasan.store')->middleware('auth');

Route::middleware(['auth'])->get('/pesanan', [UserOrderController::class, 'index'])->name('pesanan.user');


Route::middleware('auth')->group(function () {
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::get('/chat/messages/{withUserId}', [ChatController::class, 'getMessages']);
});



// ----------------------
//        ADMIN
// ----------------------


Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminAuthController::class, 'index'])->name('admin.dashboard');
});


Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');
});

Route::get('/admin/pesananAdmin', [AdminOrderController::class, 'index'])->middleware('auth:admin')->name('pesananAdmin');


Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/pendapatan-harian', [AdminLaporanController::class, 'pendapatanHarian'])->name('admin.laporan.pendapatanHarian');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/pelanggan', [AdminPelangganController::class, 'index'])->name('admin.pelanggan');
});

// hanya untuk development/testing
Route::post('/simulasi-callback', [MidtransController::class, 'callbackSimulasi']);


Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/chat', [AdminChatController::class, 'index'])->name('chat.admin');
    Route::get('/admin/chat/users', [AdminChatController::class, 'getUsers']);
    Route::get('/admin/chat/messages/{userId}', [AdminChatController::class, 'getMessages']);
    Route::post('/admin/chat/send', [AdminChatController::class, 'sendMessage']);
});

// BELI SEKARANG
Route::post('/produk/{product}/beli-langsung', [\App\Http\Controllers\Produk\ProductController::class, 'beliLangsung'])->name('produk.beliLangsung');
Route::get('/checkout/beli-langsung', [\App\Http\Controllers\Produk\ProductController::class, 'checkoutBeliLangsung'])->name('checkout.beliLangsung');
Route::post('/bayar-langsung', [\App\Http\Controllers\Produk\MidtransController::class, 'bayarLangsung'])->name('midtrans.bayarLangsung');
Route::post('/checkout/langsung/{product}', [\App\Http\Controllers\Produk\ProductController::class, 'checkoutLangsung'])->name('produk.checkoutLangsung');

