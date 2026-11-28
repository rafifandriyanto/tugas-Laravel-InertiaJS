<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake event PasswordReset buat ngasih tau sistem kalo password baru aja direset
use Illuminate\Auth\Events\PasswordReset;
// pake tipe return redirect buat mindahin halaman
use Illuminate\Http\RedirectResponse;
// pake class request buat nangkep inputan dari form
use Illuminate\Http\Request;
// pake facade hash buat nge-enkripsi password baru
use Illuminate\Support\Facades\Hash;
// pake facade password buat proses reset password bawaan laravel
use Illuminate\Support\Facades\Password;
// pake helper string buat bikin remember token acak
use Illuminate\Support\Str;
// pake aturan validasi password bawaan laravel
use Illuminate\Validation\Rules;
// pake exception validasi kalo proses reset password gagal
use Illuminate\Validation\ValidationException;
// pake inertia buat nampilin halaman vue
use Inertia\Inertia;
// pake tipe response dari inertia
use Inertia\Response;

// bikin controller buat ngurusin form dan simpan password baru pas reset
class NewPasswordController extends Controller
{
    // nampilin halaman form buat masukin password baru
    public function create(Request $request): Response
    {
        // render komponen vue Auth/ResetPassword sambil ngirim email sama token dari url
        return Inertia::render('Auth/ResetPassword', [
            // ngambil email dari query parameter request
            'email' => $request->email,
            // ngambil token reset password dari parameter route
            'token' => $request->route('token'),
        ]);
    }

    // proses nyimpen password baru yang dikirim user
    public function store(Request $request): RedirectResponse
    {
        // ngecek inputan form reset password dulu sebelum diproses
        $request->validate([
            // token reset password wajib ada
            'token' => 'required',
            // email wajib diisi dan formatnya harus email beneran
            'email' => 'required|email',
            // password wajib diisi, harus cocok sama konfirmasinya, dan ngikutin standar keamanan laravel
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // jalanin proses reset password, kalo token dan email cocok bakal ngejalanin callback update user
        $status = Password::reset(
            // ngambil cuma field yang dibutuhin buat proses reset password
            $request->only('email', 'password', 'password_confirmation', 'token'),
            // fungsi callback buat ngubah data password si user pas tokennya valid
            function ($user) use ($request) {
                // isi paksa kolom password sama remember_token punya model user
                $user->forceFill([
                    // acak password baru pake hash biar aman di database
                    'password' => Hash::make($request->password),
                    // bikin remember token baru sepanjang 60 karakter acak
                    'remember_token' => Str::random(60),
                // simpen data user yang udah diubah ke database
                ])->save();

                // picu event PasswordReset biar listener lain tau password udah diganti
                event(new PasswordReset($user));
            }
        );

        // cek apakah status hasil resetnya berhasil
        if ($status == Password::PASSWORD_RESET) {
            // kalo sukses, arahin balik ke halaman login sambil bawa pesan status berhasil
            return redirect()->route('login')->with('status', __($status));
        }

        // kalo gagal, lempar error validasi biar muncul pesan errornya di form
        throw ValidationException::withMessages([
            // tampilin pesan gagal reset password di field email
            'email' => [trans($status)],
        ]);
    }
}
