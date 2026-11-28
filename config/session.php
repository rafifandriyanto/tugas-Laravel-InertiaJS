<?php

// panggil helper Str buat bantu ngolah string nama aplikasi jadi format slug
use Illuminate\Support\Str;

// balikin array konfigurasi pengelolaan session user
return [

    // pilih tempat nyimpen data session dari env, defaultnya disimpen di database
    'driver' => env('SESSION_DRIVER', 'database'),

    // lama waktu session aktif dalam hitungan menit kalo user lagi diem aja, defaultnya 120 menit
    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    // atur apakah session langsung hangus pas browser ditutup
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    // atur apakah semua isi data session dienkripsi dulu sebelum disimpen
    'encrypt' => env('SESSION_ENCRYPT', false),

    // folder lokasi nyimpen file session kalo driver yang dipake adalah file
    'files' => storage_path('framework/sessions'),

    // nama koneksi database atau redis khusus buat session kalo mau dipisah dari utama
    'connection' => env('SESSION_CONNECTION'),

    // nama tabel database buat nyimpen data session, defaultnya tabel sessions
    'table' => env('SESSION_TABLE', 'sessions'),

    // nama cache store yang dipake kalo driver sessionnya berbasis cache kayak redis atau memcached
    'store' => env('SESSION_STORE'),

    // peluang buat bersihin session kedaluwarsa di tiap request, defaultnya 2 dari 100 request
    'lottery' => [2, 100],

    // nama cookie session yang dikirim ke browser user
    'cookie' => env(
        // ambil nama cookie dari env kalo ada
        'SESSION_COOKIE',
        // kalo gak ada di env bikin nama cookie dari nama app yang diubah jadi huruf kecil pake underscore plus _session
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    // path url di mana cookie session boleh dipake, defaultnya garis miring buat semua halaman
    'path' => env('SESSION_PATH', '/'),

    // domain atau subdomain tempat cookie session ini berlaku
    'domain' => env('SESSION_DOMAIN'),

    // kalo diset true cookie session cuma dikirim lewat koneksi https yang aman
    'secure' => env('SESSION_SECURE_COOKIE'),

    // cegah javascript di browser baca cookie session biar lebih aman dari serangan xss
    'http_only' => env('SESSION_HTTP_ONLY', true),

    // atur kebijakan pengiriman cookie lintas situs buat cegah serangan csrf, defaultnya lax
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    // atur cookie terpartisi per situs utama buat kebutuhan konteks lintas situs yang aman
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
