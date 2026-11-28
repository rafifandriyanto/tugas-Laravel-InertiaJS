<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake tipe return redirect kalo user udah verifikasi
use Illuminate\Http\RedirectResponse;
// pake class request buat ngecek user yang lagi login
use Illuminate\Http\Request;
// pake inertia buat ngerender halaman vue
use Inertia\Inertia;
// pake tipe response dari inertia
use Inertia\Response;

// bikin invokable controller buat nampilin halaman pengingat verifikasi email
class EmailVerificationPromptController extends Controller
{
    // method invoke yang langsung jalan pas route ini dipanggil
    public function __invoke(Request $request): RedirectResponse|Response
    {
        // cek apakah email user udah terverifikasi apa belum
        return $request->user()->hasVerifiedEmail()
                    // kalo udah verifikasi, arahin ke halaman tujuan atau dashboard
                    ? redirect()->intended(route('dashboard', absolute: false))
                    // kalo belum, tampilin halaman Auth/VerifyEmail sambil ngirim status session
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
