<script setup>
// ambil komponen logo aplikasi bawaan laravel breeze
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
// ambil komponen Link dari inertia buat navigasi antar halaman
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <!-- pembungkus layout tamu biar konten card posisinya rapi di tengah layar -->
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
        <!-- wadah logo aplikasi di bagian atas kotak form -->
        <div>
            <!-- klik logo bakal langsung ngarahin balik ke halaman beranda -->
            <Link href="/">
                <!-- gambar logo aplikasi ukuran agak gede -->
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link>
        </div>

        <!-- kotak putih card utama buat nampung form login, register, atau reset password -->
        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800"
        >
            <!-- tempat isi form dari halaman yang make layout ini dirender -->
            <slot />
        </div>
    </div>
</template>
