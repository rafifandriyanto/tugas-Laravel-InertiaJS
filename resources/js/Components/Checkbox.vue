<script setup>
// ambil computed dari vue buat bikin properti reaktif yang punya getter sama setter
import { computed } from 'vue';

// daftarin event update:checked biar perubahan status centang bisa dikirim ke komponen parent
const emit = defineEmits(['update:checked']);

// terima props dari komponen luar buat status centang dan nilai checkbox
const props = defineProps({
    // prop checked buat nampung status centang, bisa boolean atau array kalo checkbox-nya banyak
    checked: {
        // tipe datanya boleh array atau boolean
        type: [Array, Boolean],
        // wajib dikirim pas komponen ini dipake
        required: true,
    },
    // prop value buat nyimpen nilai opsional kalo checkbox ini bagian dari grup pilihan
    value: {
        // nilai default-nya null kalo gak diisi
        default: null,
    },
});

// bikin computed dua arah buat nyambungin v-model input ke props dan emit dari parent
const proxyChecked = computed({
    // fungsi get buat ngambil nilai status centang terbaru dari prop checked
    get() {
        // balikin nilai props.checked ke v-model input
        return props.checked;
    },

    // fungsi set jalan pas user ngeklik checkbox buat ngubah status centangnya
    set(val) {
        // kirim nilai baru ke komponen parent lewat event update:checked
        emit('update:checked', val);
    },
});
</script>

<template>
    <!-- input checkbox yang nyambung ke proxyChecked pake v-model dan styling tailwind -->
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
    />
</template>
