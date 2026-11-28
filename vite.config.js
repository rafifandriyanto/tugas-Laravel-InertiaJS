// ambil helper defineConfig dari vite biar setingannya kebaca rapi sama editor
import { defineConfig } from 'vite';
// ambil plugin resmi laravel buat nyambungin vite ke proyek laravel
import laravel from 'laravel-vite-plugin';
// ambil plugin vue biar vite bisa compile file komponen .vue
import vue from '@vitejs/plugin-vue';

// export setingan utama vite buat dev server dan build aset
export default defineConfig({
    // daftar plugin yang dipake vite pas jalan
    plugins: [
        // pasang plugin laravel buat nentuin file masuk dan auto refresh
        laravel({
            // titik masuk utama javascript yang dibundle sama vite
            input: 'resources/js/app.js',
            // refresh halaman browser pas ada file blade atau route yang berubah
            refresh: true,
        }),
        // pasang plugin vue biar file .vue bisa dicompile ke js
        vue({
            // atur cara plugin vue ngolah tag template di file .vue
            template: {
                // atur gimana url aset kayak gambar di tag img diolah pas build
                transformAssetUrls: {
                    // biarin base url kosong biar path relatif ga ditambah-tambahin
                    base: null,
                    // jangan ubah url absolut jadi import modul biar link eksternal tetep aman
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
