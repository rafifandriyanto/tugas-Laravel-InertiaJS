<?php

// nentuin namespace model di folder app/Models
namespace App\Models;

// interface wajib verifikasi email yang masih dikomentarin (aktifin kalo mau wajibin verifikasi email)
// use Illuminate\Contracts\Auth\MustVerifyEmail;
// narik trait HasFactory biar model user bisa pake factory buat bikin data dummy/testing
use Illuminate\Database\Eloquent\Factories\HasFactory;
// narik class Authenticatable sebagai induk model yang punya fitur login/autentikasi
use Illuminate\Foundation\Auth\User as Authenticatable;
// narik trait Notifiable biar user bisa dikirimin notifikasi (kayak email reset password)
use Illuminate\Notifications\Notifiable;

// model User yang nyambung ke tabel users dan dipake buat sistem login laravel
class User extends Authenticatable
{
    // pake trait factory buat seeding/testing sama trait notifiable buat kirim notif
    use HasFactory, Notifiable;

    // daftar kolom yang boleh diisi langsung lewat User::create() atau update()
    protected $fillable = [
        // kolom nama lengkap user
        'name',
        // kolom alamat email user
        'email',
        // kolom password user
        'password',
    ];

    // daftar kolom sensitif yang disembunyiin pas data user diubah jadi array atau JSON (misal dikirim ke inertia/vue)
    protected $hidden = [
        // jangan ikut ngirim password ke response JSON/frontend
        'password',
        // jangan ikut ngirim remember_token ke response JSON/frontend
        'remember_token',
    ];

    // ngatur konversi tipe data kolom pas dibaca atau disimpen sama eloquent
    protected function casts(): array
    {
        // balikin aturan tipe data buat masing-masing kolom
        return [
            // ubah kolom email_verified_at jadi objek tanggal/waktu (Carbon)
            'email_verified_at' => 'datetime',
            // acak nilai password pake hash tiap kali diset ke model ini
            'password' => 'hashed',
        ];
    }
}
