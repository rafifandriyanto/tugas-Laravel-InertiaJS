<?php

// manggil class Inspiring buat ngambil kumpulan quote penyemangat bawaan Laravel
use Illuminate\Foundation\Inspiring;
// manggil facade Artisan buat daftarin command terminal custom
use Illuminate\Support\Facades\Artisan;

// bikin command artisan 'inspire' yang bisa dipanggil lewat php artisan inspire
Artisan::command('inspire', function () {
    // tampilin satu quote motivasi random ke layar terminal
    $this->comment(Inspiring::quote());
// kasih deskripsi singkat biar keliatan keterangannya pas jalanin php artisan list
})->purpose('Display an inspiring quote');
