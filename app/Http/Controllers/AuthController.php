<?php

// nentuin lokasi namespace controller ini
namespace App\Http\Controllers;

// narik model User buat bikin akun baru pas register
use App\Models\User;
// narik class Request buat baca inputan yang dikirim user dari form
use Illuminate\Http\Request;
// narik facade Auth buat ngecek status login, proses login, sama logout
use Illuminate\Support\Facades\Auth;
// narik facade Hash buat ngacak password sebelum disimpen ke database
use Illuminate\Support\Facades\Hash;
// narik RateLimiter buat ngebatesin percobaan login biar gak kena brute force
use Illuminate\Support\Facades\RateLimiter;
// narik helper Str buat ngolah string kayak ngubah huruf kecil pas bikin throttle key
use Illuminate\Support\Str;
// narik Validation Rules bawaan laravel (di-import buat kebutuhan aturan validasi)
use Illuminate\Validation\Rules;
// narik ValidationException buat ngelempar error validasi ke form frontend
use Illuminate\Validation\ValidationException;
// narik Inertia buat ngerender halaman komponen vue
use Inertia\Inertia;

// controller custom buat ngurusin halaman dan aksi login, register, sama logout
class AuthController extends Controller
{
    // nampilin halaman form login ke browser
    public function showLogin()
    {
        // ngecek kalo user sebenernya udah login
        if (Auth::check()) {
            // langsung arahin ke halaman daftar postingan aja, gak usah login lagi
            return redirect()->route('posts.index');
        }

        // ngerender halaman vue Auth/Login sambil ngirim status session kalo ada
        return Inertia::render('Auth/Login', [
            // ngambil pesan status dari session (misalnya abis reset password)
            'status' => session('status'),
        ]);
    }

    // proses ngecek email sama password pas user submit form login
    public function login(Request $request)
    {
        // ngecek dan validasi input email sama password biar sesuai aturan
        $credentials = $request->validate([
            // email wajib diisi, harus teks, dan formatnya email beneran
            'email'    => ['required', 'string', 'email'],
            // password wajib diisi dan bentuknya teks
            'password' => ['required', 'string'],
        ], [
            // pesan error custom kalo email kosong
            'email.required'    => 'Email wajib diisi.',
            // pesan error custom kalo format email ngaco
            'email.email'       => 'Format email tidak valid.',
            // pesan error custom kalo password kosong
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // bikin kunci unik gabungan email (huruf kecil) sama IP buat ngitung batas salah login
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // ngecek udah lewat batas 5 kali gagal login belum buat kunci ini
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            // ngambil sisa waktu tunggu dalam detik sampe boleh nyoba login lagi
            $seconds = RateLimiter::availableIn($throttleKey);
            // balikin error ke field email supaya user tau harus nunggu berapa detik
            throw ValidationException::withMessages([
                // pesan peringatan terlalu banyak percobaan login gagal
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // ngecek checkbox "ingat saya" dicentang atau enggak dari input form
        $remember = $request->boolean('remember');

        // nyoba cocokin email dan password ke database sekalian opsi remember me
        if (!Auth::attempt($credentials, $remember)) {
            // kalo gagal, catet nambah 1 percobaan gagal di rate limiter
            RateLimiter::hit($throttleKey);

            // lempar error validasi ke field email karena kombinasi login gak cocok
            throw ValidationException::withMessages([
                // pesan error kalo email atau password salah
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ]);
        }

        // kalo login berhasil, reset hitungan percobaan gagal buat kunci ini
        RateLimiter::clear($throttleKey);

        // bikin ulang ID session baru buat keamanan biar terhindar dari session fixation
        $request->session()->regenerate();

        // arahin ke halaman tujuan sebelumnya atau default ke daftar postingan
        return redirect()->intended(route('posts.index'))
            // bawa flash message sambutan buat ditampilin di frontend
            ->with('message', 'Selamat datang kembali, ' . Auth::user()->name . '!');
    }

    // nampilin halaman form daftar akun baru
    public function showRegister()
    {
        // ngecek kalo user udah dalam kondisi login
        if (Auth::check()) {
            // langsung lempar ke halaman posts biar gak daftar dua kali
            return redirect()->route('posts.index');
        }

        // ngerender komponen vue Auth/Register
        return Inertia::render('Auth/Register');
    }

    // proses bikin akun baru dari data yang dikirim di form register
    public function register(Request $request)
    {
        // ngecek semua inputan form register sesuai aturan yang ditentuin
        $request->validate([
            // nama wajib diisi, teks, maksimal 255 karakter
            'name'     => ['required', 'string', 'max:255'],
            // email wajib diisi, teks, huruf kecil, format email valid, maks 255, dan belum kepake di tabel users
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            // password wajib diisi, minimal 8 karakter, dan harus sama persis sama input konfirmasinya
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // pesan error custom kalo nama gak diisi
            'name.required'      => 'Nama lengkap wajib diisi.',
            // pesan error custom kalo email gak diisi
            'email.required'     => 'Alamat email wajib diisi.',
            // pesan error custom kalo email udah dipake akun lain
            'email.unique'       => 'Alamat email sudah terdaftar.',
            // pesan error custom kalo format email gak valid
            'email.email'        => 'Format alamat email tidak valid.',
            // pesan error custom kalo password kosong
            'password.required'  => 'Kata sandi wajib diisi.',
            // pesan error custom kalo password kurang dari 8 karakter
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            // pesan error custom kalo password sama konfirmasinya beda
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // nyimpen data user baru ke tabel users di database
        $user = User::create([
            // ngisi kolom name dari inputan request
            'name'     => $request->name,
            // ngisi kolom email dari inputan request
            'email'    => $request->email,
            // ngacak password pake Hash::make biar gak kesimpen dalam bentuk teks biasa
            'password' => Hash::make($request->password),
        ]);

        // langsung bikin user yang baru daftar ini masuk dalam status login
        Auth::login($user);

        // bikin ulang ID session setelah login biar aman
        $request->session()->regenerate();

        // arahin ke halaman daftar postingan
        return redirect()->route('posts.index')
            // kirim flash message tanda pendaftaran sukses
            ->with('message', 'Akun berhasil dibuat! Selamat datang di LaraPost.');
    }

    // proses keluar akun (logout) user yang lagi aktif
    public function logout(Request $request)
    {
        // ngapus status autentikasi user dari guard web
        Auth::guard('web')->logout();

        // matiin dan hapus data session yang lama biar gak bisa dipake lagi
        $request->session()->invalidate();
        // bikin token CSRF baru biar aman buat request berikutnya
        $request->session()->regenerateToken();

        // arahin balik ke halaman utama (home)
        return redirect()->route('home')
            // kirim flash message konfirmasi udah berhasil logout
            ->with('message', 'Anda telah berhasil keluar.');
    }
}
