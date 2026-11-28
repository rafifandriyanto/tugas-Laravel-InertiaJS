<?php

// nentuin namespace buat controller ini
namespace App\Http\Controllers;

// narik model Post buat komunikasi ke tabel posts di database
use App\Models\Post;
// narik Inertia buat nampilin komponen halaman vue
use Inertia\Inertia;
// narik Request buat nangkep inputan form dari user
use Illuminate\Http\Request;
// narik facade Redirect buat ngarahin ulang halaman setelah aksi selesai
use Illuminate\Support\Facades\Redirect;

// controller buat ngurusin seluruh CRUD (tampil, tambah, ubah, hapus) postingan
class PostController extends Controller
{
    // nampilin halaman daftar semua postingan
    public function index()
    {
        // ngambil semua data postingan diurutin dari yang paling baru dibuat
        $posts = Post::latest()->get();
        // ngerender halaman vue Post/Index sambil ngirim data posts
        return Inertia::render('Post/Index', [
            // ngirim koleksi data postingan sebagai props ke komponen vue
            'posts' => $posts,
        ]);
    }

    // nampilin halaman form buat bikin postingan baru
    public function create()
    {
        // langsung ngerender komponen vue Post/Create
        return Inertia::render('Post/Create');
    }

    // nyimpen data postingan baru dari form ke database
    public function store(Request $request)
    {
        // ngecek input judul dan isi konten biar gak boleh kosong
        $request->validate([
            // field title wajib diisi
            'title'   => 'required',
            // field content wajib diisi
            'content' => 'required',
        ]);

        // masukin data baru ke tabel posts lewat mass assignment
        $post = Post::create([
            // ngisi kolom title dari input form
            'title'   => $request->title,
            // ngisi kolom content dari input form
            'content' => $request->content,
        ]);

        // ngecek kalo proses simpen datanya berhasil kebentuk
        if ($post) {
            // arahin balik ke halaman daftar postingan
            return Redirect::route('posts.index')
                // bawa flash message sukses buat ditampilin di halaman index
                ->with('message', 'Data Berhasil Disimpan!');
        }
    }

    // nampilin halaman form edit buat satu postingan berdasarkan id
    public function edit($id)
    {
        // nyari postingan pake id, kalo gak ketemu bakal langsung nampilin 404
        $post = Post::findOrFail($id);
        // ngerender komponen vue Post/Edit sambil ngoper data postingan yang mau diedit
        return Inertia::render('Post/Edit', [
            // ngirim data postingan lama biar inputan form langsung terisi data sebelumnya
            'post' => $post,
        ]);
    }

    // nyimpen perubahan data postingan yang udah diedit ke database
    public function update(Request $request, $id)
    {
        // ngecek ulang inputan form edit supaya judul sama isi gak kosong
        $request->validate([
            // judul wajib ada isinya
            'title'   => 'required',
            // isi konten wajib ada isinya
            'content' => 'required',
        ]);

        // nyari dulu postingan yang mau diubah, kalo gak ada lempar error 404
        $post = Post::findOrFail($id);
        // ngubah kolom title dan content di database sesuai data baru dari form
        $post->update([
            // update kolom title dengan input baru
            'title'   => $request->title,
            // update kolom content dengan input baru
            'content' => $request->content,
        ]);

        // arahin balik ke halaman daftar postingan
        return Redirect::route('posts.index')
            // bawa flash message kalo data berhasil diupdate
            ->with('message', 'Data Berhasil Diupdate!');
    }

    // ngehapus satu postingan dari database berdasarkan id
    public function destroy($id)
    {
        // nyari dulu postingan yang mau dihapus, kalo gak ketemu lempar 404
        $post = Post::findOrFail($id);
        // ngehapus record postingan tersebut dari tabel posts
        $post->delete();

        // arahin balik ke halaman daftar postingan
        return Redirect::route('posts.index')
            // bawa flash message kalo data berhasil dihapus
            ->with('message', 'Data Berhasil Dihapus!');
    }
}
