<?php

// panggil class Migration buat bikin tabel posts
use Illuminate\Database\Migrations\Migration;
// panggil Blueprint buat ngatur kolom yang ada di tabel posts
use Illuminate\Database\Schema\Blueprint;
// panggil facade Schema buat bikin sama hapus tabel
use Illuminate\Support\Facades\Schema;

// class migration buat bikin tabel posts di database
return new class extends Migration
{
    // jalanin pembuatan tabel posts pas migrate dieksekusi
    public function up(): void
    {
        // bikin tabel posts buat nyimpen data artikel atau postingan
        Schema::create('posts', function (Blueprint $table) {
            // bikin kolom id auto increment sebagai primary key
            $table->id();
            // bikin kolom title bertipe string buat judul postingan
            $table->string('title');
            // bikin kolom content bertipe text buat isi konten postingan yang panjang
            $table->text('content');
            // bikin kolom created_at dan updated_at buat nyatet waktu bikin dan edit
            $table->timestamps();
        });
    }

    // hapus tabel posts kalo migration dibatalin atau di-rollback
    public function down(): void
    {
        // hapus tabel posts kalo tabelnya udah ada
        Schema::dropIfExists('posts');
    }
};
