<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake model User buat nyimpen akun baru ke database
use App\Models\User;
// pake event Registered buat ngasih tau sistem kalo ada user baru daftar
use Illuminate\Auth\Events\Registered;
// pake tipe return redirect buat mindahin halaman abis daftar
use Illuminate\Http\RedirectResponse;
// pake class request buat nangkep inputan dari form register
use Illuminate\Http\Request;
// pake facade auth biar user yang baru daftar langsung login
use Illuminate\Support\Facades\Auth;
// pake facade hash buat ngacak password sebelum masuk database
use Illuminate\Support\Facades\Hash;
// pake aturan validasi password bawaan laravel
use Illuminate\Validation\Rules;
// pake exception validasi buat penanganan error inputan
use Illuminate\Validation\ValidationException;
// pake inertia buat nampilin halaman vue
use Inertia\Inertia;
// pake tipe response dari inertia
use Inertia\Response;

// bikin controller buat ngurusin pendaftaran akun baru
class RegisteredUserController extends Controller
{
    // nampilin halaman form pendaftaran (register)
    public function create(): Response
    {
        // render komponen vue Auth/Register
        return Inertia::render('Auth/Register');
    }

    // proses nyimpen data pendaftaran user baru
    public function store(Request $request): RedirectResponse
    {
        // ngecek semua inputan dari form register sebelum disimpen
        $request->validate([
            // nama wajib diisi, harus teks, maksimal 255 karakter
            'name' => 'required|string|max:255',
            // email wajib diisi, huruf kecil, format email valid, max 255, dan belum kepake di tabel users
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            // password wajib diisi, harus sama kayak konfirmasi password, dan ngikutin aturan password laravel
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // bikin data user baru di database lewat model User
        $user = User::create([
            // masukin nama dari inputan form
            'name' => $request->name,
            // masukin email dari inputan form
            'email' => $request->email,
            // acak password pake hash sebelum disimpen biar aman
            'password' => Hash::make($request->password),
        ]);

        // picu event Registered biar sistem tau ada akun baru (misal buat kirim email verifikasi)
        event(new Registered($user));

        // langsung login-in user yang baru aja berhasil daftar
        Auth::login($user);

        // arahin user ke halaman dashboard
        return redirect(route('dashboard', absolute: false));
    }
}
