<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake tipe return redirect buat balik ke halaman sebelumnya
use Illuminate\Http\RedirectResponse;
// pake class request buat nangkep inputan user
use Illuminate\Http\Request;
// pake facade hash buat nge-enkripsi password baru
use Illuminate\Support\Facades\Hash;
// pake aturan validasi password bawaan laravel
use Illuminate\Validation\Rules\Password;

// bikin controller buat ganti password user dari halaman profil
class PasswordController extends Controller
{
    // proses ngubah password user yang lagi login
    public function update(Request $request): RedirectResponse
    {
        // ngecek inputan password lama sama password baru dan nyimpen hasil validasinya
        $validated = $request->validate([
            // password sekarang wajib diisi dan harus cocok sama password user di database
            'current_password' => ['required', 'current_password'],
            // password baru wajib diisi, sesuai aturan keamanan laravel, dan sama kayak konfirmasinya
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // update kolom password milik user yang lagi login di database
        $request->user()->update([
            // hash password baru sebelum disimpen biar ga kebaca polos
            'password' => Hash::make($validated['password']),
        ]);

        // balikin user ke halaman sebelumnya setelah password berhasil diganti
        return back();
    }
}
