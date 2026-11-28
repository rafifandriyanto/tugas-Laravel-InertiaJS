<?php

// taruh file ini di namespace Tests\Feature buat test fitur profil user
namespace Tests\Feature;

// pake model User biar bisa bikin akun dummy pas test
use App\Models\User;
// pake trait RefreshDatabase biar database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas test buat ngecek halaman profil, update data, sama hapus akun
class ProfileTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat ngecek halaman profil bisa ditampilin ke user yang udah login
    public function test_profile_page_is_displayed(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // siapin request buat akses halaman profil sebagai user yang udah login
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // kirim request get ke url /profile
            ->get('/profile');

        // pastiin halaman profil kebuka dengan status 200 ok
        $response->assertOk();
    }

    // fungsi test buat ngecek nama dan email user bisa di-update
    public function test_profile_information_can_be_updated(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request patch buat ngubah data profil sebagai user yang udah login
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // kirim request patch ke /profile bawa nama dan email baru
            ->patch('/profile', [
                // masukin nama baru buat profil user
                'name' => 'Test User',
                // masukin email baru buat profil user
                'email' => 'test@example.com',
            ]);

        // cek respon abis kirim update profil
        $response
            // pastiin ga ada error validasi yang kesimpen di session
            ->assertSessionHasNoErrors()
            // pastiin user diarahkan balik ke halaman /profile
            ->assertRedirect('/profile');

        // ambil ulang data user terbaru dari database biar perubahannya kebaca
        $user->refresh();

        // pastiin nama user di database udah berubah jadi Test User
        $this->assertSame('Test User', $user->name);
        // pastiin email user di database udah berubah jadi test@example.com
        $this->assertSame('test@example.com', $user->email);
        // pastiin status verifikasi email jadi null karena emailnya barusan diganti
        $this->assertNull($user->email_verified_at);
    }

    // fungsi test buat ngecek status verifikasi email ga ilang kalo emailnya ga diganti
    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request patch buat update nama aja tanpa ganti email
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // kirim request patch ke /profile bawa nama baru dan email lama
            ->patch('/profile', [
                // masukin nama baru buat profil user
                'name' => 'Test User',
                // tetep pake email lama punya si user
                'email' => $user->email,
            ]);

        // cek respon abis kirim update profil
        $response
            // pastiin ga ada error validasi di session
            ->assertSessionHasNoErrors()
            // pastiin user diarahkan balik ke /profile
            ->assertRedirect('/profile');

        // pastiin kolom email_verified_at tetep keisi alias ga ke-reset jadi null
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    // fungsi test buat ngecek user bisa hapus akun sendiri pake password yang bener
    public function test_user_can_delete_their_account(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request delete buat ngapus akun sebagai user yang udah login
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // kirim request delete ke /profile bawa konfirmasi password
            ->delete('/profile', [
                // masukin password yang bener sesuai factory
                'password' => 'password',
            ]);

        // cek respon abis hapus akun
        $response
            // pastiin ga ada error validasi di session
            ->assertSessionHasNoErrors()
            // pastiin abis hapus akun langsung diarahkan ke halaman utama /
            ->assertRedirect('/');

        // pastiin user udah logout alias statusnya balik jadi guest
        $this->assertGuest();
        // pastiin data user beneran ilang alias null pas dicek lagi di database
        $this->assertNull($user->fresh());
    }

    // fungsi test buat ngecek hapus akun ditolak kalo password konfirmasinya salah
    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        // bikin satu user dummy di database pake factory
        $user = User::factory()->create();

        // kirim request delete dari halaman /profile pake password yang salah
        $response = $this
            // login dulu pake akun user dummy tadi
            ->actingAs($user)
            // tandain request dikirim dari /profile buat cek redirect baliknya
            ->from('/profile')
            // kirim request delete ke /profile bawa password yang salah
            ->delete('/profile', [
                // sengaja masukin password yang salah biar gagal validasi
                'password' => 'wrong-password',
            ]);

        // cek respon abis gagal hapus akun
        $response
            // pastiin session nangkep error validasi di field password
            ->assertSessionHasErrors('password')
            // pastiin user dibalikin lagi ke halaman /profile
            ->assertRedirect('/profile');

        // pastiin data user masih aman di database alias ga ikut kehapus
        $this->assertNotNull($user->fresh());
    }
}
