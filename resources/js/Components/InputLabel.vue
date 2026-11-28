<script setup>
// terima teks label dari komponen induk lewat props
defineProps({
    // isi teks label yang mau ditampilin di atas input
    value: {
        // tipenya teks string
        type: String,
    },
});
</script>

<template>
    <!-- tag label buat ngasih keterangan di atas kolom input -->
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        <!-- kalo prop value diisi, tampilin teks dari prop value -->
        <span v-if="value">{{ value }}</span>
        <!-- kalo prop value kosong, tampilin teks atau isi dari slot -->
        <span v-else><slot /></span>
    </label>
</template>
