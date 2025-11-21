<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Broadcast;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPelangganController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminTrafikController;
use App\Http\Controllers\Produk\ProductController;
use App\Http\Controllers\Produk\KeranjangController;
use App\Http\Controllers\Produk\MidtransController;
use App\Http\Controllers\Produk\ReviewController;
use App\Http\Controllers\ChatController;
use App\Models\Message;

// ----------------------
//      HALAMAN UMUM
// ----------------------

Route::get('/tentang_kami', function () {
    return view('User.TentangKami');
})->name('tentangkami');

Route::get('/admin/detailPesanan/{id}', [AdminOrderController::class, 'show'])
    ->middleware('auth:admin')
    ->name('admin.detailPesanan');
Route::post('/admin/detailPesanan/{id}/update-status', [AdminOrderController::class, 'updateStatus'])
    ->middleware('auth:admin')
    ->name('admin.updateStatus');
Route::post('/admin/detailPesanan/{id}/add-to-report', [AdminOrderController::class, 'addToReport'])
    ->middleware('auth:admin')
    ->name('admin.addToReport');


// ----------------------
//         USER
// ----------------------

// Landing page
Route::get('/', [UserController::class, 'landingPage'])->name('landing');

// Login & Register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth-google-callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Lupa password
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');

// Profile user (harus login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
});

// Produk dan keranjang
Route::get('/produk-user', [ProductController::class, 'showUserProducts'])->name('produk.user');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('product.detail');

Route::middleware(['auth'])->group(function () {
    Route::post('/keranjang/tambah/{productId}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::get('/keranjang', [KeranjangController::class, 'tampil'])->name('keranjang.tampil');
    Route::delete('/keranjang/hapus/{itemId}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
});

// Checkout & Midtrans (sudah diperbaiki)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [MidtransController::class, 'index'])->name('transaksi');
    Route::post('/midtrans/token', [MidtransController::class, 'getSnapToken'])->name('midtrans.token');
    Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');
});

// Ulasan
Route::post('/ulasan', [ReviewController::class, 'store'])->name('ulasan.store')->middleware('auth');

// Pesanan user
Route::middleware(['auth'])->get('/pesanan', [UserOrderController::class, 'index'])->name('pesanan.user');

// ----------------------
//         ADMIN
// ----------------------

// Login admin
Route::prefix('admin')->name('admin.')->group(function () {

    // Login admin
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'index'])->name('dashboard');
    });

});


// CRUD Produk
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');
});

// Pesanan admin
Route::get('/admin/pesananAdmin', [AdminOrderController::class, 'index'])
    ->middleware('auth:admin')
    ->name('pesananAdmin');

// Trafik admin
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/trafik', [AdminTrafikController::class, 'index'])->name('admin.trafik');
    Route::get('/trafik/data', [AdminTrafikController::class, 'data'])->name('admin.trafik.data');
});


// Laporan admin
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/generate', [AdminLaporanController::class, 'generate'])->name('admin.laporan.generate');
});

// Pelanggan admin
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/pelanggan', [AdminPelangganController::class, 'index'])->name('admin.pelanggan');
});

// Simulasi callback Midtrans (untuk testing)
Route::post('/simulasi-callback', [MidtransController::class, 'callbackSimulasi']);

// ----------------------
//      BELI LANGSUNG
// ----------------------
Route::post('/produk/{product}/beli-langsung', [ProductController::class, 'beliLangsung'])
    ->name('produk.beliLangsung');

Route::get('/checkout/beli-langsung', [ProductController::class, 'checkoutBeliLangsung'])
    ->middleware('auth')
    ->name('checkout.beliLangsung');

Route::post('/bayar-langsung', [MidtransController::class, 'bayarLangsung'])
    ->name('midtrans.bayarLangsung');

Route::post('/checkout/langsung/{product}', [ProductController::class, 'checkoutLangsung'])
    ->name('produk.checkoutLangsung');

Route::post('/checkout/batal', [MidtransController::class, 'batalCheckout'])
    ->name('batal.checkout');





Route::middleware(['auth'])->group(function () {

    Route::get('/chat/fetch', [ChatController::class, 'fetch'])->name('chat.fetch');
    Route::post('/chat/send-user', [ChatController::class, 'sendUser'])->name('chat.send.user');

});

Route::prefix('admin')->middleware(['auth:admin'])->group(function () {

    Route::get('/chat', [ChatController::class, 'adminChatPage'])->name('chat.admin');
    Route::get('/chat/users', [ChatController::class, 'adminUsers'])->name('chat.admin.users');
    Route::get('/chat/messages/{userId}', [ChatController::class, 'adminFetchMessages'])->name('chat.admin.messages');

    Route::post('/chat/send-admin', [ChatController::class, 'sendAdmin'])->name('chat.send.admin');
});

