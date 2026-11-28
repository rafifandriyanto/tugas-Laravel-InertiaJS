<script setup>
// ambil layout tamu buat tampilan halaman lupa password
import GuestLayout from '@/Layouts/GuestLayout.vue';
// ambil komponen buat nampilin pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul kolom input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat submit form
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen input teks bawaan breeze
import TextInput from '@/Components/TextInput.vue';
// ambil Head buat judul tab sama useForm buat ngatur state dan kirim form inertia
import { Head, useForm } from '@inertiajs/vue3';

// tangkep props yang dikirim dari controller laravel
defineProps({
    // prop status buat nampung pesan kalo link reset udah berhasil dikirim ke email
    status: {
        // tipe data status harus berupa string
        type: String,
    },
});

// bikin state form inertia buat nampung email user
const form = useForm({
    // field email awalnya dikosongin dulu
    email: '',
});

// fungsi buat ngirim permintaan link reset password ke email user
const submit = () => {
    // kirim request post ke route password.email lewat inertia
    form.post(route('password.email'));
};
</script>

<template>
    <!-- bungkus isi halaman pake layout tamu -->
    <GuestLayout>
        <!-- atur judul tab browser jadi Forgot Password -->
        <Head title="Forgot Password" />

        <!-- penjelasan singkat ke user buat masukin email biar dikirimin link reset password -->
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <!-- kotak pesan sukses warna ijo yang muncul cuma kalo prop status ada isinya -->
        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
        >
            <!-- nampilin teks status dari backend -->
            {{ status }}
        </div>

        <!-- form minta link reset password yang cegah reload terus manggil fungsi submit -->
        <form @submit.prevent="submit">
            <!-- pembungkus kolom input email dan pesan errornya -->
            <div>
                <!-- label tulisan Email buat nandain kolom input -->
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

                <!-- nampilin error validasi email kalo format salah atau email ga ketemu -->
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- pembungkus tombol submit biar posisinya rata kanan -->
            <div class="mt-4 flex items-center justify-end">
                <!-- tombol kirim link reset password yang dikunci dan pudar pas lagi proses -->
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
