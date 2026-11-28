<?php

// alamat namespace buat form request auth
namespace App\Http\Requests\Auth;

// pake event Lockout pas user kebanyakan gagal login terus kena blokir sementara
use Illuminate\Auth\Events\Lockout;
// pake interface ValidationRule buat type hint aturan validasi
use Illuminate\Contracts\Validation\ValidationRule;
// pake class FormRequest sebagai induk request validasi laravel
use Illuminate\Foundation\Http\FormRequest;
// pake facade auth buat ngecek kecocokan email sama password
use Illuminate\Support\Facades\Auth;
// pake RateLimiter buat ngebatesin jumlah percobaan login biar ga kena brute force
use Illuminate\Support\Facades\RateLimiter;
// pake helper Str buat ngolah teks email pas bikin throttle key
use Illuminate\Support\Str;
// pake exception validasi kalo login gagal atau kena limit
use Illuminate\Validation\ValidationException;

// bikin form request khusus buat validasi dan proses login user
class LoginRequest extends FormRequest
{
    // nentuin siapa aja yang boleh ngirim request login ini
    public function authorize(): bool
    {
        // balikin true biar semua pengunjung boleh nyoba login
        return true;
    }

    // daftar aturan validasi buat form login
    public function rules(): array
    {
        // balikin array aturan validasi buat email sama password
        return [
            // email wajib diisi, berupa teks, dan formatnya harus email yang bener
            'email' => ['required', 'string', 'email'],
            // password wajib diisi dan harus berupa teks
            'password' => ['required', 'string'],
        ];
    }

    // proses nyocokin email dan password yang dikirim user
    public function authenticate(): void
    {
        // cek dulu apakah user lagi kena limit gara-gara kebanyakan gagal login
        $this->ensureIsNotRateLimited();

        // coba login pake email dan password, sekalian baca opsi remember me dari checkbox
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // kalo gagal login, catet satu kali percobaan gagal ke rate limiter
            RateLimiter::hit($this->throttleKey());

            // lempar error validasi ke form login
            throw ValidationException::withMessages([
                // tampilin pesan login gagal bawaan laravel di field email
                'email' => trans('auth.failed'),
            ]);
        }

        // kalo login berhasil, reset itungan gagal login di rate limiter
        RateLimiter::clear($this->throttleKey());
    }

    // ngecek biar percobaan login ga melebihi batas maksimal
    public function ensureIsNotRateLimited(): void
    {
        // cek apakah jumlah percobaan gagal udah nyampe 5 kali atau belum
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            // kalo belum nyampe 5 kali, langsung keluar dari fungsi biar proses login lanjut
            return;
        }

        // kalo udah 5 kali gagal, picu event Lockout buat nandain akun lagi dikunci sementara
        event(new Lockout($this));

        // itung sisa waktu tunggu dalam detik sampe user boleh nyoba login lagi
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // lempar error validasi buat ngasih tau user harus nunggu dulu
        throw ValidationException::withMessages([
            // tempelin pesan error throttle ke field email pake terjemahan auth.throttle
            'email' => trans('auth.throttle', [
                // kirim sisa detik tunggu ke teks pesan error
                'seconds' => $seconds,
                // kirim sisa menit tunggu yang dibuletin ke atas ke teks pesan error
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    // bikin kunci unik buat ngelacak percobaan login tiap user
    public function throttleKey(): string
    {
        // gabungin email versi huruf kecil dan bersih dari karakter aneh sama alamat ip user
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
