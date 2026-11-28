<?php

// taruh file ini di namespace Tests\Feature\Auth buat test update password dari profil
namespace Tests\Feature\Auth;

// pake model User biar bisa bikin akun dummy pas test
use App\Models\User;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake facade Hash buat ngecek password baru yang udah di-hash di database
use Illuminate\Support\Facades\Hash;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test buat ngecek fitur ganti password user yang lagi login
class PasswordUpdateTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek password bisa diganti kalo password lama bener
    public function test_password_can_be_updated(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request put dari halaman /profile buat ngubah password user
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // tandain request asalnya dari halaman /profile
            ->from('/profile')
            // kirim request put ke /password bawa password lama dan password baru
            ->put('/password', [
                // masukin password lama yang bener sesuai factory
                'current_password' => 'password',
                // masukin password baru yang mau dipake
                'password' => 'new-password',
                // masukin konfirmasi password baru yang harus sama
                'password_confirmation' => 'new-password',
            ]);

        // cek hasil respon abis update password
        $response
            // pastiin ga ada error validasi di session
            ->assertSessionHasNoErrors()
            // pastiin user diarahkan balik ke halaman /profile
            ->assertRedirect('/profile');

        // pastiin password baru beneran cocok sama hash password terbaru user di database
        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    // fungsi test buat ngecek ganti password ditolak kalo password lamanya salah
    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request put dari halaman /profile pake password lama yang salah
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // tandain request asalnya dari halaman /profile buat cek redirect baliknya
            ->from('/profile')
            // kirim request put ke /password bawa password lama yang salah
            ->put('/password', [
                // sengaja masukin password lama yang salah biar gagal validasi
                'current_password' => 'wrong-password',
                // masukin password baru yang mau dipake
                'password' => 'new-password',
                // masukin konfirmasi password baru yang sama
                'password_confirmation' => 'new-password',
            ]);

        // cek hasil respon abis gagal ganti password
        $response
            // pastiin session nangkep error validasi di field current_password
            ->assertSessionHasErrors('current_password')
            // pastiin user dibalikin lagi ke halaman /profile
            ->assertRedirect('/profile');
    }
}
