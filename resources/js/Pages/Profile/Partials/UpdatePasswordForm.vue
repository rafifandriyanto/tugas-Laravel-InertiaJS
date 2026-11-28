<script setup>
// ambil komponen buat nampilin pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul kolom input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat simpan password baru
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen kotak input teks bawaan breeze
import TextInput from '@/Components/TextInput.vue';
// ambil useForm buat ngatur state dan kirim form ganti password
import { useForm } from '@inertiajs/vue3';
// ambil ref dari vue buat nyimpen referensi elemen input dom
import { ref } from 'vue';

// ref buat nangkep elemen input password baru biar bisa difokusin pas error
const passwordInput = ref(null);
// ref buat nangkep elemen input password lama biar bisa difokusin pas error
const currentPasswordInput = ref(null);

// bikin state form ganti password pake useForm
const form = useForm({
    // field password yang sekarang dipake awalnya kosong
    current_password: '',
    // field password baru awalnya kosong
    password: '',
    // field konfirmasi password baru awalnya kosong
    password_confirmation: '',
});

// fungsi buat ngirim perubahan password pas form disubmit
const updatePassword = () => {
    // kirim request put ke route password.update lewat inertia
    form.put(route('password.update'), {
        // jaga posisi scroll biar ga loncat ke atas abis submit
        preserveScroll: true,
        // kalo berhasil ganti password kosongin semua isi form
        onSuccess: () => form.reset(),
        // tangkep kalo ada error validasi dari backend
        onError: () => {
            // cek kalo errornya ada di kolom password baru
            if (form.errors.password) {
                // reset password baru sama konfirmasinya biar user ketik ulang
                form.reset('password', 'password_confirmation');
                // arahin fokus kursor ke input password baru
                passwordInput.value.focus();
            }
            // cek kalo errornya ada di kolom password lama
            if (form.errors.current_password) {
                // kosongin field password lama yang salah
                form.reset('current_password');
                // arahin fokus kursor ke input password lama
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <!-- bagian halaman buat form ganti password akun -->
    <section>
        <!-- header bagian ganti password buat judul sama tips keamanan -->
        <header>
            <!-- judul bagian Update Password -->
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Update Password
            </h2>

            <!-- saran ke user buat pake password panjang dan acak biar akun aman -->
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <!-- form ganti password yang cegah reload terus manggil fungsi updatePassword -->
        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <!-- blok input password lama yang sekarang dipake -->
            <div>
                <!-- label buat kolom password saat ini -->
                <InputLabel for="current_password" value="Current Password" />

                <!-- kotak input password lama yang terhubung ke ref currentPasswordInput dan form.current_password -->
                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <!-- nampilin error kalo password lama ga cocok sama yang di database -->
                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <!-- blok input password baru -->
            <div>
                <!-- label buat kolom password baru -->
                <InputLabel for="password" value="New Password" />

                <!-- kotak input password baru yang terhubung ke ref passwordInput dan form.password -->
                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <!-- nampilin error validasi password baru kalo kurang panjang atau ga valid -->
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <!-- blok input konfirmasi ulang password baru -->
            <div>
                <!-- label buat kolom konfirmasi password -->
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <!-- kotak input konfirmasi password baru yang nyambung ke form.password_confirmation -->
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <!-- nampilin error kalo konfirmasi password ga sama dengan password baru -->
                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <!-- baris bawah form buat tombol Save sama notif teks Saved -->
            <div class="flex items-center gap-4">
                <!-- tombol simpan password baru yang dikunci pas lagi ngirim -->
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <!-- animasi fade buat nampilin teks Saved sebentar pas password baru berhasil disimpan -->
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
