<?php

// alamat namespace buat form request di folder Requests
namespace App\Http\Requests;

// pake model User buat ngecek keunikan email di tabel users
use App\Models\User;
// pake interface ValidationRule buat type hint aturan validasi
use Illuminate\Contracts\Validation\ValidationRule;
// pake class FormRequest sebagai induk request validasi
use Illuminate\Foundation\Http\FormRequest;
// pake helper Rule buat bikin aturan validasi unik yang lebih fleksibel
use Illuminate\Validation\Rule;

// bikin form request buat ngecek inputan pas user update profil
class ProfileUpdateRequest extends FormRequest
{
    // daftar aturan validasi buat data yang dikirim pas update profil
    public function rules(): array
    {
        // ngambil data akun user yang lagi login sekarang
        $user = $this->user();

        // balikin array aturan validasi buat field name sama email
        return [
            // nama wajib diisi, harus teks, dan maksimal 255 karakter
            'name' => ['required', 'string', 'max:255'],
            // aturan validasi buat field email
            'email' => [
                // email wajib diisi
                'required',
                // isinya harus berupa teks
                'string',
                // hurufnya harus kecil semua
                'lowercase',
                // formatnya harus email yang valid
                'email',
                // panjang maksimal 255 karakter
                'max:255',
                // email ga boleh kembar sama user lain di tabel users, tapi abaikan id user ini sendiri
                Rule::unique(User::class)->ignore($user?->id ?? $this->user()?->getAuthIdentifier()),
            ],
        ];
    }
}
