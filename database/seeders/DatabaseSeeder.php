<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'demo@larapost.dev'],
            [
                'name' => 'Alex Pratama',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (Post::count() <= 1) {
            Post::firstOrCreate(
                ['title' => 'Membangun Arsitektur Modern dengan Laravel 12 & Inertia.js'],
                [
                    'content' => 'Inertia.js menjembatani kesenjangan antara Single Page Application (SPA) modern berbasis Vue 3 dan backend kuat seperti Laravel tanpa perlu repot membangun dan memelihara REST API terpisah. Pendekatan monolit modern ini memberikan pengalaman navigasi instan tanpa refresh halaman.'
                ]
            );

            Post::firstOrCreate(
                ['title' => 'Keunggulan Tailwind CSS v4 untuk Skalabilitas Desain Sistem'],
                [
                    'content' => 'Dengan arsitektur CSS-first dan dukungan modern CSS variables, Tailwind CSS v4 menghadirkan build super instan dan konsistensi warna antarmuka baik dalam mode terang maupun gelap secara natural.'
                ]
            );

            Post::firstOrCreate(
                ['title' => 'Tips Produktivitas Menggunakan Composition API & Reaktifitas Vue 3'],
                [
                    'content' => 'Memanfaatkan script setup dan computed state di Vue 3 memungkinkan developer menulis komponen yang ringkas, mudah dibaca, dan modular tanpa boilerplate berlebih.'
                ]
            );
        }
    }
}
