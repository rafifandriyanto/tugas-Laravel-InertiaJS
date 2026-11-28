<?php

// taruh file ini di namespace Tests\Feature\Auth buat test konfirmasi password
namespace Tests\Feature\Auth;

// pake model User biar bisa bikin akun dummy pas test
use App\Models\User;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test buat ngecek fitur halaman dan proses konfirmasi password
class PasswordConfirmationTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman konfirmasi password bisa ditampilin
    public function test_confirm_password_screen_can_be_rendered(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // login sebagai user tadi terus akses halaman /confirm-password
        $response = $this->actingAs($user)->get('/confirm-password');

        // pastiin halaman konfirmasi password kebuka dengan status 200
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek password bisa dikonfirmasi kalo inputnya bener
    public function test_password_can_be_confirmed(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // login sebagai user tadi terus kirim request post ke /confirm-password
        $response = $this->actingAs($user)->post('/confirm-password', [
            // masukin password yang bener sesuai factory
            'password' => 'password',
        ]);

        // pastiin responnya nge-redirect ke halaman tujuan abis konfirmasi berhasil
        $response->assertRedirect();
        // pastiin ga ada error validasi di session
        $response->assertSessionHasNoErrors();
    }

    // fungsi test buat ngecek konfirmasi password ditolak kalo passwordnya salah
    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // login sebagai user tadi terus kirim password yang salah ke /confirm-password
        $response = $this->actingAs($user)->post('/confirm-password', [
            // sengaja masukin password yang salah biar gagal konfirmasi
            'password' => 'wrong-password',
        ]);

        // pastiin ada error validasi yang muncul di session
        $response->assertSessionHasErrors();
    }
}
