<?php

// lokasi namespace buat class seeder database
namespace Database\Seeders;

// panggil model User biar bisa isi data user awal ke tabel users
use App\Models\User;
// panggil model Post biar bisa isi data postingan awal ke tabel posts
use App\Models\Post;
// panggil class dasar Seeder dari Laravel
use Illuminate\Database\Seeder;
// panggil facade Hash buat enkripsi password user contoh
use Illuminate\Support\Facades\Hash;

// class utama buat ngisi data awal ke database pas db:seed dijalankan
class DatabaseSeeder extends Seeder
{
    // jalanin proses isi data awal ke tabel users dan posts
    public function run(): void
    {
        // bikin akun demo kalo email demo@larapost.dev belum ada di tabel users
        User::firstOrCreate(
            // patokan pengecekan berdasarkan email biar gak dobel
            ['email' => 'demo@larapost.dev'],
            // data lengkap akun demo yang bakal diisi kalo belum ada
            [
                // isi nama akun demo
                'name' => 'Alex Pratama',
                // isi password yang udah di-hash dari teks 'password'
                'password' => Hash::make('password'),
                // tandai email udah langsung terverifikasi sekarang
                'email_verified_at' => now(),
            ]
        );

        // cek kalo jumlah postingan masih 1 atau kosong, isi data post contoh
        if (Post::count() <= 1) {
            // bikin postingan pertama kalo judulnya belum ada di database
            Post::firstOrCreate(
                // patokan pengecekan pake judul artikel pertama
                ['title' => 'Membangun Arsitektur Modern dengan Laravel 12 & Inertia.js'],
                // isi konten buat artikel pertama
                [
                    // teks isi artikel tentang Laravel 12 dan Inertia.js
                    'content' => 'Inertia.js menjembatani kesenjangan antara Single Page Application (SPA) modern berbasis Vue 3 dan backend kuat seperti Laravel tanpa perlu repot membangun dan memelihara REST API terpisah. Pendekatan monolit modern ini memberikan pengalaman navigasi instan tanpa refresh halaman.'
                ]
            );

            // bikin postingan kedua kalo judulnya belum ada di database
            Post::firstOrCreate(
                // patokan pengecekan pake judul artikel kedua
                ['title' => 'Keunggulan Tailwind CSS v4 untuk Skalabilitas Desain Sistem'],
                // isi konten buat artikel kedua
                [
                    // teks isi artikel tentang Tailwind CSS v4
                    'content' => 'Dengan arsitektur CSS-first dan dukungan modern CSS variables, Tailwind CSS v4 menghadirkan build super instan dan konsistensi warna antarmuka baik dalam mode terang maupun gelap secara natural.'
                ]
            );

            // bikin postingan ketiga kalo judulnya belum ada di database
            Post::firstOrCreate(
                // patokan pengecekan pake judul artikel ketiga
                ['title' => 'Tips Produktivitas Menggunakan Composition API & Reaktifitas Vue 3'],
                // isi konten buat artikel ketiga
                [
                    // teks isi artikel tentang Vue 3 Composition API
                    'content' => 'Memanfaatkan script setup dan computed state di Vue 3 memungkinkan developer menulis komponen yang ringkas, mudah dibaca, dan modular tanpa boilerplate berlebih.'
                ]
            );
        }
    }
}
