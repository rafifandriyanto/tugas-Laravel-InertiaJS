<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake event Verified buat nandain email user udah resmi terverifikasi
use Illuminate\Auth\Events\Verified;
// pake request khusus dari laravel yang udah ngecek signature link verifikasi
use Illuminate\Foundation\Auth\EmailVerificationRequest;
// pake tipe return redirect buat ngarahin halaman abis verifikasi
use Illuminate\Http\RedirectResponse;

// bikin invokable controller buat nandain email user udah terverifikasi pas link diklik
class VerifyEmailController extends Controller
{
    // method invoke yang jalan pas user ngeklik link verifikasi dari email
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // cek dulu apakah email user sebenernya udah pernah diverifikasi sebelumnya
        if ($request->user()->hasVerifiedEmail()) {
            // kalo udah, langsung arahin ke dashboard sambil bawa parameter verified=1
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // tandain email user sebagai terverifikasi di database, kalo berhasil jalanin blok di bawah
        if ($request->user()->markEmailAsVerified()) {
            // picu event Verified biar listener lain tau user ini udah terverifikasi
            event(new Verified($request->user()));
        }

        // arahin ke halaman tujuan awal atau dashboard sambil nambahin tanda verified=1 di url
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
