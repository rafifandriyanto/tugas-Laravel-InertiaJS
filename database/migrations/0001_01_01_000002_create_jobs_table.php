<?php

// panggil class Migration buat bikin struktur tabel antrean
use Illuminate\Database\Migrations\Migration;
// panggil Blueprint buat nentuin kolom-kolom tabel
use Illuminate\Database\Schema\Blueprint;
// panggil facade Schema buat eksekusi bikin atau hapus tabel
use Illuminate\Support\Facades\Schema;

// class migration buat bikin tabel antrean background job di database
return new class extends Migration
{
    // jalanin pembuatan tabel jobs, job_batches, dan failed_jobs
    public function up(): void
    {
        // bikin tabel jobs buat nyimpen daftar antrean tugas yang lagi ngantri
        Schema::create('jobs', function (Blueprint $table) {
            // bikin kolom id auto increment sebagai primary key
            $table->id();
            // bikin kolom queue yang di-index buat nama jalur antreannya
            $table->string('queue')->index();
            // bikin kolom payload buat nyimpen data tugas yang bakal dijalankan
            $table->longText('payload');
            // bikin kolom attempts buat ngitung udah berapa kali tugas dicoba jalan
            $table->unsignedTinyInteger('attempts');
            // bikin kolom reserved_at buat nyatet waktu tugas lagi dikerjain worker
            $table->unsignedInteger('reserved_at')->nullable();
            // bikin kolom available_at buat nyatet kapan tugas boleh mulai dikerjain
            $table->unsignedInteger('available_at');
            // bikin kolom created_at buat nyatet waktu tugas masuk antrean
            $table->unsignedInteger('created_at');
        });

        // bikin tabel job_batches buat ngelompokin beberapa job jadi satu batch
        Schema::create('job_batches', function (Blueprint $table) {
            // bikin kolom id bertipe string sebagai primary key batch
            $table->string('id')->primary();
            // bikin kolom name buat nama kelompok batch-nya
            $table->string('name');
            // bikin kolom total_jobs buat nyatet jumlah semua job di batch ini
            $table->integer('total_jobs');
            // bikin kolom pending_jobs buat nyatet sisa job yang belum kelar
            $table->integer('pending_jobs');
            // bikin kolom failed_jobs buat nyatet jumlah job yang gagal
            $table->integer('failed_jobs');
            // bikin kolom failed_job_ids buat nyimpen daftar id job yang gagal
            $table->longText('failed_job_ids');
            // bikin kolom options buat nyimpen pengaturan tambahan batch, boleh kosong
            $table->mediumText('options')->nullable();
            // bikin kolom cancelled_at buat nyatet kapan batch dibatalin kalo ada
            $table->integer('cancelled_at')->nullable();
            // bikin kolom created_at buat waktu batch dibikin
            $table->integer('created_at');
            // bikin kolom finished_at buat waktu batch selesai, boleh kosong
            $table->integer('finished_at')->nullable();
        });

        // bikin tabel failed_jobs buat nampung job yang error pas dijalankan
        Schema::create('failed_jobs', function (Blueprint $table) {
            // bikin kolom id auto increment sebagai primary key
            $table->id();
            // bikin kolom uuid yang unik sebagai kode pengenal job gagal
            $table->string('uuid')->unique();
            // bikin kolom connection buat nyatet nama koneksi queue yang dipake
            $table->text('connection');
            // bikin kolom queue buat nyatet nama antrean tempat job itu gagal
            $table->text('queue');
            // bikin kolom payload buat nyimpen isi data job yang gagal tadi
            $table->longText('payload');
            // bikin kolom exception buat nyimpen detail pesan error-nya
            $table->longText('exception');
            // bikin kolom failed_at yang otomatis keisi waktu sekarang pas gagal
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    // hapus semua tabel queue kalo migration di-rollback
    public function down(): void
    {
        // hapus tabel jobs kalo ada
        Schema::dropIfExists('jobs');
        // hapus tabel job_batches kalo ada
        Schema::dropIfExists('job_batches');
        // hapus tabel failed_jobs kalo ada
        Schema::dropIfExists('failed_jobs');
    }
};
