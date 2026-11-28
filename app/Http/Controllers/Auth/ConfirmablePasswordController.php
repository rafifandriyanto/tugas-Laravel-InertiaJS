<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller utama bawaan laravel
use App\Http\Controllers\Controller;
// pake tipe return redirect buat mindahin halaman
use Illuminate\Http\RedirectResponse;
// pake class request buat baca inputan sama session user
use Illuminate\Http\Request;
// pake facade auth buat ngecek kecocokan password user
use Illuminate\Support\Facades\Auth;
// pake exception validasi kalo password yang dimasukin salah
use Illuminate\Validation\ValidationException;
// pake inertia buat nampilin halaman vue
use Inertia\Inertia;
// pake tipe response dari inertia
use Inertia\Response;

// bikin controller buat konfirmasi ulang password sebelum masuk fitur sensitif
class ConfirmablePasswordController extends Controller
{
    // nampilin halaman konfirmasi password
    public function show(): Response
    {
        // render komponen vue Auth/ConfirmPassword
        return Inertia::render('Auth/ConfirmPassword');
    }

    // ngecek password yang dimasukin user pas konfirmasi
    public function store(Request $request): RedirectResponse
    {
        // cek apakah email user yang login cocok sama password yang diketik
        if (! Auth::guard('web')->validate([
            // ngambil email dari akun user yang lagi login
            'email' => $request->user()->email,
            // ngambil password yang diketik user dari form
            'password' => $request->password,
        ])) {
            // kalo salah, lempar error validasi ke form
            throw ValidationException::withMessages([
                // kasih pesan error standar password salah di field password
                'password' => __('auth.password'),
            ]);
        }

        // simpen waktu sekarang ke session buat nandain password udah dikonfirmasi
        $request->session()->put('auth.password_confirmed_at', time());

        // arahin ke halaman tujuan awal, kalo ga ada lempar ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
