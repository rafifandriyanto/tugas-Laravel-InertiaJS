<script setup>
// ambil computed dari vue buat bikin nilai turunan reaktif
import { computed } from 'vue'
// ambil Head buat judul tab, Link pindah halaman, router buat post logout, sama usePage buat baca shared props
import { Head, Link, router, usePage } from '@inertiajs/vue3'

// tangkep props artikel terbaru sama statistik yang dikirim dari route home
const props = defineProps({
    // prop recentPosts isinya daftar beberapa artikel terbaru
    recentPosts: {
        // tipe datanya array
        type: Array,
        // kasih array kosong kalo belum ada postingan
        default: () => []
    },
    // prop stats isinya angka ringkasan jumlah artikel dan user terdaftar
    stats: {
        // tipe datanya objek
        type: Object,
        // kasih nilai default kalo server belum ngirim stats
        default: () => ({
            // jumlah total artikel awalnya nol
            total_posts: 0,
            // jumlah total user awalnya nol
            total_users: 0,
            // keterangan waktu update terakhir
            last_updated: 'Hari ini'
        })
    }
})

// ambil objek halaman inertia aktif biar bisa baca shared props
const page = usePage()
// ambil data user yang lagi login secara reaktif dari shared props auth
const authUser = computed(() => page.props.auth?.user)

// fungsi buat logout user lewat request post ke route logout
const logout = () => {
    // kirim request post logout pake router inertia
    router.post(route('logout'))
}

// fungsi bantuan buat milih gambar sampul kartu artikel secara bergiliran
const getPostImage = (id, index) => {
    // daftar tiga gambar lokal di folder public/images
    const images = [
        // gambar tema teknologi
        '/images/post_technology.jpg',
        // gambar tema arsitektur
        '/images/post_architecture.jpg',
        // gambar tema editorial
        '/images/hero_editorial.jpg'
    ]
    // pake sisa bagi id atau index biar gambar kartu ga kembar semua
    return images[(id || index) % images.length]
}

// fungsi bantuan buat ngubah tanggal dari database ke format indonesia yang rapi
const formatDate = (dateString) => {
    // kalo tanggalnya kosong kasih tulisan Baru Saja
    if (!dateString) return 'Baru Saja'
    // coba parsing teks tanggal ke format tanggal indonesia
    try {
        // ubah string tanggal jadi objek Date javascript
        const date = new Date(dateString)
        // format tanggal pake locale id-ID biar nama bulannya bahasa indonesia
        return new Intl.DateTimeFormat('id-ID', {
            // nampilin angka tanggal
            day: 'numeric',
            // nampilin nama bulan lengkap
            month: 'long',
            // nampilin tahun empat digit
            year: 'numeric'
        // jalankan format tanggal ke objek Date tadi
        }).format(date)
    // kalo gagal parsing tanggal langsung balikin string mentahnya aja
    } catch {
        // balikin string tanggal asli
        return dateString
    }
}
</script>

