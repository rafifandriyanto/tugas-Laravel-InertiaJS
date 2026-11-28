<?php

// balikin semua pengaturan penyimpanan file dan upload di aplikasi
return [

    // disk penyimpanan utama yang dipake kalo ga nyebutin disk tertentu
    'default' => env('FILESYSTEM_DISK', 'local'),

    // daftar semua disk penyimpanan file yang bisa dipake (lokal atau cloud)
    'disks' => [

        // disk lokal buat nyimpen file privat yang ga langsung kebuka dari browser
        'local' => [
            // pake driver penyimpanan lokal di server sendiri
            'driver' => 'local',
            // folder utama tempat file privat disimpen
            'root' => storage_path('app/private'),
            // bolehin file di disk ini dilayani lewat route download/preview internal laravel
            'serve' => true,
            // jangan langsung lempar exception kalo operasi file gagal, cukup return false
            'throw' => false,
            // ga usah kirim laporan error operasi file ke log handler
            'report' => false,
        ],

        // disk lokal buat file publik yang bisa diakses langsung lewat url browser
        'public' => [
            // pake driver penyimpanan lokal
            'driver' => 'local',
            // folder utama tempat nyimpen file publik
            'root' => storage_path('app/public'),
            // alamat url dasar buat ngakses file publik lewat browser
            'url' => env('APP_URL').'/storage',
            // set hak akses file jadi publik biar bisa dibuka siapa aja
            'visibility' => 'public',
            // jangan langsung lempar exception kalo ada error baca/tulis file
            'throw' => false,
            // ga usah kirim laporan error file publik ke log handler
            'report' => false,
        ],

        // disk buat nyimpen file ke layanan cloud amazon s3 atau storage lain yang kompatibel
        's3' => [
            // pake driver s3
            'driver' => 's3',
            // access key id akun aws atau cloud storage dari .env
            'key' => env('AWS_ACCESS_KEY_ID'),
            // secret key akun aws atau cloud storage dari .env
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            // region server bucket s3 berada
            'region' => env('AWS_DEFAULT_REGION'),
            // nama bucket s3 tempat nyimpen file
            'bucket' => env('AWS_BUCKET'),
            // url publik custom buat ngakses file dari bucket s3
            'url' => env('AWS_URL'),
            // endpoint url custom kalo pake minio, cloudflare r2, atau layanan sejenis s3
            'endpoint' => env('AWS_ENDPOINT'),
            // pake format url path style bukan subdomain bucket kalo diset true
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            // jangan lempar exception kalo gagal akses s3 kecuali dicek manual
            'throw' => false,
            // ga usah kirim laporan error operasi s3 ke log handler
            'report' => false,
        ],

    ],

    // daftar symlink yang bakal dibuat pas jalanin perintah php artisan storage:link
    'links' => [
        // hubungin folder public/storage ke storage/app/public biar file upload bisa diakses web
        public_path('storage') => storage_path('app/public'),
    ],

];
