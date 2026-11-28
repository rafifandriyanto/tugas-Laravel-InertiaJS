// masukin file css utama yang udah ada konfigurasi tailwind-nya
import '../css/app.css';
// jalanin file bootstrap.js buat nyiapin axios dan header default request
import './bootstrap';

// ambil fungsi createInertiaApp buat bikin dan ngejalanin aplikasi inertia di vue 3
import { createInertiaApp } from '@inertiajs/vue3';
// ambil helper buat nyari file halaman vue secara dinamis sesuai nama page
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// ambil createApp dan fungsi render h dari vue buat ngebangun aplikasi
import { createApp, h } from 'vue';
// ambil plugin ZiggyVue biar helper route() laravel bisa dipake di komponen vue
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// ambil nama aplikasi dari file .env, kalo kosong pake tulisan 'Laravel' aja
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// mulai setup dan jalanin aplikasi inertia
createInertiaApp({
    // atur format judul tab browser jadi "Judul Halaman - Nama Aplikasi"
    title: (title) => `${title} - ${appName}`,
    // cari komponen halaman vue sesuai nama page yang dikirim dari controller laravel
    resolve: (name) =>
        // panggil helper buat muat file halaman vue yang cocok
        resolvePageComponent(
            // arahin path ke folder Pages sesuai nama komponen yang diminta
            `./Pages/${name}.vue`,
            // daftarin semua file .vue di folder Pages dan subfoldernya biar kebaca vite
            import.meta.glob('./Pages/**/*.vue'),
        ),
    // fungsi setup buat masang vue ke elemen html dan daftarin plugin yang dipake
    setup({ el, App, props, plugin }) {
        // bikin instance aplikasi vue pake komponen root App dan props dari inertia
        return createApp({ render: () => h(App, props) })
            // pasang plugin bawaan inertia ke aplikasi vue
            .use(plugin)
            // pasang ziggy biar pemanggilan route() jalan di template dan script vue
            .use(ZiggyVue)
            // tempelin aplikasi vue ke elemen html yang disiapin sama @inertia
            .mount(el);
    },
    // atur tampilan loading bar di atas halaman pas pindah route inertia
    progress: {
        // kasih warna abu-abu gelap ke garis progress loading-nya
        color: '#4B5563',
    },
});
