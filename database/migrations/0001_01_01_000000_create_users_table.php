<?php

// panggil class dasar Migration buat bikin struktur tabel database
use Illuminate\Database\Migrations\Migration;
// panggil Blueprint buat ngatur kolom-kolom di dalem tabel
use Illuminate\Database\Schema\Blueprint;
// panggil facade Schema buat ngejalanin perintah bikin atau hapus tabel
use Illuminate\Support\Facades\Schema;

// class migration anonim buat bikin tabel user, token reset password, sama session
return new class extends Migration
{
    // jalanin migration pas perintah php artisan migrate dipanggil
    public function up(): void
    {
        // bikin tabel users buat nyimpen data akun pengguna
        Schema::create('users', function (Blueprint $table) {
            // bikin kolom id auto increment sebagai primary key
            $table->id();
            // bikin kolom name bertipe string buat nama lengkap user
            $table->string('name');
            // bikin kolom email bertipe string yang gak boleh kembar
            $table->string('email')->unique();
            // bikin kolom waktu verifikasi email, boleh kosong kalo belum verifikasi
            $table->timestamp('email_verified_at')->nullable();
            // bikin kolom password buat nyimpen password yang udah di-hash
            $table->string('password');
            // bikin kolom remember_token buat fitur ingat saya pas login
            $table->rememberToken();
            // bikin kolom created_at dan updated_at sekaligus
            $table->timestamps();
        });

        // bikin tabel password_reset_tokens buat nyimpen token pas user lupa password
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // pake email sebagai primary key biar satu email punya satu token aktif
            $table->string('email')->primary();
            // bikin kolom token buat nyimpen kode verifikasi reset password
            $table->string('token');
            // bikin kolom created_at buat catet kapan token dibikin, boleh kosong
            $table->timestamp('created_at')->nullable();
        });

        // bikin tabel sessions buat nyimpen data sesi login user di database
        Schema::create('sessions', function (Blueprint $table) {
            // bikin kolom id bertipe string sebagai primary key session
            $table->string('id')->primary();
            // bikin kolom user_id yang di-index dan boleh kosong buat tamu
            $table->foreignId('user_id')->nullable()->index();
            // bikin kolom ip_address max 45 karakter buat nyatet IP user
            $table->string('ip_address', 45)->nullable();
            // bikin kolom user_agent buat nyatet browser atau perangkat user
            $table->text('user_agent')->nullable();
            // bikin kolom payload buat nyimpen data session dalam ukuran teks panjang
            $table->longText('payload');
            // bikin kolom last_activity yang di-index buat nyatet aktivitas terakhir user
            $table->integer('last_activity')->index();
        });
    }

    // batalin migration kalo di-rollback pake php artisan migrate:rollback
    public function down(): void
    {
        // hapus tabel users kalo tabelnya ada
        Schema::dropIfExists('users');
        // hapus tabel password_reset_tokens kalo tabelnya ada
        Schema::dropIfExists('password_reset_tokens');
        // hapus tabel sessions kalo tabelnya ada
        Schema::dropIfExists('sessions');
    }
};
