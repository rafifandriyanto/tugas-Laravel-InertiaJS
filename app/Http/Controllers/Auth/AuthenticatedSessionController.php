<?php

// alamat namespace buat controller auth ini
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake form request khusus buat validasi dan proses login
use App\Http\Requests\Auth\LoginRequest;
// pake tipe return redirect buat ngarahin halaman setelah aksi
use Illuminate\Http\RedirectResponse;
// pake class request buat nangkep data sama session dari user
use Illuminate\Http\Request;
// pake facade auth buat urusan login sama logout
use Illuminate\Support\Facades\Auth;
// pake facade route buat ngecek nama route yang terdaftar
use Illuminate\Support\Facades\Route;
// pake inertia buat ngerender halaman vue
use Inertia\Inertia;
// pake tipe response punya inertia buat return halaman
use Inertia\Response;

// bikin controller buat ngurusin sesi login dan logout user
class AuthenticatedSessionController extends Controller
{
    // nampilin halaman form login ke user
    public function create(): Response
    {
        // render komponen vue Auth/Login sambil ngirim data props
        return Inertia::render('Auth/Login', [
            // ngecek apakah fitur/route lupa password aktif apa nggak
            'canResetPassword' => Route::has('password.request'),
            // ngambil pesan status dari session kalo ada (misal abis reset password)
            'status' => session('status'),
        ]);
    }

    // proses login pas user ngirim form
    public function store(LoginRequest $request): RedirectResponse
    {
        // jalanin proses cek email dan password lewat method authenticate di LoginRequest
        $request->authenticate();

        // bikin ulang id session biar lebih aman dari serangan session fixation
        $request->session()->regenerate();

        // arahin ke halaman tujuan sebelumnya, kalo ga ada langsung ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    // proses logout buat ngapus sesi login user
    public function destroy(Request $request): RedirectResponse
    {
        // keluarin user yang lagi login di guard web
        Auth::guard('web')->logout();

        // matiin dan bersihin semua data session yang lama
        $request->session()->invalidate();

        // bikin token csrf baru biar request selanjutnya tetep aman
        $request->session()->regenerateToken();

        // balikin user ke halaman utama setelah logout
        return redirect('/');
    }
}
