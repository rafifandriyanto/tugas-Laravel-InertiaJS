<?php

// manggil class utama Application dari Laravel
use Illuminate\Foundation\Application;
// manggil class Request buat nangkep permintaan HTTP yang masuk dari browser
use Illuminate\Http\Request;

// simpen waktu mulai pas request masuk biar performanya bisa diukur
define('LARAVEL_START', microtime(true));

// cek dulu apakah aplikasi lagi mode maintenance (ada file maintenance.php apa nggak)
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    // kalo lagi maintenance, langsung muat file halaman maintenancenya
    require $maintenance;
}

// muat autoloader Composer biar semua library di folder vendor kebaca
require __DIR__.'/../vendor/autoload.php';

// siapin aplikasi Laravel dari file bootstrap/app.php (tipenya Application)
$app = require_once __DIR__.'/../bootstrap/app.php';

// tangkep request HTTP yang masuk lalu proses lewat aplikasi Laravel sampe keluar response
$app->handleRequest(Request::capture());
