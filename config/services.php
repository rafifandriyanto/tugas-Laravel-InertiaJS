<?php

// balikin array tempat nyimpen kunci dan kredensial layanan pihak ketiga
return [

    // kredensial buat layanan email postmark
    'postmark' => [
        // token api postmark yang diambil dari env
        'token' => env('POSTMARK_TOKEN'),
    ],

    // kredensial buat layanan email amazon ses
    'ses' => [
        // access key akun aws dari env
        'key' => env('AWS_ACCESS_KEY_ID'),
        // secret key akun aws dari env
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        // region server aws yang dipake buat ses, defaultnya us-east-1
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // kredensial buat layanan email resend
    'resend' => [
        // api key resend yang diambil dari env
        'key' => env('RESEND_KEY'),
    ],

    // kredensial buat integrasi notifikasi ke slack
    'slack' => [
        // pengaturan bot notifikasi slack
        'notifications' => [
            // token oauth bot slack buat ngirim pesan ke channel
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            // nama atau id channel slack default tujuan notifikasi
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
