<script setup>
// ambil layout tamu buat tampilan halaman auth
import GuestLayout from '@/Layouts/GuestLayout.vue';
// ambil komponen buat nampilin pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat submit form
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen input teks bawaan breeze
import TextInput from '@/Components/TextInput.vue';
// ambil Head buat judul tab sama useForm buat ngatur data dan ngirim form inertia
import { Head, useForm } from '@inertiajs/vue3';

// siapin state form pake useForm biar isi password dan errornya gampang dipantau
const form = useForm({
    // field password awalnya dikosongin dulu
    password: '',
});

// fungsi buat ngirim data konfirmasi password pas form disubmit
const submit = () => {
    // kirim request post ke route password.confirm lewat inertia
    form.post(route('password.confirm'), {
        // abis request selesai reset lagi isi formnya biar password ga nyangkut
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <!-- bungkus seluruh halaman pake layout tamu -->
    <GuestLayout>
        <!-- atur judul tab browser jadi Confirm Password -->
        <Head title="Confirm Password" />

        <!-- teks info buat ngasih tau user area ini butuh konfirmasi password dulu -->
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <!-- form konfirmasi password yang cegah reload bawaan terus manggil fungsi submit -->
        <form @submit.prevent="submit">
            <!-- pembungkus bagian input password sama pesan errornya -->
            <div>
                <!-- label buat kolom input password -->
                <InputLabel for="password" value="Password" />
                <!-- kotak input password yang nyambung ke form.password pake v-model -->
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <!-- nampilin pesan error validasi kalo password yang dimasukin salah -->
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- pembungkus tombol submit biar posisinya rata kanan -->
            <div class="mt-4 flex justify-end">
                <!-- tombol kirim konfirmasi yang jadi pudar dan dikunci pas lagi ngirim -->
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
