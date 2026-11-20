<script setup>
import { computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    recentPosts: {
        type: Array,
        default: () => []
    },
    stats: {
        type: Object,
        default: () => ({
            total_posts: 0,
            total_users: 0,
            last_updated: 'Hari ini'
        })
    }
})

const page = usePage()
const authUser = computed(() => page.props.auth?.user)

const logout = () => {
    router.post(route('logout'))
}

const getPostImage = (id, index) => {
    const images = [
        '/images/post_technology.jpg',
        '/images/post_architecture.jpg',
        '/images/hero_editorial.jpg'
    ]
    return images[(id || index) % images.length]
}

const formatDate = (dateString) => {
    if (!dateString) return 'Baru Saja'
    try {
        const date = new Date(dateString)
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(date)
    } catch {
        return dateString
    }
}
</script>

<template>
    <Head title="LaraPost — Publikasi Artikel & Gagasan" />

    <div class="min-h-screen bg-stone-50 text-stone-900 font-sans selection:bg-stone-200">
        
        <!-- Header Nav (Clean, Minimal, Neutral) -->
        <header class="border-b border-stone-200 bg-white/80 backdrop-blur-sm sticky top-0 z-30">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                
                <!-- Brand -->
                <div class="flex items-center gap-6">
                    <Link :href="route('home')" class="font-bold text-lg tracking-tight text-stone-900 hover:text-stone-700 transition-colors">
                        LaraPost
                    </Link>
                    <nav class="hidden sm:flex items-center gap-5 text-sm text-stone-600">
                        <Link :href="route('posts.index')" class="hover:text-stone-900 transition-colors">
                            Artikel
                        </Link>
                    </nav>
                </div>

                <!-- Right Auth Controls -->
                <div class="flex items-center gap-3">
                    <template v-if="authUser">
                        <Link
                            :href="route('dashboard')"
                            class="text-xs sm:text-sm font-medium text-stone-700 hover:text-stone-900 transition-colors"
                        >
                            Dashboard
                        </Link>
                        <button
                            @click="logout"
                            type="button"
                            class="text-xs sm:text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors cursor-pointer"
                        >
                            Keluar
                        </button>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-xs sm:text-sm font-medium text-stone-600 hover:text-stone-900 px-3 py-1.5 transition-colors"
                        >
                            Masuk
                        </Link>
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

        <!-- Hero Section (Editorial, Grounded, Zero Neon) -->
        <main>
            <section class="max-w-5xl mx-auto px-4 sm:px-6 pt-14 pb-12">
                <div class="max-w-3xl">
                    <p class="text-xs font-semibold tracking-wider uppercase text-stone-500 mb-3">
                        Platform Pengelolaan Artikel
                    </p>
                    <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-stone-950 leading-[1.15]">
                        Tulis, kelola, dan publikasikan artikel dengan sederhana.
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-stone-600 leading-relaxed max-w-2xl">
                        Aplikasi modern berbasis Laravel 12 dan Inertia.js dengan sistem otentikasi resmi Laravel Breeze serta database MySQL.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <Link
                            :href="route('posts.index')"
                            class="inline-flex items-center px-5 py-2.5 rounded-md bg-stone-900 hover:bg-stone-800 text-white font-medium text-sm transition-colors shadow-xs"
                        >
                            Jelajahi Artikel
                        </Link>

                        <Link
                            v-if="!authUser"
                            :href="route('login')"
                            class="inline-flex items-center px-4 py-2.5 rounded-md border border-stone-300 hover:bg-stone-100 text-stone-800 font-medium text-sm transition-colors"
                        >
                            Masuk ke Akun
                        </Link>

                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="inline-flex items-center px-4 py-2.5 rounded-md border border-stone-300 hover:bg-stone-100 text-stone-800 font-medium text-sm transition-colors"
                        >
                            Buka Dashboard
                        </Link>
                    </div>
                </div>

                <!-- Editorial Hero Image -->
                <div class="mt-10 overflow-hidden rounded-xl border border-stone-200 shadow-sm bg-stone-100">
                    <img 
                        src="/images/hero_editorial.jpg" 
                        alt="Editorial Workspace" 
                        class="w-full h-64 sm:h-80 md:h-96 object-cover"
                    />
                </div>

                <!-- Subtle Info Bar -->
                <div class="mt-10 pt-8 border-t border-stone-200 grid grid-cols-2 sm:grid-cols-3 gap-6 text-left">
                    <div>
                        <span class="block text-xs text-stone-500 uppercase tracking-wider font-semibold">Total Artikel</span>
                        <span class="text-2xl font-bold text-stone-900 mt-0.5 block">{{ stats.total_posts }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-stone-500 uppercase tracking-wider font-semibold">Penulis Terdaftar</span>
                        <span class="text-2xl font-bold text-stone-900 mt-0.5 block">{{ stats.total_users }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-stone-500 uppercase tracking-wider font-semibold">Autentikasi</span>
                        <span class="text-sm font-semibold text-stone-800 mt-1 block">Laravel Breeze (Official)</span>
                    </div>
                </div>
            </section>

            <!-- Articles Section -->
            <section class="max-w-5xl mx-auto px-4 sm:px-6 py-12 border-t border-stone-200">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-stone-900 tracking-tight">
                            Artikel Terbaru
                        </h2>
                        <p class="text-xs text-stone-500 mt-0.5">
                            Postingan terkini yang tersimpan di database MySQL
                        </p>
                    </div>
                    <Link :href="route('posts.index')" class="text-xs sm:text-sm font-medium text-stone-800 hover:text-stone-600 underline">
                        Lihat semua →
                    </Link>
                </div>

                <div v-if="recentPosts.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <article
                        v-for="(post, index) in recentPosts"
                        :key="post.id"
                        class="bg-white border border-stone-200 rounded-lg overflow-hidden flex flex-col justify-between hover:border-stone-400 transition-colors shadow-xs group"
                    >
                        <div>
                            <!-- Card Image -->
                            <div class="aspect-video w-full overflow-hidden bg-stone-100">
                                <img 
                                    :src="getPostImage(post.id, index)" 
                                    :alt="post.title" 
                                    class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300"
                                />
                            </div>

                            <div class="p-5">
                                <span class="text-[11px] font-mono text-stone-400 block mb-1.5">#{{ post.id }}</span>
                                <h3 class="font-bold text-stone-900 text-base leading-snug line-clamp-2">
                                    {{ post.title }}
                                </h3>
                                <p class="text-xs text-stone-600 mt-2 line-clamp-3 leading-relaxed">
                                    {{ post.content }}
                                </p>
                            </div>
                        </div>

                        <div class="px-5 pb-5 pt-3 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                            <span>{{ formatDate(post.created_at) }}</span>
                            <Link :href="route('posts.index')" class="font-medium text-stone-800 hover:underline">
                                Baca →
                            </Link>
                        </div>
                    </article>
                </div>

                <div v-else class="text-center py-12 bg-white border border-dashed border-stone-300 rounded-lg">
                    <p class="text-sm text-stone-600">Belum ada artikel yang ditambahkan.</p>
                    <Link :href="route('posts.create')" class="text-xs font-semibold text-stone-900 underline mt-1.5 inline-block">
                        Tambah Artikel Pertama
                    </Link>
                </div>
            </section>

            <!-- Technical Summary Section (Clean Editorial) -->
            <section class="max-w-5xl mx-auto px-4 sm:px-6 py-12 border-t border-stone-200">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-stone-900 tracking-tight">
                        Arsitektur Aplikasi
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="bg-white border border-stone-200 rounded-lg p-5">
                        <h3 class="text-sm font-bold text-stone-900">1. Laravel Breeze Auth</h3>
                        <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">
                            Menggunakan starter kit resmi Laravel Breeze dengan form request LoginRequest dan session guard bawaan.
                        </p>
                    </div>

                    <div class="bg-white border border-stone-200 rounded-lg p-5">
                        <h3 class="text-sm font-bold text-stone-900">2. Inertia.js & Vue 3</h3>
                        <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">
                            Antarmuka SPA yang reaktif tanpa reload halaman, terhubung langsung ke controller Laravel.
                        </p>
                    </div>

                    <div class="bg-white border border-stone-200 rounded-lg p-5">
                        <h3 class="text-sm font-bold text-stone-900">3. Database MySQL Laragon</h3>
                        <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">
                            Penyimpanan persisten pada database <code>laravel_inertia</code> menggunakan Eloquent ORM.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-stone-200 bg-white py-8 text-xs text-stone-500">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div>
                    LaraPost • Laravel 12 + Laravel Breeze + Inertia.js
                </div>
                <div class="flex items-center gap-4">
                    <Link :href="route('login')" class="hover:text-stone-900">Login</Link>
                    <Link :href="route('register')" class="hover:text-stone-900">Register</Link>
                    <Link :href="route('posts.index')" class="hover:text-stone-900">Artikel</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