<template>
    <!-- atur judul tab browser buat halaman utama LaraPost -->
    <Head title="LaraPost — Publikasi Artikel & Gagasan" />

    <!-- pembungkus luar halaman landing dengan background batu muda dan font sans -->
    <div class="min-h-screen bg-stone-50 text-stone-900 font-sans selection:bg-stone-200">
        
        <!-- navbar atas yang nempel pas discroll dengan efek kaca transparan -->
        <header class="border-b border-stone-200 bg-white/80 backdrop-blur-sm sticky top-0 z-30">
            <!-- container isi navbar biar sejajar di tengah dengan tinggi 16 -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                
                <!-- sisi kiri navbar buat nama brand sama menu navigasi -->
                <div class="flex items-center gap-6">
                    <!-- link logo LaraPost yang balik ke halaman home -->
                    <Link :href="route('home')" class="font-bold text-lg tracking-tight text-stone-900 hover:text-stone-700 transition-colors">
                        LaraPost
                    </Link>
                    <!-- menu navigasi desktop yang disembunyiin di layar hp -->
                    <nav class="hidden sm:flex items-center gap-5 text-sm text-stone-600">
                        <!-- link menuju halaman daftar artikel -->
                        <Link :href="route('posts.index')" class="hover:text-stone-900 transition-colors">
                            Artikel
                        </Link>
                    </nav>
                </div>

                <!-- sisi kanan navbar buat tombol dashboard/keluar atau masuk/daftar -->
                <div class="flex items-center gap-3">
                    <!-- tampilkan tombol dashboard dan keluar kalo user udah login -->
                    <template v-if="authUser">
                        <!-- link ke halaman dashboard user -->
                        <Link
                            :href="route('dashboard')"
                            class="text-xs sm:text-sm font-medium text-stone-700 hover:text-stone-900 transition-colors"
                        >
                            Dashboard
                        </Link>
                        <!-- tombol logout yang manggil fungsi logout pas diklik -->
                        <button
                            @click="logout"
                            type="button"
                            class="text-xs sm:text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors cursor-pointer"
                        >
                            Keluar
                        </button>
                    </template>
                    <!-- tampilkan tombol masuk dan daftar kalo pengunjung belum login -->
                    <template v-else>
                        <!-- link menuju halaman login -->
                        <Link
                            :href="route('login')"
                            class="text-xs sm:text-sm font-medium text-stone-600 hover:text-stone-900 px-3 py-1.5 transition-colors"
                        >
                            Masuk
                        </Link>
                        <!-- link menuju halaman registrasi akun baru -->
                        <Link
                            :href="route('register')"
                            class="text-xs sm:text-sm font-medium text-white bg-stone-900 hover:bg-stone-800 px-3.5 py-1.5 rounded-md transition-colors"
                        >
                            Daftar
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- konten utama halaman landing -->
        <main>
            <!-- bagian hero pembuka di paling atas halaman -->
            <section class="max-w-5xl mx-auto px-4 sm:px-6 pt-14 pb-12">
                <!-- pembungkus teks judul, deskripsi, dan tombol ajakan hero -->
                <div class="max-w-3xl">
                    <!-- label kecil di atas judul utama -->
                    <p class="text-xs font-semibold tracking-wider uppercase text-stone-500 mb-3">
                        Platform Pengelolaan Artikel
                    </p>
                    <!-- headline utama halaman landing -->
                    <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-stone-950 leading-[1.15]">
                        Tulis, kelola, dan publikasikan artikel dengan sederhana.
                    </h1>
                    <!-- deskripsi singkat teknologi yang dipake aplikasi ini -->
                    <p class="mt-4 text-base sm:text-lg text-stone-600 leading-relaxed max-w-2xl">
                        Aplikasi modern berbasis Laravel 12 dan Inertia.js dengan sistem otentikasi resmi Laravel Breeze serta database MySQL.
                    </p>

                    <!-- baris tombol aksi utama di bagian hero -->
                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <!-- tombol utama buat langsung lihat daftar artikel -->
                        <Link
                            :href="route('posts.index')"
                            class="inline-flex items-center px-5 py-2.5 rounded-md bg-stone-900 hover:bg-stone-800 text-white font-medium text-sm transition-colors shadow-xs"
                        >
                            Jelajahi Artikel
                        </Link>

                        <!-- tombol masuk ke akun yang muncul kalo user belum login -->
                        <Link
                            v-if="!authUser"
                            :href="route('login')"
                            class="inline-flex items-center px-4 py-2.5 rounded-md border border-stone-300 hover:bg-stone-100 text-stone-800 font-medium text-sm transition-colors"
                        >
                            Masuk ke Akun
                        </Link>

                        <!-- tombol buka dashboard yang gantiin tombol login kalo user udah login -->
                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="inline-flex items-center px-4 py-2.5 rounded-md border border-stone-300 hover:bg-stone-100 text-stone-800 font-medium text-sm transition-colors"
                        >
                            Buka Dashboard
                        </Link>
                    </div>
                </div>

                <!-- wadah gambar banner besar di bawah teks hero -->
                <div class="mt-10 overflow-hidden rounded-xl border border-stone-200 shadow-sm bg-stone-100">
                    <!-- gambar ilustrasi ruang kerja editorial dari folder public/images -->
                    <img 
                        src="/images/hero_editorial.jpg" 
                        alt="Editorial Workspace" 
                        class="w-full h-64 sm:h-80 md:h-96 object-cover"
                    />
                </div>

                <!-- baris ringkasan statistik jumlah artikel, penulis, dan sistem auth -->
                <div class="mt-10 pt-8 border-t border-stone-200 grid grid-cols-2 sm:grid-cols-3 gap-6 text-left">
                    <!-- kolom statistik pertama buat total artikel -->
                    <div>
                        <!-- judul label total artikel -->
                        <span class="block text-xs text-stone-500 uppercase tracking-wider font-semibold">Total Artikel</span>
                        <!-- angka jumlah artikel dari props stats.total_posts -->
                        <span class="text-2xl font-bold text-stone-900 mt-0.5 block">{{ stats.total_posts }}</span>
                    </div>
                    <!-- kolom statistik kedua buat jumlah penulis terdaftar -->
                    <div>
                        <!-- judul label penulis terdaftar -->
                        <span class="block text-xs text-stone-500 uppercase tracking-wider font-semibold">Penulis Terdaftar</span>
                        <!-- angka jumlah user dari props stats.total_users -->
                        <span class="text-2xl font-bold text-stone-900 mt-0.5 block">{{ stats.total_users }}</span>
                    </div>
                    <!-- kolom statistik ketiga buat info sistem autentikasi yang dipake -->
                    <div>
                        <!-- judul label autentikasi -->
                        <span class="block text-xs text-stone-500 uppercase tracking-wider font-semibold">Autentikasi</span>
                        <!-- nama paket autentikasi resmi yang dipake -->
                        <span class="text-sm font-semibold text-stone-800 mt-1 block">Laravel Breeze (Official)</span>
                    </div>
                </div>
            </section>

            <!-- bagian daftar artikel terbaru yang diambil dari database -->
            <section class="max-w-5xl mx-auto px-4 sm:px-6 py-12 border-t border-stone-200">
                <!-- header section artikel terbaru beserta link lihat semua -->
                <div class="flex items-center justify-between mb-8">
                    <!-- sisi kiri judul dan keterangan section -->
                    <div>
                        <!-- judul bagian artikel terbaru -->
                        <h2 class="text-xl font-bold text-stone-900 tracking-tight">
                            Artikel Terbaru
                        </h2>
                        <!-- keterangan sumber data artikel dari database mysql -->
                        <p class="text-xs text-stone-500 mt-0.5">
                            Postingan terkini yang tersimpan di database MySQL
                        </p>
                    </div>
                    <!-- link buat pindah ke halaman daftar semua artikel -->
                    <Link :href="route('posts.index')" class="text-xs sm:text-sm font-medium text-stone-800 hover:text-stone-600 underline">
                        Lihat semua →
                    </Link>
                </div>

                <!-- grid tiga kolom buat nampilin artikel terbaru kalo datanya ada -->
                <div v-if="recentPosts.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- looping setiap artikel terbaru jadi kartu -->
                    <article
                        v-for="(post, index) in recentPosts"
                        :key="post.id"
                        class="bg-white border border-stone-200 rounded-lg overflow-hidden flex flex-col justify-between hover:border-stone-400 transition-colors shadow-xs group"
                    >
                        <!-- bagian atas kartu berisi gambar sampul, id, judul, dan cuplikan isi -->
                        <div>
                            <!-- wadah gambar sampul kartu -->
                            <div class="aspect-video w-full overflow-hidden bg-stone-100">
                                <!-- gambar sampul artikel yang dipilih pake fungsi getPostImage -->
                                <img 
                                    :src="getPostImage(post.id, index)" 
                                    :alt="post.title" 
                                    class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300"
                                />
                            </div>

                            <!-- isi teks kartu artikel -->
                            <div class="p-5">
                                <!-- nomor id artikel dengan gaya font monospace -->
                                <span class="text-[11px] font-mono text-stone-400 block mb-1.5">#{{ post.id }}</span>
                                <!-- judul artikel yang dibatasi maksimal dua baris -->
                                <h3 class="font-bold text-stone-900 text-base leading-snug line-clamp-2">
                                    {{ post.title }}
                                </h3>
                                <!-- cuplikan isi artikel yang dibatasi maksimal tiga baris -->
                                <p class="text-xs text-stone-600 mt-2 line-clamp-3 leading-relaxed">
                                    {{ post.content }}
                                </p>
                            </div>
                        </div>

                        <!-- bagian kaki kartu buat tanggal rilis sama link baca -->
                        <div class="px-5 pb-5 pt-3 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                            <!-- tanggal artikel dibuat yang udah diformat ke bahasa indonesia -->
                            <span>{{ formatDate(post.created_at) }}</span>
                            <!-- link baca yang ngarahin ke halaman daftar artikel -->
                            <Link :href="route('posts.index')" class="font-medium text-stone-800 hover:underline">
                                Baca →
                            </Link>
                        </div>
                    </article>
                </div>

                <!-- tampilan kosong kalo belum ada satu pun artikel di database -->
                <div v-else class="text-center py-12 bg-white border border-dashed border-stone-300 rounded-lg">
                    <!-- pesan kalo daftar artikel masih kosong -->
                    <p class="text-sm text-stone-600">Belum ada artikel yang ditambahkan.</p>
                    <!-- link ajakan buat nulis artikel pertama -->
                    <Link :href="route('posts.create')" class="text-xs font-semibold text-stone-900 underline mt-1.5 inline-block">
                        Tambah Artikel Pertama
                    </Link>
                </div>
            </section>

            <!-- bagian penjelasan singkat arsitektur teknologi yang dipake proyek ini -->
            <section class="max-w-5xl mx-auto px-4 sm:px-6 py-12 border-t border-stone-200">
                <!-- wadah judul section arsitektur aplikasi -->
                <div class="mb-6">
                    <!-- judul bagian arsitektur aplikasi -->
                    <h2 class="text-lg font-bold text-stone-900 tracking-tight">
                        Arsitektur Aplikasi
                    </h2>
                </div>

                <!-- grid tiga kartu buat ngejelasin stack utama aplikasi -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- kartu pertama ngejelasin sistem login pake laravel breeze -->
                    <div class="bg-white border border-stone-200 rounded-lg p-5">
                        <!-- judul kartu pertama -->
                        <h3 class="text-sm font-bold text-stone-900">1. Laravel Breeze Auth</h3>
                        <!-- isi keterangan kartu pertama -->
                        <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">
                            Menggunakan starter kit resmi Laravel Breeze dengan form request LoginRequest dan session guard bawaan.
                        </p>
                    </div>

                    <!-- kartu kedua ngejelasin frontend pake inertia.js dan vue 3 -->
                    <div class="bg-white border border-stone-200 rounded-lg p-5">
                        <!-- judul kartu kedua -->
                        <h3 class="text-sm font-bold text-stone-900">2. Inertia.js & Vue 3</h3>
                        <!-- isi keterangan kartu kedua -->
                        <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">
                            Antarmuka SPA yang reaktif tanpa reload halaman, terhubung langsung ke controller Laravel.
                        </p>
                    </div>

                    <!-- kartu ketiga ngejelasin penyimpanan database mysql laragon -->
                    <div class="bg-white border border-stone-200 rounded-lg p-5">
                        <!-- judul kartu ketiga -->
                        <h3 class="text-sm font-bold text-stone-900">3. Database MySQL Laragon</h3>
                        <!-- isi keterangan kartu ketiga -->
                        <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">
                            Penyimpanan persisten pada database <code>laravel_inertia</code> menggunakan Eloquent ORM.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- footer bagian paling bawah halaman landing -->
        <footer class="border-t border-stone-200 bg-white py-8 text-xs text-stone-500">
            <!-- container isi footer di kiri teks identitas dan di kanan link pintasan -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <!-- teks nama aplikasi dan teknologi di kiri footer -->
                <div>
                    LaraPost • Laravel 12 + Laravel Breeze + Inertia.js
                </div>
                <!-- deretan link navigasi cepat di kanan footer -->
                <div class="flex items-center gap-4">
                    <!-- link menuju halaman login -->
                    <Link :href="route('login')" class="hover:text-stone-900">Login</Link>
                    <!-- link menuju halaman pendaftaran akun -->
                    <Link :href="route('register')" class="hover:text-stone-900">Register</Link>
                    <!-- link menuju halaman daftar artikel -->
                    <Link :href="route('posts.index')" class="hover:text-stone-900">Artikel</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
