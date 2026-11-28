<?php

// taruh file ini di namespace Tests\Feature\Auth buat test verifikasi email
namespace Tests\Feature\Auth;

// pake model User biar bisa bikin akun dummy pas test
use App\Models\User;
// pake event Verified buat ngecek apakah event verifikasi kepanggil
use Illuminate\Auth\Events\Verified;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake facade Event buat palsuin pengiriman event pas test
use Illuminate\Support\Facades\Event;
// pake facade URL buat bikin signed url verifikasi sementara
use Illuminate\Support\Facades\URL;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test buat ngecek alur verifikasi email user
class EmailVerificationTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman verifikasi email bisa ditampilin
    public function test_email_verification_screen_can_be_rendered(): void
    {
        // bikin satu user dummy yang status emailnya belum terverifikasi
        $user = User::factory()->unverified()->create();

        // login sebagai user belum verifikasi tadi terus akses halaman /verify-email
        $response = $this->actingAs($user)->get('/verify-email');

        // pastiin halaman verifikasi kebuka dengan status 200
        $response->assertStatus(200);
    }

    // fungsi test buat ngecek email user bisa diverifikasi pake link bertanda tangan yang valid
    public function test_email_can_be_verified(): void
    {
        // bikin satu user dummy yang status emailnya belum terverifikasi
        $user = User::factory()->unverified()->create();

        // palsuin semua event biar ga beneran ngejalanin listener asli pas test
        Event::fake();

        // bikin url verifikasi sementara yang punya signature valid
        $verificationUrl = URL::temporarySignedRoute(
            // arahin ke nama route verification.verify
            'verification.verify',
            // atur masa berlaku link verifikasi selama 60 menit dari sekarang
            now()->addMinutes(60),
            // kirim parameter id user sama hash sha1 dari emailnya
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // login sebagai user tadi terus buka link verifikasi yang udah dibikin
        $response = $this->actingAs($user)->get($verificationUrl);

        // pastiin event Verified beneran kepanggil pas proses verifikasi sukses
        Event::assertDispatched(Verified::class);
        // pastiin pas dicek ulang dari database email user sekarang udah terverifikasi
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // pastiin abis verifikasi diarahkan ke dashboard dengan query verified=1
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    // fungsi test buat ngecek verifikasi gagal kalo hash email di link nya salah
    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        // bikin satu user dummy yang status emailnya belum terverifikasi
        $user = User::factory()->unverified()->create();

        // bikin url verifikasi sementara tapi hash emailnya sengaja disalahin
        $verificationUrl = URL::temporarySignedRoute(
            // arahin ke nama route verification.verify
            'verification.verify',
            // atur masa berlaku link selama 60 menit dari sekarang
            now()->addMinutes(60),
            // kirim id user tapi hash-nya pake string email yang salah
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        // login sebagai user tadi terus coba buka link verifikasi yang hash-nya salah
        $this->actingAs($user)->get($verificationUrl);

        // pastiin pas dicek ulang di database email user tetep belum terverifikasi
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
