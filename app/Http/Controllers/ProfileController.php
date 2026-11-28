<?php

// nentuin namespace buat controller profil
namespace App\Http\Controllers;

// narik Form Request khusus yang udah ngevalidasi inputan update profil
use App\Http\Requests\ProfileUpdateRequest;
// narik model User buat type-hinting dan referensi model pengguna
use App\Models\User;
// narik interface MustVerifyEmail buat ngecek apakah user wajib verifikasi email
use Illuminate\Contracts\Auth\MustVerifyEmail;
// narik tipe return RedirectResponse buat method yang ngarahin halaman
use Illuminate\Http\RedirectResponse;
// narik class Request buat baca data request yang masuk
use Illuminate\Http\Request;
// narik facade Auth buat proses logout pas user hapus akun
use Illuminate\Support\Facades\Auth;
// narik facade Redirect buat mindahin halaman abis proses selesai
use Illuminate\Support\Facades\Redirect;
// narik Inertia buat ngerender halaman vue profil
use Inertia\Inertia;
// narik tipe return Response milik inertia
use Inertia\Response;

// controller bawaan breeze buat ngatur halaman edit profil dan hapus akun user
class ProfileController extends Controller
{
    // nampilin halaman form edit profil milik user yang lagi login
    public function edit(Request $request): Response
    {
        // ngerender halaman vue Profile/Edit sambil ngirim status verifikasi email
        return Inertia::render('Profile/Edit', [
            // ngecek apakah model user ini nerapin interface MustVerifyEmail (true/false)
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            // ngambil pesan status dari session kalo abis ngirim link verifikasi atau update
            'status' => session('status'),
        ]);
    }
    // nyimpen perubahan nama atau email user yang lolos validasi ProfileUpdateRequest
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // ngambil data user yang lagi login sekarang (tipenya model User)
        $user = $request->user();

        // ngisi atribut user pake data yang udah lolos validasi dari request
        $user->fill($request->validated());

        // ngecek apakah kolom email ikut diubah sama user
        if ($user->isDirty('email')) {
            // kalo email diganti, kosongin tanggal verifikasinya biar verifikasi ulang pake email baru
            $user->email_verified_at = null;
        }

        // nyimpen semua perubahan profil user ke database
        $user->save();

        // arahin balik ke halaman edit profil
        return Redirect::route('profile.edit');
    }

    // ngehapus akun user permanen setelah konfirmasi password
    public function destroy(Request $request): RedirectResponse
    {
        // ngecek password yang dimasukin wajib diisi dan harus cocok sama password akun sekarang
        $request->validate([
            // aturan password wajib isi dan dicek pake rule current_password bawaan laravel
            'password' => ['required', 'current_password'],
        ]);

        // ngambil data user yang lagi login buat dihapus nanti (tipenya model User)
        $user = $request->user();

        // keluarin dulu user dari sesi login sebelum datanya dihapus
        Auth::logout();

        // hapus record akun user ini dari database
        $user->delete();

        // matiin session lama biar gak bisa dipake lagi
        $request->session()->invalidate();
        // bikin token CSRF baru buat keamanan request selanjutnya
        $request->session()->regenerateToken();

        // arahin user balik ke halaman depan (/)
        return Redirect::to('/');
    }
}
