<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const isMobileMenuOpen = ref(false)
const showFlash = ref(true)

const page = usePage()
const authUser = computed(() => page.props.auth?.user)
const flashMessage = computed(() => page.props.flash?.message)

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <div class="min-h-screen flex flex-col bg-stone-50 text-stone-900 font-sans selection:bg-stone-200">
        <!-- Top Navigation Bar (Matching Landing Page Stone Theme) -->
        <header class="sticky top-0 z-40 bg-white/80 border-b border-stone-200 backdrop-blur-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-16">
                    <!-- Brand -->
                    <div class="flex items-center gap-8">
                        <Link :href="route('home')" class="font-bold text-lg tracking-tight text-stone-900 hover:text-stone-700 transition-colors">
                            LaraPost
                        </Link>

                        <!-- Desktop Navigation Links -->
                        <nav class="hidden md:flex items-center gap-1">
                            <Link 
                                :href="route('home')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url === '/' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                Beranda
                            </Link>

                            <Link 
                                :href="route('posts.index')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url.startsWith('/posts') && $page.url !== '/posts/create' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                Artikel
                            </Link>

                            <Link 
                                v-if="authUser"
                                :href="route('posts.create')" 
                                class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors"
                                :class="$page.url === '/posts/create' ? 'bg-stone-200/70 text-stone-900 font-semibold' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100'"
                            >
                                + Tulis Baru
                            </Link>

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

                    <!-- Right Controls -->
                    <div class="flex items-center gap-3">
                        <template v-if="authUser">
                            <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-md bg-stone-100 border border-stone-200 text-xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <span class="font-medium text-stone-800">{{ authUser.name }}</span>
                            </div>

                            <Link 
                                :href="route('profile.edit')"
                                class="hidden sm:inline-flex text-xs font-medium text-stone-600 hover:text-stone-900 transition-colors"
                            >
                                Profil
                            </Link>

                            <button 
                                @click="logout"
                                type="button"
                                class="text-xs font-medium text-stone-500 hover:text-stone-900 transition-colors cursor-pointer"
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

                        <!-- Mobile Hamburger Button -->
                        <button 
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            type="button"
                            class="md:hidden p-1.5 rounded-md text-stone-600 hover:bg-stone-100"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Drawer -->
                <div v-show="isMobileMenuOpen" class="md:hidden py-3 border-t border-stone-200 space-y-1 text-sm">
                    <Link :href="route('home')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">Beranda</Link>
                    <Link :href="route('posts.index')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">Artikel</Link>
                    <Link v-if="authUser" :href="route('posts.create')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">+ Tulis Baru</Link>
                    <Link v-if="authUser" :href="route('dashboard')" @click="isMobileMenuOpen = false" class="block px-3 py-1.5 hover:bg-stone-100 rounded-md">Dashboard</Link>
                </div>
            </div>
        </header>

        <!-- Flash Notification (Clean & Minimal) -->
        <div v-if="flashMessage && showFlash" class="bg-stone-900 text-white text-xs py-2.5 px-4">
            <div class="max-w-5xl mx-auto flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span>✓</span>
                    <span>{{ flashMessage }}</span>
                </span>
                <button @click="showFlash = false" class="text-stone-400 hover:text-white cursor-pointer text-sm">
                    ✕
                </button>
            </div>
        </div>

        <!-- Main Page Container -->
        <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-10">
            <slot />
        </main>

        <!-- Minimal Footer Matching Landing Page -->
        <footer class="border-t border-stone-200 bg-white py-8 text-xs text-stone-500 mt-auto">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div>
                    LaraPost • Laravel 12 + Laravel Breeze + Inertia.js
                </div>
                <div class="flex items-center gap-4">
                    <Link :href="route('home')" class="hover:text-stone-900">Beranda</Link>
                    <Link :href="route('posts.index')" class="hover:text-stone-900">Artikel</Link>
                    <Link v-if="!authUser" :href="route('login')" class="hover:text-stone-900">Login</Link>
                    <Link v-else :href="route('dashboard')" class="hover:text-stone-900">Dashboard</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
