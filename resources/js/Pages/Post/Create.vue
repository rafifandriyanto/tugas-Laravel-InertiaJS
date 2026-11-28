<script setup>
// ambil layout utama buat halaman artikel
import Layout from '../../Layouts/App.vue'
// ambil Link buat pindah halaman tanpa reload sama useForm buat ngatur form inertia
import { Link, useForm } from '@inertiajs/vue3'

// tangkep props yang dikirim dari controller laravel
defineProps({
    // prop errors buat nampung pesan error validasi dari backend
    errors: {
        // tipe datanya berupa objek
        type: Object,
        // kasih nilai default objek kosong kalo belum ada error
        default: () => ({})
    }
})

// bikin state form artikel baru pake useForm
const form = useForm({
    // field judul artikel awalnya dikosongin
    title: '',
    // field isi konten artikel awalnya dikosongin
    content: ''
})

// fungsi buat ngirim data artikel baru pas form disubmit
const submit = () => {
    // kirim data form pake method post ke endpoint /posts
    form.post('/posts')
}
</script>

<template>
    <!-- bungkus seluruh halaman bikin artikel pake layout utama -->
    <Layout>
        <!-- pembungkus tengah biar lebar form maksimal 2xl dan ada jarak antar blok -->
        <div class="max-w-2xl mx-auto space-y-4">
            <!-- navigasi breadcrumb buat balik ke halaman daftar artikel -->
            <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
                <!-- link kembali ke daftar semua artikel -->
                <Link :href="route('posts.index')" class="hover:text-stone-900 transition-colors">
                    ← Kembali ke Semua Artikel
                </Link>
            </nav>

            <!-- kartu utama pembungkus form tulis artikel baru -->
            <div class="bg-white rounded-lg border border-stone-200 shadow-xs overflow-hidden">
                <!-- bagian atas kartu buat judul dan keterangan form -->
                <div class="p-6 border-b border-stone-200">
                    <!-- judul halaman tambah artikel -->
                    <h1 class="text-xl font-bold text-stone-900">
                        Tulis Artikel Baru
                    </h1>
                    <!-- keterangan info kalo artikel bakal disimpen ke tabel posts mysql -->
                    <p class="text-xs text-stone-500 mt-1">
                        Artikel akan tersimpan secara langsung di tabel <strong>posts</strong> database MySQL.
                    </p>
                </div>

                <!-- form tambah artikel yang cegah reload terus manggil fungsi submit -->
                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <!-- blok input buat judul artikel -->
                    <div>
                        <!-- baris atas buat label judul sama penghitung jumlah karakter -->
                        <div class="flex items-center justify-between mb-1">
                            <!-- label judul artikel dengan tanda wajib diisi -->
                            <label class="block text-xs font-semibold text-stone-800">
                                Judul Artikel <span class="text-red-500">*</span>
                            </label>
                            <!-- nampilin jumlah karakter judul yang udah diketik -->
                            <span class="text-[11px] font-mono text-stone-400">
                                {{ form.title.length }} karakter
                            </span>
                        </div>
                        <!-- kotak input teks judul yang nyambung ke form.title dan bordernya merah kalo ada error -->
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            autofocus
                            placeholder="Contoh: Menulis Kode Bersih dan Terstruktur"
                            class="w-full px-3.5 py-2.5 rounded-md bg-stone-50 border border-stone-300 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors"
                            :class="errors.title ? 'border-red-500' : ''"
                        />
                        <!-- nampilin pesan error validasi judul kalo ada error dari server -->
                        <p v-if="errors.title" class="text-xs text-red-600 mt-1">
                            {{ errors.title }}
                        </p>
                    </div>

                    <!-- blok input buat isi konten artikel -->
                    <div>
                        <!-- baris atas buat label konten sama penghitung jumlah karakter -->
                        <div class="flex items-center justify-between mb-1">
                            <!-- label isi konten artikel dengan tanda bintang wajib diisi -->
                            <label class="block text-xs font-semibold text-stone-800">
                                Isi Konten Artikel <span class="text-red-500">*</span>
                            </label>
                            <!-- nampilin jumlah karakter isi konten secara live -->
                            <span class="text-[11px] font-mono text-stone-400">
                                {{ form.content.length }} karakter
                            </span>
                        </div>
                        <!-- kotak textarea konten yang nyambung ke form.content dan bordernya merah kalo error -->
                        <textarea
                            v-model="form.content"
                            rows="7"
                            required
                            placeholder="Tuliskan isi pemikiran atau artikel Anda secara lengkap di sini..."
                            class="w-full px-3.5 py-2.5 rounded-md bg-stone-50 border border-stone-300 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors resize-y leading-relaxed"
                            :class="errors.content ? 'border-red-500' : ''"
                        ></textarea>
                        <!-- nampilin pesan error validasi konten kalo ada error dari backend -->
                        <p v-if="errors.content" class="text-xs text-red-600 mt-1">
                            {{ errors.content }}
                        </p>
                    </div>

                    <!-- baris bawah form buat tombol batal, reset, sama simpan -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                        <!-- link batal yang ngarahin balik ke daftar artikel -->
                        <Link
                            :href="route('posts.index')"
                            class="px-3 py-1.5 rounded-md text-xs font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors"
                        >
                            Batal
                        </Link>

                        <!-- wadah buat tombol reset dan simpan di sebelah kanan -->
                        <div class="flex items-center gap-2">
                            <!-- tombol reset buat ngosongin lagi semua isi form -->
                            <button
                                type="button"
                                @click="form.reset()"
                                class="px-3 py-1.5 rounded-md text-xs font-medium text-stone-500 hover:text-stone-800 cursor-pointer"
                            >
                                Reset
                            </button>

                            <!-- tombol submit yang dikunci pas proses simpan dan ganti teks jadi Menyimpan... -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 rounded-md text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 disabled:opacity-50 transition-colors cursor-pointer shadow-xs"
                            >
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Artikel' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>
