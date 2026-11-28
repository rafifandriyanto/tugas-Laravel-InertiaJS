<script setup>
// ambil layout utama buat user yang udah login
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// ambil Head buat ngatur judul tab sama Link buat pindah halaman tanpa reload
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <!-- atur judul tab browser jadi Dashboard -->
    <Head title="Dashboard" />

    <!-- bungkus isi halaman pake layout khusus user yang udah login -->
    <AuthenticatedLayout>
        <!-- isi slot header di bagian atas halaman dashboard -->
        <template #header>
            <!-- judul halaman dashboard di bagian header -->
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <!-- pembungkus utama konten dashboard biar ada jarak atas bawah -->
        <div class="py-12">
            <!-- atur lebar maksimal konten biar posisinya rapi di tengah -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- kartu putih buat nampilin pesan sambutan user -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <!-- tata letak isi kartu biar responsif di layar kecil maupun gede -->
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- bagian kiri buat nyapa user yang lagi login -->
                        <div>
                            <!-- nampilin nama user yang diambil dari shared props auth inertia -->
                            <p class="font-medium text-base">Selamat datang, {{ $page.props.auth.user.name }}!</p>
                            <!-- info tambahan kalo user udah berhasil masuk ke sistem -->
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Anda telah berhasil masuk ke dalam sistem.
                            </p>
                        </div>
                        <!-- bagian kanan buat tombol pintasan ke halaman artikel -->
                        <div>
                            <!-- link tombol buat ngarahin user ke halaman daftar artikel -->
                            <Link
                                :href="route('posts.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150"
                            >
                                Kelola Artikel
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
