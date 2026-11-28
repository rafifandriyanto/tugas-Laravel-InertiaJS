<script setup>
// ambil komponen Link dari inertia biar pindah halaman gak perlu reload browser
import { Link } from '@inertiajs/vue3';

// terima prop href dari komponen parent buat alamat tujuan link
defineProps({
    // alamat url atau route tujuan pas item dropdown diklik
    href: {
        // tipe datanya string
        type: String,
        // wajib diisi biar link-nya jelas mau ke mana
        required: true,
    },
});
</script>

<template>
    <!-- tombol link inertia di dalem menu dropdown dengan styling hover dan focus -->
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
    >
        <!-- slot buat nampilin teks menu di dalem link -->
        <slot />
    </Link>
</template>
