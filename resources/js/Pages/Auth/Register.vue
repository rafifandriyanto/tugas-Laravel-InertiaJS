<script setup>
// ambil layout tamu buat wadah halaman pendaftaran
import GuestLayout from '@/Layouts/GuestLayout.vue';
// ambil komponen penampil pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul tiap kolom
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat kirim pendaftaran
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen kotak input teks
import TextInput from '@/Components/TextInput.vue';
// ambil Head buat judul halaman, Link buat navigasi SPA, dan useForm buat atur form
import { Head, Link, useForm } from '@inertiajs/vue3';

// bikin state form registrasi pake useForm
const form = useForm({
    // tampung inputan nama lengkap user, awalnya kosong
    name: '',
    // tampung inputan email user, awalnya kosong
    email: '',
    // tampung inputan password baru, awalnya kosong
    password: '',
    // tampung inputan ulang password buat konfirmasi, awalnya kosong
    password_confirmation: '',
});

// fungsi buat ngirim data registrasi pas form disubmit
const submit = () => {
    // kirim form lewat POST ke route register
    form.post(route('register'), {
        // kalo udah selesai, reset isi password sama konfirmasinya biar aman
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <!-- bungkus halaman register pake layout tamu -->
    <GuestLayout>
        <!-- set judul tab browser jadi Register -->
        <Head title="Register" />

        <!-- form pendaftaran akun baru, dicegah reloadnya pas disubmit -->
        <form @submit.prevent="submit">
            <!-- blok inputan nama user -->
            <div>
                <!-- label teks Name buat inputan nama -->
                <InputLabel for="name" value="Name" />

                <!-- kotak input nama yang nyambung ke form.name dan langsung fokus -->
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <!-- tampilin error validasi kalo nama bermasalah -->
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- blok inputan email dengan jarak dari atas -->
            <div class="mt-4">
                <!-- label teks Email buat inputan email -->
                <InputLabel for="email" value="Email" />

                <!-- kotak input email yang nyambung ke form.email -->
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <!-- tampilin error validasi kalo email udah kepake atau formatnya salah -->
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- blok inputan password -->
            <div class="mt-4">
                <!-- label teks Password buat inputan password -->
                <InputLabel for="password" value="Password" />

                <!-- kotak input password yang nyambung ke form.password -->
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <!-- tampilin error validasi kalo password terlalu pendek atau gak cocok -->
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- blok inputan konfirmasi password -->
            <div class="mt-4">
                <!-- label teks Confirm Password buat inputan ulang password -->
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <!-- kotak input ulang password yang nyambung ke form.password_confirmation -->
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <!-- tampilin error kalo konfirmasi password beda sama passwordnya -->
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <!-- baris bawah buat taruh link ke halaman login dan tombol Register -->
            <div class="mt-4 flex items-center justify-end">
                <!-- link buat pindah ke halaman login kalo user ternyata udah punya akun -->
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <!-- tombol Register yang redup dan nonaktif sementara pas form lagi diproses -->
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
