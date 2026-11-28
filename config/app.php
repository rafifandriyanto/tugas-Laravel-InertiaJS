<?php

// balikin array setingan utama aplikasi laravel
return [

    // nama aplikasi, diambil dari APP_NAME di .env atau default 'Laravel' buat ditampilin di notifikasi sama judul halaman
    'name' => env('APP_NAME', 'Laravel'),

    // mode lingkungan aplikasi lagi jalan di mana (local atau production), diambil dari APP_ENV di .env
    'env' => env('APP_ENV', 'production'),

    // status debug mode, kalo true bakal nampilin detail error lengkap pas ada bug (kalo live harusnya false)
    'debug' => (bool) env('APP_DEBUG', false),

    // alamat url utama aplikasi, kepake pas artisan bikin link atau manggil asset
    'url' => env('APP_URL', 'http://localhost'),

    // zona waktu default buat fungsi tanggal dan jam di php dan laravel, diset ke UTC biar seragam
    'timezone' => 'UTC',

    // bahasa utama yang dipake fitur terjemahan laravel, diambil dari APP_LOCALE atau default inggris
    'locale' => env('APP_LOCALE', 'en'),

    // bahasa cadangan kalo teks terjemahan di bahasa utama ga ketemu
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    // bahasa/wilayah buat data palsu pas pake Faker di factory atau seeder
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    // algoritma enkripsi yang dipake buat ngamanin data kayak cookie dan payload terenkripsi
    'cipher' => 'AES-256-CBC',

    // kunci rahasia buat enkripsi aplikasi, wajib diisi lewat APP_KEY di .env (bisa bikin pake php artisan key:generate)
    'key' => env('APP_KEY'),

    // daftar kunci enkripsi lama kalo abis ganti APP_KEY biar data lama masih bisa kebuka
    'previous_keys' => [
        // bongkar hasil array_filter biar isinya langsung masuk ke array previous_keys tanpa elemen kosong
        ...array_filter(
            // pecah string APP_PREVIOUS_KEYS yang dipisahin koma jadi array
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    // setingan penyimpanan status pas aplikasi lagi mode maintenance (php artisan down)
    'maintenance' => [
        // driver buat nyimpen status maintenance, bisa pake 'file' atau 'cache'
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        // nama store cache yang dipake nyimpen status maintenance kalo drivernya diset ke 'cache'
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
