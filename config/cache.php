<?php

// ambil helper Str buat bikin slug nama aplikasi di prefix cache nanti
use Illuminate\Support\Str;

// balikin array setingan sistem cache laravel
return [

    // nama store cache default yang dipake kalo ga disebutin pas manggil Cache, defaultnya 'database'
    'default' => env('CACHE_STORE', 'database'),

    // daftar semua pilihan tempat nyimpen cache beserta drivernya
    'stores' => [

        // store 'array', nyimpen cache cuma di memori sementara pas satu request aja (kepake buat testing)
        'array' => [
            // driver in-memory array
            'driver' => 'array',
            // matiin serialize data biar proses baca tulis array lebih cepet
            'serialize' => false,
        ],

        // store 'database', nyimpen data cache ke tabel database
        'database' => [
            // driver penyimpanan cache lewat database
            'driver' => 'database',
            // nama koneksi database khusus cache kalo mau dipisah dari db utama
            'connection' => env('DB_CACHE_CONNECTION'),
            // nama tabel buat nyimpen data cache, defaultnya tabel 'cache'
            'table' => env('DB_CACHE_TABLE', 'cache'),
            // nama koneksi database buat nyimpen lock cache (cegah race condition)
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            // nama tabel buat nyimpen lock cache
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        // store 'file', nyimpen cache sebagai file di folder storage
        'file' => [
            // driver berbasis file di disk
            'driver' => 'file',
            // lokasi folder tempat nyimpen file data cache
            'path' => storage_path('framework/cache/data'),
            // lokasi folder buat nyimpen file lock cache
            'lock_path' => storage_path('framework/cache/data'),
        ],

        // store 'memcached', nyimpen cache di server memori Memcached
        'memcached' => [
            // driver memcached
            'driver' => 'memcached',
            // id koneksi persisten biar koneksi memcached bisa dipake ulang terus
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            // kredensial login SASL kalo server memcached pake autentikasi
            'sasl' => [
                // username SASL memcached dari .env
                env('MEMCACHED_USERNAME'),
                // password SASL memcached dari .env
                env('MEMCACHED_PASSWORD'),
            ],
            // opsi tambahan buat client Memcached PHP
            'options' => [
                // contoh opsi batas waktu koneksi ke memcached dalam milidetik
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            // daftar alamat server memcached yang dipake
            'servers' => [
                // konfigurasi server memcached pertama
                [
                    // alamat ip atau host server memcached
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    // port server memcached, defaultnya 11211
                    'port' => env('MEMCACHED_PORT', 11211),
                    // bobot prioritas server kalo servernya ada lebih dari satu
                    'weight' => 100,
                ],
            ],
        ],

        // store 'redis', nyimpen cache di server Redis
        'redis' => [
            // driver redis
            'driver' => 'redis',
            // nama koneksi redis yang diseting di config/database.php buat cache
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            // nama koneksi redis yang dipake khusus buat lock cache
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        // store 'dynamodb', nyimpen cache ke layanan AWS DynamoDB
        'dynamodb' => [
            // driver dynamodb
            'driver' => 'dynamodb',
            // access key akun AWS dari .env
            'key' => env('AWS_ACCESS_KEY_ID'),
            // secret key akun AWS dari .env
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            // region server AWS tempat DynamoDB berada
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            // nama tabel DynamoDB yang dipake buat nyimpen cache
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            // alamat endpoint custom kalo pake DynamoDB lokal buat testing
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        // store 'octane', nyimpen cache di memori Laravel Octane
        'octane' => [
            // driver octane buat performa super kenceng di server Octane
            'driver' => 'octane',
        ],

    ],

    // awalan nama key cache biar ga bentrok kalo ada aplikasi lain yang share cache server yang sama
    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

];
