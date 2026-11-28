// export konfigurasi postcss buat ngolah file css proyek
export default {
    // daftar plugin postcss yang dipake pas compile css
    plugins: {
        // aktifin tailwind css buat proses class utility
        tailwindcss: {},
        // tambahin prefix vendor kayak -webkit- biar aman di semua browser
        autoprefixer: {},
    },
};
