<script setup>
// ambil computed dari vue buat ngatur class css dinamis sesuai status aktif
import { computed } from 'vue';
// ambil komponen Link dari inertia buat navigasi antar halaman tanpa reload
import { Link } from '@inertiajs/vue3';

// terima prop tujuan link sama penanda apakah link ini lagi aktif dibuka
const props = defineProps({
    // alamat route atau url tujuan link navigasi
    href: {
        // tipe datanya string
        type: String,
        // wajib diisi pas manggil komponen ini
        required: true,
    },
    // penanda apakah menu ini lagi jadi halaman aktif sekarang
    active: {
        // tipe datanya boolean
        type: Boolean,
    },
});

// tentuin class tailwind buat nav link tergantung kondisi prop active
const classes = computed(() =>
    // cek apakah link lagi aktif atau gak
    props.active
        // kalo lagi aktif, kasih garis bawah warna indigo dan teks lebih gelap
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        // kalo gak aktif, garis bawahnya transparan dan teksnya abu-abu biasa
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out',
);
</script>

<template>
    <!-- link navigasi navbar desktop pake alamat href dan class aktif/nonaktif dari computed -->
    <Link :href="href" :class="classes">
        <!-- slot buat nampilin nama menu navigasi -->
        <slot />
    </Link>
</template>
