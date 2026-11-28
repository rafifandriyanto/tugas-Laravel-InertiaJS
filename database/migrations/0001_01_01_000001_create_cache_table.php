<?php

// panggil class Migration sebagai dasar class migrasi Laravel
use Illuminate\Database\Migrations\Migration;
// panggil Blueprint buat ngatur tipe kolom tabel
use Illuminate\Database\Schema\Blueprint;
// panggil facade Schema buat bikin dan hapus tabel di database
use Illuminate\Support\Facades\Schema;

// class migration buat bikin tabel cache dan cache_locks bawaan Laravel
return new class extends Migration
{
    // jalanin pembuatan tabel cache pas migrate dijalankan
    public function up(): void
    {
        // bikin tabel cache buat nyimpen data cache di database
        Schema::create('cache', function (Blueprint $table) {
            // bikin kolom key bertipe string sebagai primary key pengenal cache
            $table->string('key')->primary();
            // bikin kolom value ukuran mediumText buat nyimpen isi datanya
            $table->mediumText('value');
            // bikin kolom expiration buat nyatet kapan cache-nya kadaluarsa
            $table->integer('expiration');
        });

        // bikin tabel cache_locks buat ngunci cache biar gak tabrakan pas diakses barengan
        Schema::create('cache_locks', function (Blueprint $table) {
            // bikin kolom key sebagai primary key kunci cache
            $table->string('key')->primary();
            // bikin kolom owner buat nyatet siapa yang lagi megang kuncinya
            $table->string('owner');
            // bikin kolom expiration buat batas waktu kunci cache-nya
            $table->integer('expiration');
        });
    }

    // hapus tabel cache kalo migration di-rollback
    public function down(): void
    {
        // hapus tabel cache kalo ada di database
        Schema::dropIfExists('cache');
        // hapus tabel cache_locks kalo ada di database
        Schema::dropIfExists('cache_locks');
    }
};
