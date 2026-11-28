<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake tipe return redirect buat ngarahin halaman
use Illuminate\Http\RedirectResponse;
// pake class request buat ngambil data user yang lagi login
use Illuminate\Http\Request;

// bikin controller buat ngirim ulang email verifikasi
class EmailVerificationNotificationController extends Controller
{
    // proses ngirim ulang link verifikasi ke email user
    public function store(Request $request): RedirectResponse
    {
        // cek dulu apakah email user udah terverifikasi sebelumnya
        if ($request->user()->hasVerifiedEmail()) {
            // kalo udah terverifikasi, langsung arahin ke halaman tujuan atau dashboard
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // kalo belum, kirim notifikasi link verifikasi baru ke email user
        $request->user()->sendEmailVerificationNotification();

        // balikin ke halaman sebelumnya sambil bawa status kalo link udah dikirim
        return back()->with('status', 'verification-link-sent');
    }
}
