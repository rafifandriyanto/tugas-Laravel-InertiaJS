<script setup>
// ambil komponen checkbox buat fitur remember me
import Checkbox from '@/Components/Checkbox.vue';
// ambil layout tamu buat tampilan halaman login
import GuestLayout from '@/Layouts/GuestLayout.vue';
// ambil komponen buat nampilin pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul kolom input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat submit form login
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen input teks bawaan breeze
import TextInput from '@/Components/TextInput.vue';
// ambil Head buat judul tab, Link buat pindah halaman, sama useForm buat ngatur form login
import { Head, Link, useForm } from '@inertiajs/vue3';

// tangkep props yang dikirim dari controller login laravel
defineProps({
    // prop buat ngecek apakah fitur reset password tersedia atau engga
    canResetPassword: {
        // tipe datanya boolean true atau false
        type: Boolean,
    },
    // prop status buat nampilin pesan info misalnya abis reset password
    status: {
        // tipe datanya string
        type: String,
    },
});

// siapin state form login pake useForm
const form = useForm({
    // field email awal dikosongin
    email: '',
    // field password awal dikosongin
    password: '',
    // checkbox remember me awalnya false alias ga dicentang
    remember: false,
});

// fungsi buat ngirim data login pas form disubmit
const submit = () => {
    // kirim request post ke route login lewat inertia
    form.post(route('login'), {
        // abis request kelar kosongin lagi field password biar ga nyangkut di memori form
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <!-- bungkus isi halaman login pake layout tamu -->
    <GuestLayout>
        <!-- atur judul tab browser jadi Log in -->
        <Head title="Log in" />

        <!-- nampilin kotak info warna ijo kalo ada pesan status dari session -->
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            <!-- cetak isi pesan status -->
            {{ status }}
        </div>

        <!-- form login yang cegah reload halaman terus manggil fungsi submit -->
        <form @submit.prevent="submit">
            <!-- pembungkus bagian input email dan pesan errornya -->
            <div>
                <!-- label buat kolom email -->
                <InputLabel for="email" value="Email" />

                <!-- kotak input email yang nyambung ke form.email pake v-model -->
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <!-- nampilin error validasi email kalo login gagal -->
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- pembungkus bagian input password dan pesan errornya -->
            <div class="mt-4">
                <!-- label buat kolom password -->
                <InputLabel for="password" value="Password" />

                <!-- kotak input password yang nyambung ke form.password pake v-model -->
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <!-- nampilin error validasi password kalo ada error dari server -->
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- pembungkus checkbox remember me -->
            <div class="mt-4 block">
                <!-- label pembungkus biar kotak centang sama tulisannya bisa diklik bareng -->
                <label class="flex items-center">
                    <!-- checkbox remember me yang nyambung ke form.remember -->
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <!-- teks keterangan Remember me di sebelah checkbox -->
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                        >Remember me</span
                    >
                </label>
            </div>

            <!-- pembungkus link lupa password sama tombol login di bagian bawah form -->
            <div class="mt-4 flex items-center justify-end">
                <!-- link ke halaman lupa password yang cuma muncul kalo canResetPassword bernilai true -->
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>

                <!-- tombol submit login yang jadi pudar dan nonaktif pas lagi proses ngirim -->
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
