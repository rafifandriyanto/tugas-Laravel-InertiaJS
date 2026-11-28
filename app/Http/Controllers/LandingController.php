<?php

// nentuin namespace controller ini di app/Http/Controllers
namespace App\Http\Controllers;

// narik model Post buat ngambil data postingan terbaru sama jumlah totalnya
use App\Models\Post;
// narik model User buat ngitung total pengguna yang udah terdaftar
use App\Models\User;
// narik class Request (bawaan controller kalo nanti butuh baca request)
use Illuminate\Http\Request;
// narik Inertia buat ngerender halaman vue Landing
use Inertia\Inertia;

// controller buat ngurusin data yang ditampilin di halaman landing page
class LandingController extends Controller
{
    // method utama buat nampilin halaman depan aplikasi
    public function index()
    {
        // ngambil maksimal 3 postingan paling baru lalu ubah format datanya pake map
        $posts = Post::latest()->take(3)->get()->map(function ($post) {
            // balikin array ringkas berisi field yang dibutuhin tampilan depan aja
            return [
                // id unik postingan
                'id'         => $post->id,
                // judul postingan
                'title'      => $post->title,
                // isi konten postingan
                'content'    => $post->content,
                // format tanggal dibuat ke standar ISO biar gampang dibaca javascript, kalo kosong isi null
                'created_at' => $post->created_at ? $post->created_at->toISOString() : null,
            ];
        });

        // nyiapin data statistik singkat buat ditampilin di kartu info landing page
        $stats = [
            // ngitung semua jumlah postingan yang ada di database
            'total_posts'  => Post::count(),
            // ngitung semua jumlah user yang terdaftar
            'total_users'  => User::count(),
            // ngambil waktu update postingan terakhir dalam format ramah (misal "2 jam lalu"), kalo belum ada default 'Hari ini'
            'last_updated' => Post::latest()->value('updated_at')?->diffForHumans() ?? 'Hari ini',
        ];

        // ngerender komponen vue Landing sambil ngoper props recentPosts dan stats
        return Inertia::render('Landing', [
            // kirim daftar 3 postingan terbaru ke frontend
            'recentPosts' => $posts,
            // kirim data statistik ringkas ke frontend
            'stats'       => $stats,
        ]);
    }
}
