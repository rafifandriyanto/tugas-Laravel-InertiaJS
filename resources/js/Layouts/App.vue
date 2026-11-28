<script setup>
// ambil ref sama computed dari vue buat ngatur state lokal dan data turunan
import { ref, computed } from 'vue'
// ambil Link, usePage, sama router dari inertia buat navigasi, baca props global, sama kirim request logout
import { Link, usePage, router } from '@inertiajs/vue3'

// state buat ngatur menu navigasi versi hp lagi kebuka atau ketutup
const isMobileMenuOpen = ref(false)
// state buat nampilin atau nutup notifikasi flash message
const showFlash = ref(true)

// ambil objek halaman inertia sekarang buat akses shared props dari laravel
const page = usePage()
// ambil data user yang lagi login dari shared props auth, kalo belum login hasilnya undefined
const authUser = computed(() => page.props.auth?.user)
// ambil pesan flash dari session laravel kalo habis bikin aksi kayak simpan atau hapus data
const flashMessage = computed(() => page.props.flash?.message)

// fungsi buat logout dengan ngirim request post ke route logout
const logout = () => {
    // kirim request post ke route logout bawaan laravel
    router.post(route('logout'))
}
</script>

<template>
    <!-- pembungkus utama layout aplikasi biar tinggi minimalnya satu layar penuh -->
    <div class="min-h-screen flex flex-col bg-stone-50 text-stone-900 font-sans selection:bg-stone-200">
        <!-- navbar atas yang nempel di atas layar dengan efek blur -->
        <header class="sticky top-0 z-40 bg-white/80 border-b border-stone-200 backdrop-blur-sm">
            <!-- wadah pembatas lebar isi navbar biar rata tengah -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6">
                <!-- baris flex buat misahin logo dan menu di kiri sama tombol akun di kanan -->
                <div class="flex items-center justify-between h-16">
                    <!-- kelompok sebelah kiri isi brand aplikasi sama menu navigasi utama -->
                    <div class="flex items-center gap-8">
                        <!-- link logo teks LaraPost yang ngarah balik ke beranda -->
                        <Link :href="route('home')" class="font-bold text-lg tracking-tight text-stone-900 hover:text-stone-700 transition-colors">
                            LaraPost
                        </Link>

                        <!-- daftar menu navigasi khusus tampilan desktop -->
                        <nav class="hidden md:flex items-center gap-1">
                            <!-- link menu beranda, nyala tebel kalo url sekarang lagi di '/' -->
                            <Link 
                                :href="route('home')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url === '/' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                Beranda
                            </Link>

                            <!-- link menu artikel, nyala tebel kalo lagi buka halaman daftar, detail, atau edit artikel -->
                            <Link 
                                :href="route('posts.index')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url.startsWith('/posts') && $page.url !== '/posts/create' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                Artikel
                            </Link>

                            <!-- link buat nulis artikel baru, cuma ditampilin kalo user udah login -->
                            <Link 
                                v-if="authUser"
                                :href="route('posts.create')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url === '/posts/create' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                + Tulis Baru
                            </Link>

                            <!-- link menu dashboard, cuma muncul buat user yang udah login -->
                            <Link 
                                v-if="authUser"
                                :href="route('dashboard')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url === '/dashboard' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                Dashboard
                            </Link>
                        </nav>
                    </div>

                    <!-- kelompok tombol kontrol di pojok kanan navbar -->
                    <div class="flex items-center gap-3">
                        <!-- bagian menu kanan khusus kalo user udah login -->
                        <template v-if="authUser">
                            <!-- badge kecil nampilin nama user yang lagi login beserta titik ijo status aktif -->
                            <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-md bg-stone-100 border border-stone-200 text-xs">
                                <!-- titik bulat warna hijau penanda status akun lagi aktif -->
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <!-- nampilin nama user yang lagi login -->
                                <span class="font-medium text-stone-800">{{ authUser.name }}</span>
                            </div>

                            <!-- link menuju halaman edit profil user -->
                            <Link 
                                :href="route('profile.edit')"
                                class="hidden sm:inline-flex text-xs font-medium text-stone-600 hover:text-stone-900 transition-colors"
                            >
                                Profil
                            </Link>

                            <!-- tombol keluar yang manggil fungsi logout pas diklik -->
                            <button 
                                @click="logout"
                                type="button"
                                class="text-xs font-medium text-stone-500 hover:text-stone-900 transition-colors cursor-pointer"
                            >
                                Keluar
                            </button>
                        </template>

                        <!-- bagian menu kanan kalo user belum login -->
                        <template v-else>
                            <!-- tombol link buat pindah ke halaman login -->
                            <Link 
                                :href="route('login')" 
                                class="text-xs sm:text-sm font-medium text-stone-600 hover:text-stone-900 px-3 py-1.5 transition-colors"
                            >
                                Masuk
                            </Link>
                            <!-- tombol link buat pindah ke halaman daftar akun baru -->
                            <Link 
                                :href="route('register')" 
                                class="text-xs sm:text-sm font-medium text-white bg-stone-900 hover:bg-stone-800 px-3.5 py-1.5 rounded-md transition-colors"
                            >
                                Daftar
                            </Link>
                        </template>

                        <!-- tombol garis tiga di layar hp buat buka-tutup menu mobile -->
                        <button 
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            type="button"
                            class="md:hidden p-1.5 rounded-md text-stone-600 hover:bg-stone-100"
                        >
                            <!-- ikon garis tiga hamburger pake svg -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <!-- tiga garis horizontal pembentuk ikon menu -->
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- laci menu navigasi versi hp yang muncul pas tombol hamburger diklik -->
                <div v-show="isMobileMenuOpen" class="md:hidden py-3 border-t border-stone-200 space-y-1 text-sm">
                    <!-- link beranda versi mobile yang langsung nutup menu pas diklik -->
                    <Link :href="route('home')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">Beranda</Link>
                    <!-- link artikel versi mobile yang langsung nutup menu pas diklik -->
                    <Link :href="route('posts.index')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">Artikel</Link>
                    <!-- link tulis artikel baru versi mobile khusus user yang udah login -->
                    <Link v-if="authUser" :href="route('posts.create')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">+ Tulis Baru</Link>
                    <!-- link dashboard versi mobile khusus user yang udah login -->
                    <Link v-if="authUser" :href="route('dashboard')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">Dashboard</Link>
                </div>
            </div>
        </header>

        <!-- banner notifikasi flash message yang muncul kalo ada pesan dari server dan belum ditutup -->
        <div v-if="flashMessage && showFlash" class="bg-stone-900 text-white text-xs py-2.5 px-4">
            <!-- baris isi notifikasi biar teks di kiri dan tombol silang di kanan -->
            <div class="max-w-5xl mx-auto flex items-center justify-between">
                <!-- wadah ikon centang sama teks notifikasi -->
                <span class="flex items-center gap-2">
                    <!-- simbol centang di samping pesan notifikasi -->
                    <span>✓</span>
                    <!-- teks isi pesan notifikasi dari session flash -->
                    <span>{{ flashMessage }}</span>
                </span>
                <!-- tombol silang buat nutup banner notifikasi -->
                <button @click="showFlash = false" class="text-stone-400 hover:text-white cursor-pointer text-sm">
                    ✕
                </button>
            </div>
        </div>

        <!-- area konten utama halaman yang ngisi sisa tinggi layar -->
        <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-10">
            <!-- slot tempat konten halaman masing-masing page ditampilin -->
            <slot />
        </main>

        <!-- bagian kaki halaman di paling bawah layout -->
        <footer class="border-t border-stone-200 bg-white py-8 text-xs text-stone-500 mt-auto">
            <!-- pembungkus isi footer biar sejajar kiri kanan di layar lebar -->
            <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <!-- info nama aplikasi sama stack teknologi yang dipake -->
                <div>
                    LaraPost • Laravel 12 + Laravel Breeze + Inertia.js
                </div>
                <!-- kumpulan link cepat di sisi kanan footer -->
                <div class="flex items-center gap-4">
                    <!-- link balik ke halaman beranda -->
                    <Link :href="route('home')" class="hover:text-stone-900">Beranda</Link>
                    <!-- link ke halaman daftar artikel -->
                    <Link :href="route('posts.index')" class="hover:text-stone-900">Artikel</Link>
                    <!-- link login di footer kalo user belum masuk -->
                    <Link v-if="!authUser" :href="route('login')" class="hover:text-stone-900">Login</Link>
                    <!-- link dashboard di footer kalo user udah masuk -->
                    <Link v-else :href="route('dashboard')" class="hover:text-stone-900">Dashboard</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
