<?php

// manggil class Application buat ngatur konfigurasi inti Laravel
use Illuminate\Foundation\Application;
// manggil class Exceptions buat ngatur penanganan error
use Illuminate\Foundation\Configuration\Exceptions;
// manggil class Middleware buat daftarin middleware aplikasi
use Illuminate\Foundation\Configuration\Middleware;

// mulai nyiapin konfigurasi aplikasi Laravel dari folder root project
return Application::configure(basePath: dirname(__DIR__))
    // daftarin file route sama endpoint bawaan aplikasi
    ->withRouting(
        // arahin semua route halaman web ke file routes/web.php
        web: __DIR__.'/../routes/web.php',
        // arahin command terminal custom ke file routes/console.php
        commands: __DIR__.'/../routes/console.php',
        // aktifin endpoint /up buat ngecek apakah server aplikasi masih sehat
        health: '/up',
    )
    // daftarin middleware yang bakal jalan buat grup route web
    ->withMiddleware(function (Middleware $middleware) {
        // tambahin middleware Inertia sama preload asset di akhir tumpukan middleware web
        $middleware->web(append: [
            // middleware buat ngirim data global (seperti auth dan flash message) ke Inertia
            \App\Http\Middleware\HandleInertiaRequests::class,
            // middleware buat ngasih header preload aset JS/CSS biar loading halaman makin ngebut
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // tambahin middleware HandleInertiaRequests ke grup middleware web
        $middleware->web(append: [
            // daftarin middleware HandleInertiaRequests buat nge-handle request Inertia
            App\Http\Middleware\HandleInertiaRequests::class,
        ]);
    })
    // tempat buat ngatur custom error handler kalo suatu saat dibutuhin
    ->withExceptions(function (Exceptions $exceptions) {
        // masih kosong karena masih pake error handling bawaan Laravel
    // selesaiin semua konfigurasi terus balikin objek aplikasinya biar siap jalan
    })->create();
