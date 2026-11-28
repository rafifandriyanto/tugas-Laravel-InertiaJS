<?php

// manggil LandingController buat ngatur halaman depan
use App\Http\Controllers\LandingController;
// manggil PostController buat ngatur fitur CRUD postingan
use App\Http\Controllers\PostController;
// manggil ProfileController buat ngatur edit sama hapus profil user
use App\Http\Controllers\ProfileController;
// manggil facade Route buat daftarin alamat URL aplikasi
use Illuminate\Support\Facades\Route;
// manggil Inertia buat ngerender komponen halaman Vue
use Inertia\Inertia;

// daftar route web utama buat aplikasi Laravel 12 + Laravel Breeze + Inertia Vue

// halaman depan yang bisa dibuka siapa aja tanpa login, sekalian nampilin info Breeze sama post terbaru
Route::get('/', [LandingController::class, 'index'])->name('home');

// halaman dashboard bawaan Breeze
Route::get('/dashboard', function () {
    // tampilin halaman Vue Dashboard lewat Inertia
    return Inertia::render('Dashboard');
// cuma bisa dibuka user yang udah login dan emailnya udah terverifikasi, terus kasih nama route 'dashboard'
})->middleware(['auth', 'verified'])->name('dashboard');

// halaman daftar semua postingan yang bisa diliat publik
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// grup route yang wajib login dulu baru boleh diakses
Route::middleware('auth')->group(function () {
    // buka halaman form buat bikin postingan baru
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    // nerima data form lalu nyimpen postingan baru ke database
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // buka halaman form edit buat ngubah postingan tertentu sesuai id/slug
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    // simpen perubahan data postingan yang abis diedit
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    // hapus data postingan dari database
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // buka halaman pengaturan profil user bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // proses update nama atau email profil user yang lagi login
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // proses hapus akun user yang lagi login
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// masukin semua route login, register, lupa password, dan logout dari file routes/auth.php
require __DIR__.'/auth.php';
