<?php

// manggil controller buat ngurus login sama logout
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// manggil controller buat halaman konfirmasi password sebelum aksi sensitif
use App\Http\Controllers\Auth\ConfirmablePasswordController;
// manggil controller buat kirim ulang link verifikasi email
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
// manggil controller yang nampilin halaman minta verifikasi email
use App\Http\Controllers\Auth\EmailVerificationPromptController;
// manggil controller buat nyimpen password baru pas reset password
use App\Http\Controllers\Auth\NewPasswordController;
// manggil controller buat ganti password dari halaman profil
use App\Http\Controllers\Auth\PasswordController;
// manggil controller buat ngirim link reset password ke email
use App\Http\Controllers\Auth\PasswordResetLinkController;
// manggil controller buat pendaftaran akun user baru
use App\Http\Controllers\Auth\RegisteredUserController;
// manggil controller buat proses verifikasi email pas user klik link di emailnya
use App\Http\Controllers\Auth\VerifyEmailController;
// manggil facade Route buat bikin jalur URL
use Illuminate\Support\Facades\Route;

// grup route khusus tamu (user yang belum login)
Route::middleware('guest')->group(function () {
    // buka halaman form registrasi akun baru
    Route::get('register', [RegisteredUserController::class, 'create'])
        // kasih nama route 'register' biar gampang dipanggil di link atau redirect
        ->name('register');

    // proses data registrasi dan simpen user baru ke database
    Route::post('register', [RegisteredUserController::class, 'store']);

    // buka halaman form login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        // kasih nama route 'login' buat alamat masuk akun
        ->name('login');

    // proses validasi email dan password pas user klik tombol login
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // buka halaman lupa password buat masukin email
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        // kasih nama route 'password.request'
        ->name('password.request');

    // kirim email berisi link reset password ke alamat email yang dimasukin
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        // kasih nama route 'password.email'
        ->name('password.email');

    // buka halaman buat bikin password baru pake token dari link email
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        // kasih nama route 'password.reset'
        ->name('password.reset');

    // proses simpen password baru ke database
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        // kasih nama route 'password.store'
        ->name('password.store');
});

// grup route khusus user yang udah login
Route::middleware('auth')->group(function () {
    // tampilin halaman peringatan buat verifikasi email dulu
    Route::get('verify-email', EmailVerificationPromptController::class)
        // kasih nama route 'verification.notice'
        ->name('verification.notice');

    // proses verifikasi email pas user ngeklik link dari email
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        // wajibin signature URL asli dan batesin maksimal 6 kali per menit biar nggak dispam
        ->middleware(['signed', 'throttle:6,1'])
        // kasih nama route 'verification.verify'
        ->name('verification.verify');

    // kirim ulang email verifikasi ke user yang lagi login
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        // batesin maksimal kirim ulang 6 kali dalam semenit
        ->middleware('throttle:6,1')
        // kasih nama route 'verification.send'
        ->name('verification.send');

    // buka halaman konfirmasi password ulang sebelum akses fitur penting
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        // kasih nama route 'password.confirm'
        ->name('password.confirm');

    // cek apakah password yang dimasukin beneran cocok sama user yang login
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // proses ganti password baru lewat halaman profil
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // proses keluar akun dan hapus session login user
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        // kasih nama route 'logout'
        ->name('logout');
});
