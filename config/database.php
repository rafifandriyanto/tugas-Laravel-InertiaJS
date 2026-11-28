<?php

// manggil helper Str buat ngubah nama aplikasi jadi format slug di prefix redis
use Illuminate\Support\Str;

// balikin semua pengaturan koneksi database dan redis
return [

    // koneksi database utama yang dipake kalo query ga nyebutin koneksi lain
    'default' => env('DB_CONNECTION', 'sqlite'),

    // daftar semua konfigurasi koneksi database yang didukung aplikasi
    'connections' => [

        // konfigurasi database sqlite berbasis file lokal
        'sqlite' => [
            // pake driver sqlite
            'driver' => 'sqlite',
            // url koneksi database lengkap kalo diset di .env
            'url' => env('DB_URL'),
            // lokasi file database sqlite, defaultnya di folder database/database.sqlite
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            // awalan nama tabel kalo perlu ditambahin prefix
            'prefix' => '',
            // aktifin pengecekan relasi foreign key di sqlite
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            // lama waktu tunggu maksimal pas database sqlite lagi kekunci proses lain
            'busy_timeout' => null,
            // mode jurnal penulisan data sqlite (misalnya WAL)
            'journal_mode' => null,
            // aturan sinkronisasi tulis data ke disk di sqlite
            'synchronous' => null,
        ],

        // konfigurasi database mysql
        'mysql' => [
            // pake driver mysql
            'driver' => 'mysql',
            // url koneksi mysql kalo pake format string url di .env
            'url' => env('DB_URL'),
            // alamat host server mysql, defaultnya localhost 127.0.0.1
            'host' => env('DB_HOST', '127.0.0.1'),
            // port server mysql, standar bawaannya 3306
            'port' => env('DB_PORT', '3306'),
            // nama database mysql yang dipake aplikasi
            'database' => env('DB_DATABASE', 'laravel'),
            // username buat login ke mysql
            'username' => env('DB_USERNAME', 'root'),
            // password buat login ke mysql
            'password' => env('DB_PASSWORD', ''),
            // path unix socket kalo konek ke mysql lokal lewat file socket
            'unix_socket' => env('DB_SOCKET', ''),
            // set karakter teks yang dipake mysql, utf8mb4 biar support emoji juga
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            // aturan pengurutan dan pembandingan teks di mysql
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            // awalan nama semua tabel kalo perlu prefix
            'prefix' => '',
            // ikut kasih prefix juga ke nama index tabel kalo prefix diisi
            'prefix_indexes' => true,
            // nyalain strict mode biar query mysql lebih ketat ngecek tipe data
            'strict' => true,
            // jenis storage engine tabel mysql, null berarti ikut default server (biasanya InnoDB)
            'engine' => null,
            // opsi tambahan pdo mysql buat sertifikat ssl kalo ekstensi pdo_mysql udah aktif
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                // file sertifikat ca ssl mysql diambil dari .env kalo ada
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            // kalo ekstensi pdo_mysql belum aktif kasih array kosong aja
            ]) : [],
        ],

        // konfigurasi database mariadb
        'mariadb' => [
            // pake driver mariadb
            'driver' => 'mariadb',
            // url koneksi mariadb kalo diset di .env
            'url' => env('DB_URL'),
            // alamat host server mariadb
            'host' => env('DB_HOST', '127.0.0.1'),
            // port server mariadb, defaultnya 3306
            'port' => env('DB_PORT', '3306'),
            // nama database mariadb yang dipake
            'database' => env('DB_DATABASE', 'laravel'),
            // username buat masuk ke mariadb
            'username' => env('DB_USERNAME', 'root'),
            // password buat masuk ke mariadb
            'password' => env('DB_PASSWORD', ''),
            // path unix socket buat koneksi lokal mariadb
            'unix_socket' => env('DB_SOCKET', ''),
            // set karakter teks buat mariadb, utf8mb4 biar aman nyimpen karakter unicode & emoji
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            // aturan collation buat ngebandingin huruf di mariadb
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            // awalan nama tabel mariadb
            'prefix' => '',
            // ikut kasih prefix ke nama index tabel juga
            'prefix_indexes' => true,
            // nyalain strict mode biar validasi query sql lebih aman
            'strict' => true,
            // engine tabel mariadb, null biar ngikutin default server
            'engine' => null,
            // opsi tambahan pdo buat koneksi ssl mariadb kalo ekstensi pdo_mysql keinstall
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                // file sertifikat ca ssl dari .env kalo koneksi butuh ssl
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            // kalo ekstensi pdo_mysql belum aktif kasih array kosong aja
            ]) : [],
        ],

        // konfigurasi database postgresql
        'pgsql' => [
            // pake driver pgsql
            'driver' => 'pgsql',
            // url koneksi postgresql dari .env
            'url' => env('DB_URL'),
            // alamat host server postgresql
            'host' => env('DB_HOST', '127.0.0.1'),
            // port server postgresql, standar bawaannya 5432
            'port' => env('DB_PORT', '5432'),
            // nama database postgresql yang mau dipake
            'database' => env('DB_DATABASE', 'laravel'),
            // username login postgresql
            'username' => env('DB_USERNAME', 'root'),
            // password login postgresql
            'password' => env('DB_PASSWORD', ''),
            // encoding karakter buat postgresql
            'charset' => env('DB_CHARSET', 'utf8'),
            // awalan nama tabel postgresql
            'prefix' => '',
            // tambahin prefix ke index tabel juga kalo prefix dipake
            'prefix_indexes' => true,
            // nama schema default yang dicari di postgresql
            'search_path' => 'public',
            // mode enkripsi ssl pas konek ke server postgresql
            'sslmode' => 'prefer',
        ],

        // konfigurasi database microsoft sql server
        'sqlsrv' => [
            // pake driver sqlsrv
            'driver' => 'sqlsrv',
            // url koneksi sql server dari .env
            'url' => env('DB_URL'),
            // alamat host sql server
            'host' => env('DB_HOST', 'localhost'),
            // port standar sql server
            'port' => env('DB_PORT', '1433'),
            // nama database sql server
            'database' => env('DB_DATABASE', 'laravel'),
            // username login sql server
            'username' => env('DB_USERNAME', 'root'),
            // password login sql server
            'password' => env('DB_PASSWORD', ''),
            // encoding karakter teks sql server
            'charset' => env('DB_CHARSET', 'utf8'),
            // awalan nama tabel sql server
            'prefix' => '',
            // ikut tambahin prefix ke index tabel
            'prefix_indexes' => true,
            // opsi tambahan kalo mau aktifin enkripsi koneksi sql server
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // opsi buat percaya sertifikat server meski self-signed
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    // pengaturan tabel buat nyatet riwayat migrasi database yang udah pernah dijalanin
    'migrations' => [
        // nama tabel pencatat migrasi di database
        'table' => 'migrations',
        // update tanggal file migrasi pas publish migrasi dari package luar
        'update_date_on_publish' => true,
    ],

    // pengaturan koneksi database redis buat cache, queue, atau session
    'redis' => [

        // library client php yang dipake buat ngobrol sama server redis (phpredis atau predis)
        'client' => env('REDIS_CLIENT', 'phpredis'),

        // opsi umum koneksi redis
        'options' => [
            // mode cluster redis kalo pake banyak node
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            // awalan nama key redis biar ga bentrok sama aplikasi lain di satu server
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
            // pake koneksi persistent atau bikin koneksi baru tiap request
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        // koneksi redis utama buat keperluan umum aplikasi
        'default' => [
            // url koneksi redis lengkap dari .env
            'url' => env('REDIS_URL'),
            // alamat ip atau host server redis
            'host' => env('REDIS_HOST', '127.0.0.1'),
            // username login redis kalo diset
            'username' => env('REDIS_USERNAME'),
            // password login redis kalo pake password
            'password' => env('REDIS_PASSWORD'),
            // port standar server redis
            'port' => env('REDIS_PORT', '6379'),
            // nomor indeks database redis yang dipake (default 0)
            'database' => env('REDIS_DB', '0'),
        ],

        // koneksi redis khusus buat nyimpen data cache
        'cache' => [
            // url koneksi redis cache dari .env
            'url' => env('REDIS_URL'),
            // alamat ip atau host server redis cache
            'host' => env('REDIS_HOST', '127.0.0.1'),
            // username login redis cache
            'username' => env('REDIS_USERNAME'),
            // password login redis cache
            'password' => env('REDIS_PASSWORD'),
            // port server redis cache
            'port' => env('REDIS_PORT', '6379'),
            // nomor indeks database redis khusus cache biar misah dari yang default
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
