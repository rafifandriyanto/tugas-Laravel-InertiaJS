<?php

// nentuin namespace service provider di app/Providers
namespace App\Providers;

// narik facade Vite buat ngatur strategi muat aset frontend (JS/CSS)
use Illuminate\Support\Facades\Vite;
// narik class induk ServiceProvider dari laravel
use Illuminate\Support\ServiceProvider;

// service provider utama aplikasi buat daftarin dan nyiapin layanan pas booting
class AppServiceProvider extends ServiceProvider
{
    // tempat buat daftarin binding atau service custom sebelum aplikasi jalan penuh
    public function register(): void
    {
        // masih kosong karena belum ada service custom yang perlu didaftarin
    }

    // jalan pas semua service provider udah siap buat ngatur konfigurasi awal aplikasi
    public function boot(): void
    {
        // nyuruh vite buat prefetch aset frontend maksimal 3 file paralel biar pindah halaman lebih cepet
        Vite::prefetch(concurrency: 3);
    }
}
