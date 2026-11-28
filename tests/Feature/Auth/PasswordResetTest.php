<?php

// taruh file ini di namespace Tests\Feature\Auth buat test fitur lupa dan reset password
namespace Tests\Feature\Auth;

// pake model User biar bisa bikin akun dummy pas test
use App\Models\User;
// pake kelas notifikasi ResetPassword buat ngecek notifikasi reset password ke-kirim
use Illuminate\Auth\Notifications\ResetPassword;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake facade Notification buat palsuin pengiriman email notifikasi pas test
use Illuminate\Support\Facades\Notification;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test buat ngecek alur lupa password sampe reset password baru
class PasswordResetTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman minta link reset password bisa ditampilin
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        // kirim request get ke halaman /forgot-password terus simpen responnya
        $response = $this->get('/forgot-password');

        // pastiin halaman lupa password kebuka dengan status 200
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek user bisa request link reset password lewat email
    public function test_reset_password_link_can_be_requested(): void
    {
        // palsuin sistem notifikasi biar ga ngirim email beneran pas test
        Notification::fake();

        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request post ke /forgot-password bawa email user dummy
        $this->post('/forgot-password', ['email' => $user->email]);

        // pastiin notifikasi ResetPassword kekirim ke user tadi
        Notification::assertSentTo($user, ResetPassword::class);
    }

    // fungsi test buat ngecek halaman form reset password bisa dibuka pake token yang valid
    public function test_reset_password_screen_can_be_rendered(): void
    {
        // palsuin sistem notifikasi biar token reset bisa diambil dari objek notifikasinya
        Notification::fake();

        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request post ke /forgot-password biar notifikasi berisi token kebentuk
        $this->post('/forgot-password', ['email' => $user->email]);

        // pastiin notifikasi kekirim terus cek halaman reset password pake token dari notifikasi itu
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            // buka halaman /reset-password sambil tempelin token reset dari notifikasi
            $response = $this->get('/reset-password/'.$notification->token);

            // pastiin halaman form reset password kebuka dengan status 200
            $response->assertStatus(200);

            // balikin nilai true buat nandain callback pengecekan notifikasi sukses
            return true;
        });
    }

    // fungsi test buat ngecek password user bisa di-reset pake token yang valid
    public function test_password_can_be_reset_with_valid_token(): void
    {
        // palsuin sistem notifikasi biar token reset bisa dipake di step selanjutnya
        Notification::fake();

        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request post ke /forgot-password buat minta token reset password
        $this->post('/forgot-password', ['email' => $user->email]);

        // tangkep notifikasi ResetPassword buat dapetin tokennya lalu kirim request reset password
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            // kirim request post ke /reset-password bawa token, email, sama password baru
            $response = $this->post('/reset-password', [
                // masukin token reset yang didapet dari notifikasi
                'token' => $notification->token,
                // masukin email user yang mau di-reset passwordnya
                'email' => $user->email,
                // masukin password baru
                'password' => 'password',
                // masukin konfirmasi password baru harus sama kayak password
                'password_confirmation' => 'password',
            ]);

            // cek respon abis kirim form reset password
            $response
                // pastiin ga ada error validasi di session
                ->assertSessionHasNoErrors()
                // pastiin abis reset password user diarahkan ke halaman login
                ->assertRedirect(route('login'));

            // balikin nilai true buat nandain callback pengecekan notifikasi sukses
            return true;
        });
    }
}
