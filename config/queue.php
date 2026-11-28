<?php

// balikin array konfigurasi antrian tugas atau queue aplikasi
return [

    // pilih koneksi antrian utama dari env, defaultnya nyimpen job antrian di database
    'default' => env('QUEUE_CONNECTION', 'database'),

    // daftar semua koneksi backend antrian yang didukung aplikasi
    'connections' => [

        // koneksi sync buat langsung jalanin job saat itu juga tanpa antri di background
        'sync' => [
            // pake driver sync alias eksekusi langsung
            'driver' => 'sync',
        ],

        // koneksi database buat nyimpen daftar antrian job di tabel database
        'database' => [
            // pake driver database
            'driver' => 'database',
            // nama koneksi database yang dipake buat nyimpen antrian, kalo null pake db default
            'connection' => env('DB_QUEUE_CONNECTION'),
            // nama tabel buat nyimpen antrian job, defaultnya tabel jobs
            'table' => env('DB_QUEUE_TABLE', 'jobs'),
            // nama antrian default kalo pas dispatch job gak nyebut nama queue
            'queue' => env('DB_QUEUE', 'default'),
            // waktu tunggu dalam detik sebelum job yang macet dianggap gagal dan dicoba ulang
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
            // tentuin job baru masuk antrian setelah transaksi database commit atau langsung
            'after_commit' => false,
        ],

        // koneksi antrian lewat service beanstalkd
        'beanstalkd' => [
            // pake driver beanstalkd
            'driver' => 'beanstalkd',
            // alamat host server beanstalkd, defaultnya localhost
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'),
            // nama tube atau antrian default di beanstalkd
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            // batas detik sebelum job dilempar ulang kalo worker belum selesai
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90),
            // waktu tunggu worker nahan koneksi pas antrian kosong dalam detik
            'block_for' => 0,
            // jangan tunggu transaksi database commit buat masukin job ke antrian
            'after_commit' => false,
        ],

        // koneksi antrian pake layanan cloud amazon sqs
        'sqs' => [
            // pake driver sqs
            'driver' => 'sqs',
            // access key akun aws dari env
            'key' => env('AWS_ACCESS_KEY_ID'),
            // secret key akun aws dari env
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            // url dasar antrian sqs sesuai id akun aws
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            // nama antrian default di sqs
            'queue' => env('SQS_QUEUE', 'default'),
            // akhiran tambahan nama queue sqs kalo dibutuhin
            'suffix' => env('SQS_SUFFIX'),
            // wilayah server aws yang dipake, defaultnya us-east-1
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            // kirim job ke sqs langsung tanpa nunggu transaksi database commit
            'after_commit' => false,
        ],

        // koneksi antrian pake redis yang lebih cepet karena di memori
        'redis' => [
            // pake driver redis
            'driver' => 'redis',
            // nama koneksi redis yang dipake dari file config database
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            // nama antrian default di redis
            'queue' => env('REDIS_QUEUE', 'default'),
            // batas waktu detik sebelum job yang gantung diproses ulang
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90),
            // lama waktu worker nunggu job baru di redis, null artinya polling biasa
            'block_for' => null,
            // kirim job ke redis langsung tanpa nunggu commit transaksi db
            'after_commit' => false,
        ],

    ],

    // konfigurasi tempat nyimpen data kelompok job atau job batching
    'batching' => [
        // koneksi database tempat nyimpen data batch job
        'database' => env('DB_CONNECTION', 'sqlite'),
        // nama tabel yang nyimpen daftar batch job
        'table' => 'job_batches',
    ],

    // konfigurasi pencatatan job yang gagal dijalankan
    'failed' => [
        // metode penyimpanan job gagal, defaultnya pake database dengan id uuid
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        // koneksi database buat nyimpen tabel job yang gagal
        'database' => env('DB_CONNECTION', 'sqlite'),
        // nama tabel buat nyimpen daftar job yang gagal
        'table' => 'failed_jobs',
    ],

];
