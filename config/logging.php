<?php

// panggil class NullHandler buat ngebuang log biar gak ditulis ke mana-mana
use Monolog\Handler\NullHandler;
// panggil class StreamHandler buat nulis log ke file atau stream kayak stderr
use Monolog\Handler\StreamHandler;
// panggil class SyslogUdpHandler buat kirim log lewat protokol udp ke server syslog
use Monolog\Handler\SyslogUdpHandler;
// panggil class PsrLogMessageProcessor buat ngolah placeholder pesan log standar psr
use Monolog\Processor\PsrLogMessageProcessor;

// balikin semua pengaturan logging aplikasi dalam bentuk array
return [

    // pilih channel log utama dari env, kalo gak diset pake channel stack
    'default' => env('LOG_CHANNEL', 'stack'),

    // pengaturan log khusus buat nyatet peringatan fitur php atau library yang udah usang
    'deprecations' => [
        // tentuin channel tujuan log deprecation, defaultnya ke null alias gak dicatet
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        // atur apakah stack trace ikut dicatet pas ada peringatan fitur usang
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    // daftar semua pilihan channel log yang disediain buat aplikasi
    'channels' => [

        // channel stack buat gabungin beberapa channel log sekaligus dalam satu kali catet
        'stack' => [
            // pake driver stack biar bisa nerusin log ke banyak channel
            'driver' => 'stack',
            // pecah daftar channel dari env dipisahin koma, defaultnya cuma channel single
            'channels' => explode(',', env('LOG_STACK', 'single')),
            // jangan abaikan error kalo proses penulisan log bermasalah
            'ignore_exceptions' => false,
        ],

        // channel single buat nyimpen semua log di satu file aja
        'single' => [
            // pake driver single buat nulis ke satu file log
            'driver' => 'single',
            // lokasi file log disimpen di folder storage/logs/laravel.log
            'path' => storage_path('logs/laravel.log'),
            // batas minimal level log yang dicatet, defaultnya mulai dari debug
            'level' => env('LOG_LEVEL', 'debug'),
            // aktifin penggantian placeholder kurung kurawal di pesan log pake data context
            'replace_placeholders' => true,
        ],

        // channel daily buat bikin file log baru tiap hari biar filenya gak kegedean
        'daily' => [
            // pake driver daily buat rotasi file log harian
            'driver' => 'daily',
            // path dasar file log harian yang nanti ditambah tanggal di nama filenya
            'path' => storage_path('logs/laravel.log'),
            // minimal level log yang dicatet di file harian, defaultnya debug
            'level' => env('LOG_LEVEL', 'debug'),
            // simpen file log harian maksimal 14 hari sebelum dibersihin
            'days' => env('LOG_DAILY_DAYS', 14),
            // ganti placeholder di pesan log pake isi array context
            'replace_placeholders' => true,
        ],

        // channel slack buat ngirim notif log penting ke ruang obrolan slack
        'slack' => [
            // pake driver slack buat kirim pesan lewat webhook
            'driver' => 'slack',
            // alamat webhook slack yang diambil dari file env
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            // nama pengirim bot pas muncul di slack, defaultnya Laravel Log
            'username' => env('LOG_SLACK_USERNAME', 'Laravel Log'),
            // ikon emoji bot yang dipake di pesan slack
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
            // level log yang dikirim ke slack, defaultnya cuma yang critical aja
            'level' => env('LOG_LEVEL', 'critical'),
            // ganti placeholder pesan log dengan nilai context sebelum dikirim
            'replace_placeholders' => true,
        ],

        // channel papertrail buat kirim log ke layanan monitoring papertrail
        'papertrail' => [
            // pake driver monolog biar bisa pakai handler custom
            'driver' => 'monolog',
            // batas level log minimal yang dikirim ke papertrail
            'level' => env('LOG_LEVEL', 'debug'),
            // handler monolog yang dipake buat kirim log ke papertrail lewat udp
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            // parameter tambahan yang dikirim ke handler papertrail
            'handler_with' => [
                // alamat host server papertrail dari env
                'host' => env('PAPERTRAIL_URL'),
                // nomor port server papertrail dari env
                'port' => env('PAPERTRAIL_PORT'),
                // gabungin protokol tls sama host dan port jadi string koneksi
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            // tambahin processor biar format placeholder pesan log sesuai standar psr
            'processors' => [PsrLogMessageProcessor::class],
        ],

        // channel stderr buat nampilin log langsung ke output error terminal atau container
        'stderr' => [
            // pake driver monolog buat ngarahin log ke stream stderr
            'driver' => 'monolog',
            // minimal level log yang ditampilin ke stderr
            'level' => env('LOG_LEVEL', 'debug'),
            // pake StreamHandler buat nulis log ke aliran output
            'handler' => StreamHandler::class,
            // class formatter custom dari env kalo mau ubah tampilan log stderr
            'formatter' => env('LOG_STDERR_FORMATTER'),
            // opsi konfigurasi yang dikasih ke StreamHandler
            'with' => [
                // arahin output stream ke php://stderr
                'stream' => 'php://stderr',
            ],
            // proses placeholder pesan log pake standar psr
            'processors' => [PsrLogMessageProcessor::class],
        ],

        // channel syslog buat nyatet log ke sistem log bawaan os linux atau server
        'syslog' => [
            // pake driver syslog bawaan
            'driver' => 'syslog',
            // minimal level log yang dikirim ke syslog
            'level' => env('LOG_LEVEL', 'debug'),
            // kategori fasilitas syslog, defaultnya LOG_USER
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
            // aktifin pengisian placeholder pesan log pake data context
            'replace_placeholders' => true,
        ],

        // channel errorlog buat nulis log ke fungsi error_log bawaan php
        'errorlog' => [
            // pake driver errorlog
            'driver' => 'errorlog',
            // minimal level log yang masuk ke error_log php
            'level' => env('LOG_LEVEL', 'debug'),
            // ganti placeholder pesan log dengan isi context
            'replace_placeholders' => true,
        ],

        // channel null buat matiin atau buang semua pesan log yang masuk ke sini
        'null' => [
            // pake driver monolog dengan handler kosong
            'driver' => 'monolog',
            // pake NullHandler biar semua log gak disimpan ke mana-mana
            'handler' => NullHandler::class,
        ],

        // channel darurat yang dipake kalo konfigurasi channel utama lagi gagal jalan
        'emergency' => [
            // file cadangan buat nyatet error darurat di storage/logs/laravel.log
            'path' => storage_path('logs/laravel.log'),
        ],

    ],

];
