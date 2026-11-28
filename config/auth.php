<?php

// balikin array setingan autentikasi (login, user provider, dan reset password)
return [

    // setingan default buat guard login dan broker reset password
    'defaults' => [
        // guard default yang dipake buat ngecek user udah login apa belum, defaultnya 'web'
        'guard' => env('AUTH_GUARD', 'web'),
        // broker reset password default yang ngarah ke grup 'users' di bawah
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    // daftar guard autentikasi buat ngatur cara user diloginin dan dicek statusnya
    'guards' => [
        // guard 'web' buat aplikasi browser biasa
        'web' => [
            // nyimpen status login user pake session browser
            'driver' => 'session',
            // ngambil data user-nya dari provider bernama 'users'
            'provider' => 'users',
        ],
    ],

    // daftar provider buat nentuin dari mana data user diambil (database atau model eloquent)
    'providers' => [
        // konfigurasi provider 'users'
        'users' => [
            // pake driver eloquent biar ngambil data user lewat class model laravel
            'driver' => 'eloquent',
            // model yang dipake buat representasi user, defaultnya App\Models\User
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // contoh alternatif kalo mau ngambil user langsung dari tabel tanpa model eloquent
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    // setingan fitur lupa/reset password user
    'passwords' => [
        // konfigurasi reset password buat grup 'users'
        'users' => [
            // pake provider 'users' di atas buat nyari akunnya
            'provider' => 'users',
            // nama tabel database buat nyimpen token reset password
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            // lama token reset password berlaku dalam hitungan menit (60 menit)
            'expire' => 60,
            // jeda tunggu minimal dalam detik sebelum user boleh minta token reset lagi
            'throttle' => 60,
        ],
    ],

    // batas waktu dalam detik sebelum user diminta masukin password ulang pas buka halaman sensitif (default 3 jam)
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
