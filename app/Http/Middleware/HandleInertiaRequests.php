<?php

// nentuin namespace middleware di app/Http/Middleware
namespace App\Http\Middleware;

// narik class Request buat baca info user, session, dan header
use Illuminate\Http\Request;
// narik middleware induk dari package Inertia
use Inertia\Middleware;

// middleware jembatan antara laravel dan frontend vue biar bisa share data global
class HandleInertiaRequests extends Middleware
{
    // nentuin nama file blade utama (resources/views/app.blade.php) pas halaman pertama kali dibuka
    protected $rootView = 'app';

    // ngambil versi aset frontend biar browser tau kapan harus reload ulang aset baru
    public function version(Request $request): ?string
    {
        // manggil pengecekan versi bawaan dari class induk Inertia\Middleware
        return parent::version($request);
    }

    // ngirim data global (shared props) yang bisa dibaca di semua komponen halaman vue
    public function share(Request $request): array
    {
        // gabungin shared props bawaan inertia sama data custom di bawah ini
        return [
            // nyebarin shared props bawaan dari parent class
            ...parent::share($request),
            // data autentikasi user yang lagi login
            'auth' => [
                // ngirim data user aktif (kalo belum login isinya null)
                'user' => $request->user(),
            ],
            // data pesan kilat (flash message) dari session
            'flash' => [
                // fungsi lazy buat ngambil flash message 'message' pas dibutuhin
                'message' => fn () => $request->session()->get('message'),
                // fungsi lazy buat ngambil flash message 'status' pas dibutuhin
                'status'  => fn () => $request->session()->get('status'),
            ],
            // info metadata starter kit laravel breeze buat ditampilin di ui/landing
            'breeze' => [
                // nunjukin breeze lagi aktif dipake
                'active'       => true,
                // versi breeze yang kepasang
                'version'      => '2.4.2',
                // nama paket starter kit
                'name'         => 'Laravel Breeze (Official Starter Kit)',
                // stack teknologi frontend yang dipake
                'stack'        => 'Vue 3 + Inertia.js',
                // lokasi folder controller autentikasi breeze
                'controllers'  => 'App\Http\Controllers\Auth',
                // lokasi form request login bawaan breeze
                'form_request' => 'App\Http\Requests\Auth\LoginRequest',
                // lokasi file daftar route autentikasi
                'routes_file'  => 'routes/auth.php',
            ],
            // info koneksi database yang lagi aktif buat ditampilin sebagai status sistem
            'db' => [
                // ngambil nama driver database default dari config (misal sqlite atau mysql)
                'driver'   => config('database.default'),
                // ngambil nama atau path database sesuai driver yang lagi dipake
                'database' => config('database.connections.' . config('database.default') . '.database'),
            ],
        ];
    }
}
