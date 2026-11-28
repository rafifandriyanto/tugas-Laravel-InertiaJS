<?php

// balikin array pengaturan pengiriman email aplikasi
return [

    // pilih layanan kirim email utama dari env, defaultnya log biar cuma dicatet di file log aja pas lagi belajar
    'default' => env('MAIL_MAILER', 'log'),

    // daftar konfigurasi driver mailer yang bisa dipake buat kirim email
    'mailers' => [

        // konfigurasi kirim email lewat server smtp
        'smtp' => [
            // pake driver transport smtp
            'transport' => 'smtp',
            // skema enkripsi koneksi smtp kayak tls atau smtps dari env
            'scheme' => env('MAIL_SCHEME'),
            // dsn atau url lengkap koneksi mailer kalo diset sekaligus di env
            'url' => env('MAIL_URL'),
            // alamat host server smtp, defaultnya localhost 127.0.0.1
            'host' => env('MAIL_HOST', '127.0.0.1'),
            // port server smtp yang dipake, defaultnya 2525 biasanya buat mailpit atau mailtrap
            'port' => env('MAIL_PORT', 2525),
            // username akun buat login ke server smtp dari env
            'username' => env('MAIL_USERNAME'),
            // password akun smtp dari env
            'password' => env('MAIL_PASSWORD'),
            // batas waktu tunggu koneksi smtp dalam detik, null artinya pake bawaan
            'timeout' => null,
            // nama domain lokal yang dikirim pas perintah ehlo ke server smtp, ngambil host dari APP_URL
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        // konfigurasi kirim email lewat layanan amazon ses
        'ses' => [
            // pake driver transport ses
            'transport' => 'ses',
        ],

        // konfigurasi kirim email lewat layanan postmark
        'postmark' => [
            // pake driver transport postmark
            'transport' => 'postmark',
            // opsi tambahan kalo mau nentuin stream id atau timeout client postmark
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        // konfigurasi kirim email lewat layanan resend
        'resend' => [
            // pake driver transport resend
            'transport' => 'resend',
        ],

        // konfigurasi kirim email lewat program sendmail bawaan server linux
        'sendmail' => [
            // pake driver transport sendmail
            'transport' => 'sendmail',
            // lokasi file biner sendmail dan parameter jalannya di server
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        // konfigurasi mailer log buat nulis isi email ke file log bukan kirim beneran
        'log' => [
            // pake driver transport log
            'transport' => 'log',
            // channel log tujuan buat nyatet email, kalo kosong ngikutin channel default
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        // konfigurasi mailer array buat nampung email di memori aja, kepake pas testing
        'array' => [
            // pake driver transport array
            'transport' => 'array',
        ],

        // konfigurasi mailer cadangan kalo mailer pertama error langsung pindah ke mailer berikutnya
        'failover' => [
            // pake driver transport failover
            'transport' => 'failover',
            // urutan daftar mailer yang dicoba satu per satu pas kirim email
            'mailers' => [
                // coba kirim lewat smtp dulu
                'smtp',
                // kalo smtp gagal catet emailnya ke log aja
                'log',
            ],
        ],

        // konfigurasi mailer bagi rata beban pengiriman ke beberapa layanan secara bergantian
        'roundrobin' => [
            // pake driver transport roundrobin
            'transport' => 'roundrobin',
            // daftar mailer yang dipake giliran secara acak atau bergantian
            'mailers' => [
                // giliran pake amazon ses
                'ses',
                // giliran pake postmark
                'postmark',
            ],
        ],

    ],

    // pengaturan alamat dan nama pengirim global buat semua email yang dikirim aplikasi
    'from' => [
        // alamat email pengirim default dari env
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        // nama pengirim yang tampil di inbox penerima dari env
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

];
