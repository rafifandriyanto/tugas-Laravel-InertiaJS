<?php

// taruh file ini di namespace Tests\Feature buat feature test
namespace Tests\Feature;

// pake trait RefreshDatabase biar data di database ke-reset tiap test jalan
use Illuminate\Foundation\Testing\RefreshDatabase;
// pake kelas dasar TestCase punya project ini
use Tests\TestCase;

// kelas contoh feature test buat ngecek respon halaman utama
class ExampleTest extends TestCase
{
    // aktifin reset database tiap kali test di kelas ini jalan
    use RefreshDatabase;

    // fungsi test buat mastiin halaman utama ngasih respon sukses
    public function test_the_application_returns_a_successful_response(): void
    {
        // kirim request get ke url root terus simpen responnya
        $response = $this->get('/');

        // pastiin kode status http nya 200 alias berhasil
        $response->assertStatus(200);
    }
}
