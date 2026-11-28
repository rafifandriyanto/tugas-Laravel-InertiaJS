<script setup>
// ambil layout utama buat halaman user yang udah login
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// ambil form partial buat hapus akun user
import DeleteUserForm from './Partials/DeleteUserForm.vue';
// ambil form partial buat ganti password user
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
// ambil form partial buat ubah nama dan email profil user
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
// ambil komponen Head buat ngatur judul tab browser
import { Head } from '@inertiajs/vue3';

// tangkep props yang dikirim dari ProfileController laravel
defineProps({
    // prop buat nandain user wajib verifikasi email atau engga
    mustVerifyEmail: {
        // tipe datanya boolean true atau false
        type: Boolean,
    },
    // prop status buat ngasih kabar misalnya link verifikasi baru udah kekirim
    status: {
        // tipe datanya string
        type: String,
    },
});
</script>

<template>
    <!-- atur judul tab browser jadi Profile -->
    <Head title="Profile" />

    <!-- bungkus isi halaman profil pake layout authenticated -->
    <AuthenticatedLayout>
        <!-- isi slot header di bagian atas halaman profil -->
        <template #header>
            <!-- judul halaman Profile di bagian header -->
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Profile
            </h2>
        </template>

        <!-- pembungkus utama konten halaman profil dengan jarak atas bawah -->
        <div class="py-12">
            <!-- container tengah buat ngatur lebar maksimal dan jarak antar kartu -->
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- kartu pertama buat form edit informasi profil -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- panggil komponen form update nama dan email sambil oper props mustVerifyEmail sama status -->
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- kartu kedua buat form ganti password -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- panggil komponen form ganti password -->
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- kartu ketiga buat form hapus akun secara permanen -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- panggil komponen form hapus akun -->
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
