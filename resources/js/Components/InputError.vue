<script setup>
// terima prop message buat nampung teks pesan error validasi form
defineProps({
    // teks pesan error yang dikirim dari validasi backend atau frontend
    message: {
        // tipe datanya string
        type: String,
    },
});
</script>

<template>
    <!-- wadah pesan error yang cuma ditampilin kalo prop message ada isinya -->
    <div v-show="message">
        <!-- paragraf warna merah buat nampilin teks error di bawah input -->
        <p class="text-sm text-red-600 dark:text-red-400">
            <!-- nampilin isi teks pesan error -->
            {{ message }}
        </p>
    </div>
</template>
