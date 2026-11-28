<?php

// taruh file ini di namespace Tests\Feature\Auth buat test fitur login dan logout
namespace Tests\Feature\Auth;

// pake model User biar bisa bikin akun dummy pas test
use App\Models\User;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test bawaan breeze buat ngecek proses autentikasi user
class AuthenticationTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman login bisa ditampilin
    public function test_login_screen_can_be_rendered(): void
    {
        // kirim request get ke url /login terus simpen responnya
        $response = $this->get('/login');

        // pastiin status respon http nya 200 alias berhasil
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek user bisa login pake form login
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

        // pastiin user udah terautentikasi alias berhasil login
        $this->assertAuthenticated();
        // pastiin abis login user diarahkan ke route dashboard
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    // fungsi test buat ngecek user ga bisa login kalo passwordnya salah
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request post ke /login pake password yang salah
        $this->post('/login', [
            // masukin email yang bener dari user dummy
            'email' => $user->email,
            // sengaja masukin password salah biar gagal login
            'password' => 'wrong-password',
        ]);

        // pastiin user tetep berstatus guest alias belum login
        $this->assertGuest();
    }

    // fungsi test buat ngecek user bisa logout dari aplikasi
    public function test_users_can_logout(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // login sebagai user dummy terus kirim request post ke /logout
        $response = $this->actingAs($user)->post('/logout');

        // pastiin user udah keluar dari sesi login alias jadi guest
        $this->assertGuest();
        // pastiin abis logout user diarahkan ke halaman utama /
        $response->assertRedirect('/');
    }
}
