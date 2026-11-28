<?php

// alamat namespace buat controller auth
namespace App\Http\Controllers\Auth;

// pake controller induk bawaan laravel
use App\Http\Controllers\Controller;
// pake tipe return redirect buat ngarahin halaman
use Illuminate\Http\RedirectResponse;
// pake class request buat nangkep email dari form
use Illuminate\Http\Request;
// pake facade password buat ngirim link reset password
use Illuminate\Support\Facades\Password;
// pake exception validasi kalo gagal ngirim link reset
use Illuminate\Validation\ValidationException;
// pake inertia buat nampilin halaman vue
use Inertia\Inertia;
// pake tipe response dari inertia
use Inertia\Response;

// bikin controller buat ngurusin permintaan link reset password (lupa password)
class PasswordResetLinkController extends Controller
{
    // nampilin halaman form lupa password
    public function create(): Response
    {
        // render komponen vue Auth/ForgotPassword sambil ngirim status session kalo ada
        return Inertia::render('Auth/ForgotPassword', [
            // ngambil pesan status dari session buat ditampilin ke user
            'status' => session('status'),
        ]);
    }

    // proses ngirim link reset password ke email yang dimasukin user
    public function store(Request $request): RedirectResponse
    {
        // ngecek inputan email dari form dulu
        $request->validate([
            // email wajib diisi dan harus pake format email yang bener
            'email' => 'required|email',
        ]);

        // coba kirim link reset password ke email user lalu simpen hasilnya ke $status
        $status = Password::sendResetLink(
            // ngambil field email aja dari request
            $request->only('email')
        );

        // cek apakah link reset password berhasil dikirim
        if ($status == Password::RESET_LINK_SENT) {
            // kalo berhasil, balikin ke halaman sebelumnya sambil bawa pesan sukses
            return back()->with('status', __($status));
        }

        // kalo gagal ngirim, lempar error validasi biar muncul di input email
        throw ValidationException::withMessages([
            // tampilin pesan error sesuai status kegagalan di field email
            'email' => [trans($status)],
        ]);
    }
}
