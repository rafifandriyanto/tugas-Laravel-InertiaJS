<script setup>
// ambil onMounted sama ref dari vue buat ngatur fokus elemen input pas tampil
import { onMounted, ref } from 'vue';

// bikin two-way binding v-model pake defineModel buat nilai teks input
const model = defineModel({
    // tipe data teks input harus string
    type: String,
    // wajib disambungin ke v-model pas komponen ini dipake
    required: true,
});

// ref buat megang elemen <input> asli di dom
const input = ref(null);

// pas komponen selesai dipasang di halaman, cek apakah ada atribut autofocus
onMounted(() => {
    // kalo elemen input punya atribut autofocus, langsung arahin kursor ke situ
    if (input.value.hasAttribute('autofocus')) {
        // fokusin kursor ke input ini
        input.value.focus();
    }
});

// bukain method focus biar komponen luar bisa manggil fungsi fokus ke input ini
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <!-- kotak input teks yang nyambung ke v-model dan punya ref input -->
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
        v-model="model"
        ref="input"
    />
</template>
