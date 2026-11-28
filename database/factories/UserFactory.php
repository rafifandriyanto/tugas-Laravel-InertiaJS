<?php

// lokasi namespace buat semua file factory database
namespace Database\Factories;

// panggil class dasar Factory dari Laravel biar bisa bikin data dummy
use Illuminate\Database\Eloquent\Factories\Factory;
// panggil facade Hash buat enkripsi password user dummy
use Illuminate\Support\Facades\Hash;
// panggil helper Str buat bikin teks acak kayak remember_token
use Illuminate\Support\Str;

// factory khusus buat bikin data dummy model User pas seeding atau testing
class UserFactory extends Factory
{
    // simpen password yang lagi dipake biar gak nge-hash ulang terus
    protected static ?string $password;

    // tentuin isi kolom default kalo bikin user dummy baru
    public function definition(): array
    {
        // balikin array data user palsu yang siap masuk ke tabel users
        return [
            // generate nama acak pake faker
            'name' => fake()->name(),
            // generate alamat email acak yang aman dan pasti unik
            'email' => fake()->unique()->safeEmail(),
            // anggap email user dummy langsung terverifikasi sekarang
            'email_verified_at' => now(),
            // pake password yang udah di-hash, default-nya tulisan 'password'
            'password' => static::$password ??= Hash::make('password'),
            // bikin 10 karakter acak buat token ingat saya
            'remember_token' => Str::random(10),
        ];
    }

    // state tambahan kalo mau bikin user dummy yang emailnya belum diverifikasi
    public function unverified(): static
    {
        // timpa kolom email_verified_at jadi null biar statusnya belum terverifikasi
        return $this->state(fn (array $attributes) => [
            // kosongin tanggal verifikasi emailnya
            'email_verified_at' => null,
        ]);
    }
}
