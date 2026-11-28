<?php

// taruh file ini di namespace Tests\Feature buat feature test autentikasi
namespace Tests\Feature;

// pake model User biar bisa bikin data user dummy pas test
use App\Models\User;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test buat ngecek alur login, logout, halaman awal, sama register
class AuthTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman login bisa kebuka normal
    public function test_login_screen_can_be_rendered(): void
    {
        // kirim request get ke halaman /login terus simpen responnya
        $response = $this->get('/login');

        // pastiin halaman login kebuka dengan status 200
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek user bisa login lewat form login
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request post ke /login bawa data email sama password
        $response = $this->post('/login', [
            // masukin email dari user dummy yang barusan dibikin
            'email' => $user->email,
            // masukin password default bawaan factory user
            'password' => 'password',
        ]);

        // pastiin status user sekarang udah terautentikasi alias berhasil login
        $this->assertAuthenticated();
        // pastiin abis login langsung diarahkan ke route dashboard
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    // fungsi test buat ngecek user gagal login kalo passwordnya salah
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request post ke /login pake password yang salah
        $this->post('/login', [
            // masukin email yang bener dari user dummy
            'email' => $user->email,
            // sengaja masukin password yang salah
            'password' => 'wrong-password',
        ]);

        // pastiin user tetep jadi guest alias gagal login
        $this->assertGuest();
    }

    // fungsi test buat ngecek user yang udah login bisa logout
    public function test_users_can_logout(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // login sebagai user dummy terus kirim request post ke /logout
        $response = $this->actingAs($user)->post('/logout');

        // pastiin status user balik jadi guest alias udah keluar
        $this->assertGuest();
        // pastiin abis logout diarahkan ke route home
        $response->assertRedirect(route('home'));
    }

    // fungsi test buat ngecek landing page utama bisa kebuka normal
    public function test_landing_page_renders_successfully(): void
    {
        // kirim request get ke halaman utama / terus simpen responnya
        $response = $this->get('/');

        // pastiin status responnya 200 alias berhasil
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek user baru bisa daftar akun lewat /register
    public function test_user_can_register_new_account(): void
    {
        // kirim request post ke /register bawa data pendaftaran user baru
        $response = $this->post('/register', [
            // isi nama user baru buat registrasi
            'name' => 'Kreator Baru',
            // bikin email unik pake uniqid biar ga bentrok sama test lain
            'email' => 'newuser_' . uniqid() . '@example.com',
            // isi password buat akun baru
            'password' => 'password',
            // isi konfirmasi password yang sama persis kayak password
            'password_confirmation' => 'password',
        ]);

        // pastiin user baru langsung ke-login abis daftar
        $this->assertAuthenticated();
        // pastiin abis daftar langsung diarahkan ke route dashboard
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
