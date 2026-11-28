// ambil tema bawaan tailwind buat dipake lagi nanti pas nambah font
import defaultTheme from 'tailwindcss/defaultTheme';
// ambil plugin forms tailwind biar tampilan input form lebih rapi
import forms from '@tailwindcss/forms';

// export objek konfigurasi tailwind biar dipake pas build css
export default {
    // daftar path file yang bakal discan tailwind buat nyari class css yang dipake
    content: [
        // scan template blade pagination bawaan laravel di folder vendor
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        // scan file view hasil compile blade yang disimpen di storage
        './storage/framework/views/*.php',
        // scan semua file blade di folder resources/views dan subfolder-nya
        './resources/views/**/*.blade.php',
        // scan semua komponen vue di folder resources/js dan subfolder-nya
        './resources/js/**/*.vue',
    ],

    // bagian buat ngatur desain tema tailwind kayak warna dan font
    theme: {
        // nambahin atau nimpa setingan tema bawaan tanpa ngapus bawaan aslinya
        extend: {
            // ngatur daftar jenis huruf yang dipake proyek
            fontFamily: {
                // pasang font Figtree di urutan pertama buat font sans lalu lanjut font bawaan tailwind
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    // daftarin plugin tambahan tailwind, di sini pake plugin forms
    plugins: [forms],
};
