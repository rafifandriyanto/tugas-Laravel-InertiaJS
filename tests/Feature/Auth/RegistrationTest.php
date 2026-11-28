<?php

// taruh file ini di namespace Tests\Feature\Auth buat test pendaftaran user baru
namespace Tests\Feature\Auth;

// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test bawaan breeze buat ngecek halaman dan proses registrasi akun baru
class RegistrationTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman registrasi bisa ditampilin
    public function test_registration_screen_can_be_rendered(): void
    {
        // kirim request get ke halaman /register terus simpen responnya
        $response = $this->get('/register');

        // pastiin halaman register kebuka dengan status 200
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek user baru bisa daftar akun lewat halaman register
    public function test_new_users_can_register(): void
    {
        // kirim request post ke /register bawa data akun baru
        $response = $this->post('/register', [
            // isi nama lengkap user baru
            'name' => 'Test User',
            // isi alamat email user baru
            'email' => 'test@example.com',
            // isi password buat akun baru
            'password' => 'password',
            // isi konfirmasi password yang harus sama kayak password
            'password_confirmation' => 'password',
        ]);

        // pastiin user baru langsung ke-login abis berhasil daftar
        $this->assertAuthenticated();
        // pastiin abis daftar user diarahkan ke route dashboard
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
