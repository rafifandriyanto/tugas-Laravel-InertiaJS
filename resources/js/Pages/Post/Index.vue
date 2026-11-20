<script setup>
import { ref, computed } from 'vue'
import Layout from '../../Layouts/App.vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    posts: {
        type: Array,
        default: () => []
    }
})

const page = usePage()
const authUser = computed(() => page.props.auth?.user)

// Search state
const searchQuery = ref('')

// Filtered posts based on search query
const filteredPosts = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.posts
    }
    const q = searchQuery.value.toLowerCase()
    return props.posts.filter(post => 
        (post.title && post.title.toLowerCase().includes(q)) ||
        (post.content && post.content.toLowerCase().includes(q))
    )
})

// Image helper
const getPostImage = (id, index) => {
    const images = [
        '/images/post_technology.jpg',
        '/images/post_architecture.jpg',
        '/images/hero_editorial.jpg'
    ]
    return images[(id || index) % images.length]
}

// Delete confirmation modal state
const postToDelete = ref(null)
const isDeleting = ref(false)

const confirmDelete = (post) => {
    postToDelete.value = post
}

const cancelDelete = () => {
    postToDelete.value = null
}

const executeDelete = () => {
    if (!postToDelete.value) return
    isDeleting.value = true
    router.delete(`/posts/${postToDelete.value.id}`, {
        onFinish: () => {
            isDeleting.value = false
            postToDelete.value = null
        }
    })
}

// Format date helper
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
    <Layout>
        <div class="space-y-8">
            <!-- Guest Notice Banner if unauthenticated -->
            <div 
                v-if="!authUser"
                class="p-4 rounded-lg bg-stone-100 border border-stone-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-stone-800 text-xs"
            >
                <div class="flex items-center gap-2.5">
                    <span class="text-sm">🔒</span>
                    <div>
                        <span class="font-semibold block sm:inline">Mode Pratinjau Tamu:</span>
                        <span class="text-stone-600 sm:ml-1">Anda dapat membaca semua artikel. Untuk membuat, mengedit, atau menghapus postingan, silakan login.</span>
                    </div>
                </div>
                <Link
                    :href="route('login')"
                    class="shrink-0 px-3 py-1.5 rounded-md text-xs font-medium text-white bg-stone-900 hover:bg-stone-800 transition-colors"
                >
                    Masuk ke Akun
                </Link>
            </div>

            <!-- Page Header (Clean Editorial) -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-2 border-b border-stone-200">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-stone-500 block mb-1">
                        Koleksi Publikasi
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-950">
                        Daftar Postingan Artikel
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-600 mt-1">
                        Total {{ posts.length }} artikel tersimpan di database MySQL (Laragon).
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        v-if="authUser"
                        :href="route('posts.create')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 transition-colors shadow-xs"
                    >
                        <span>+ Tulis Artikel Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Search Bar & Filters -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="relative w-full sm:max-w-xs">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari judul atau isi artikel..."
                        class="w-full px-3.5 py-2 rounded-md bg-white border border-stone-300 text-stone-900 text-xs placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors shadow-2xs"
                    />
                    <span v-if="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-2 text-xs text-stone-400 hover:text-stone-700 cursor-pointer">
                        ✕
                    </span>
                </div>

                <div class="text-xs text-stone-500">
                    Menampilkan <strong>{{ filteredPosts.length }}</strong> dari {{ posts.length }} postingan
                </div>
            </div>

            <!-- Posts Grid with Editorial Images -->
            <div v-if="filteredPosts.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <article
                    v-for="(post, index) in filteredPosts"
                    :key="post.id"
                    class="bg-white border border-stone-200 rounded-lg overflow-hidden flex flex-col justify-between hover:border-stone-400 transition-colors shadow-xs group"
                >
                    <div>
                        <!-- Post Cover Image -->
                        <div class="aspect-video w-full overflow-hidden bg-stone-100">
                            <img 
                                :src="getPostImage(post.id, index)" 
                                :alt="post.title" 
                                class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300"
                            />
                        </div>

                        <!-- Card Body -->
                        <div class="p-5">
                            <div class="flex items-center justify-between text-[11px] font-mono text-stone-400 mb-2">
                                <span>#{{ post.id }}</span>
                                <span>{{ formatDate(post.created_at) }}</span>
                            </div>

                            <h2 class="font-bold text-stone-900 text-base leading-snug line-clamp-2">
                                {{ post.title }}
                            </h2>

                            <p class="text-xs text-stone-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ post.content }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="px-5 pb-5 pt-3 border-t border-stone-100 flex items-center justify-between text-xs">
                        <template v-if="authUser">
                            <div class="flex items-center gap-3">
                                <Link
                                    :href="`/posts/${post.id}/edit`"
                                    class="text-xs font-medium text-stone-700 hover:text-stone-950 underline"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    @click="confirmDelete(post)"
                                    class="text-xs font-medium text-red-600 hover:text-red-800 underline cursor-pointer"
                                >
                                    Hapus
                                </button>
                            </div>
                            <span class="text-[11px] text-stone-400 font-mono">Tersimpan</span>
                        </template>

                        <template v-else>
                            <span class="text-[11px] text-stone-400">Mode Baca</span>
                            <Link :href="route('login')" class="text-xs font-medium text-stone-800 hover:underline">
                                Login untuk kelola
                            </Link>
                        </template>
                    </div>
                </article>
            </div>

            <!-- Empty Search State -->
            <div v-else class="text-center py-14 bg-white border border-dashed border-stone-300 rounded-lg">
                <p class="text-sm font-medium text-stone-700">Tidak ada artikel yang cocok dengan pencarian.</p>
                <button
                    v-if="searchQuery"
                    type="button"
                    @click="searchQuery = ''"
                    class="mt-2 text-xs text-stone-900 underline font-medium cursor-pointer"
                >
                    Reset Pencarian
                </button>
            </div>

            <!-- Delete Confirmation Modal (Clean Stone) -->
            <div v-if="postToDelete" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/40 backdrop-blur-2xs">
                <div class="bg-white rounded-lg border border-stone-200 max-w-sm w-full p-6 shadow-lg">
                    <h3 class="text-base font-bold text-stone-900">
                        Hapus Artikel?
                    </h3>
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Apakah Anda yakin ingin menghapus artikel <strong>"{{ postToDelete.title }}"</strong>? Tindakan ini akan menghapus data dari database MySQL secara permanen.
                    </p>

                    <div class="mt-6 flex items-center justify-end gap-3 text-xs">
                        <button
                            type="button"
                            @click="cancelDelete"
                            :disabled="isDeleting"
                            class="px-3 py-2 rounded-md border border-stone-300 text-stone-700 hover:bg-stone-50 cursor-pointer font-medium"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            @click="executeDelete"
                            :disabled="isDeleting"
                            class="px-3.5 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-medium cursor-pointer"
                        >
                            {{ isDeleting ? 'Menghapus...' : 'Ya, Hapus' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>