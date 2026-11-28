<script setup>
// ambil layout utama buat halaman artikel
import Layout from '../../Layouts/App.vue'
// ambil Link buat pindah halaman tanpa reload sama useForm buat ngatur form edit
import { Link, useForm } from '@inertiajs/vue3'

// tangkep props data post sama error validasi yang dikirim controller
const props = defineProps({
    // prop post isinya data artikel yang mau diedit
    post: {
        // tipe datanya wajib objek
        type: Object,
        // wajib dikirim dari server biar halaman edit ada isinya
        required: true
    },
    // prop errors buat nampung pesan error validasi dari backend
    errors: {
        // tipe datanya objek
        type: Object,
        // kasih nilai default objek kosong kalo ga ada error
        default: () => ({})
    }
})

// bikin state form edit pake useForm dan langsung isi dari data artikel lama
const form = useForm({
    // isi awal judul diambil dari props.post.title atau string kosong kalo ga ada
    title: props.post.title || '',
    // isi awal konten diambil dari props.post.content atau string kosong kalo ga ada
    content: props.post.content || ''
})

// fungsi buat nyimpen perubahan artikel pas form disubmit
const submit = () => {
    // kirim request put ke endpoint update artikel sesuai id-nya
    form.put(`/posts/${props.post.id}`)
}

// fungsi buat balikin isi form ke data awal artikel sebelum diedit
const resetToOriginal = () => {
    // set ulang judul form ke judul asli dari props
    form.title = props.post.title || ''
    // set ulang isi konten form ke konten asli dari props
    form.content = props.post.content || ''
}
</script>

<template>
    <!-- bungkus seluruh halaman edit artikel pake layout utama -->
    <Layout>
        <!-- pembungkus tengah biar lebar form maksimal 2xl dan ada jarak antar bagian -->
        <div class="max-w-2xl mx-auto space-y-4">
            <!-- navigasi breadcrumb buat balik ke daftar artikel dan nandain id artikel yang lagi diedit -->
            <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
                <!-- link kembali ke semua artikel -->
                <Link :href="route('posts.index')" class="hover:text-stone-900 transition-colors">
                    ← Kembali ke Semua Artikel
                </Link>
                <!-- pemisah breadcrumb -->
                <span>/</span>
                <!-- teks penanda artikel mana yang lagi diedit -->
                <span class="text-stone-800">Edit Artikel #{{ post.id }}</span>
            </nav>

            <!-- kartu pembungkus form edit artikel -->
            <div class="bg-white rounded-lg border border-stone-200 shadow-xs overflow-hidden">
                <!-- bagian atas kartu buat judul, keterangan, sama badge id artikel -->
                <div class="p-6 border-b border-stone-200 flex items-center justify-between">
                    <!-- sisi kiri buat judul dan subjudul -->
                    <div>
                        <!-- judul halaman edit artikel -->
                        <h1 class="text-xl font-bold text-stone-900">
                            Edit Artikel
                        </h1>
                        <!-- keterangan kalo perubahan bakal langsung ke-update di mysql -->
                        <p class="text-xs text-stone-500 mt-1">
                            Perubahan akan langsung disimpan ke database MySQL.
                        </p>
                    </div>
                    <!-- badge kecil buat nampilin id artikel -->
                    <span class="px-2.5 py-0.5 text-xs font-mono font-semibold rounded bg-stone-100 text-stone-700">
                        ID: {{ post.id }}
                    </span>
                </div>

                <!-- form edit artikel yang cegah reload terus manggil fungsi submit -->
                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <!-- blok input buat edit judul artikel -->
                    <div>
                        <!-- baris atas buat label judul sama penghitung jumlah karakter -->
                        <div class="flex items-center justify-between mb-1">
                            <!-- label judul artikel dengan tanda wajib diisi -->
                            <label class="block text-xs font-semibold text-stone-800">
                                Judul Artikel <span class="text-red-500">*</span>
                            </label>
                            <!-- nampilin jumlah karakter judul sekarang -->
                            <span class="text-[11px] font-mono text-stone-400">
                                {{ form.title.length }} karakter
                            </span>
                        </div>
                        <!-- kotak input teks judul yang nyambung ke form.title -->
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="Masukkan Judul Artikel..."
                            class="w-full px-3.5 py-2.5 rounded-md bg-stone-50 border border-stone-300 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors"
                            :class="errors.title ? 'border-red-500' : ''"
                        />
                        <!-- nampilin pesan error validasi judul kalo ada error -->
                        <p v-if="errors.title" class="text-xs text-red-600 mt-1">
                            {{ errors.title }}
                        </p>
                    </div>

                    <!-- blok input buat edit isi konten artikel -->
                    <div>
                        <!-- baris atas buat label konten sama penghitung jumlah karakter -->
                        <div class="flex items-center justify-between mb-1">
                            <!-- label isi konten artikel dengan tanda wajib diisi -->
                            <label class="block text-xs font-semibold text-stone-800">
                                Isi Konten Artikel <span class="text-red-500">*</span>
                            </label>
                            <!-- nampilin jumlah karakter isi konten sekarang -->
                            <span class="text-[11px] font-mono text-stone-400">
                                {{ form.content.length }} karakter
                            </span>
                        </div>
                        <!-- kotak textarea konten yang nyambung ke form.content -->
                        <textarea
                            v-model="form.content"
                            rows="7"
                            required
                            placeholder="Masukkan Isi Konten Artikel..."
                            class="w-full px-3.5 py-2.5 rounded-md bg-stone-50 border border-stone-300 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors resize-y leading-relaxed"
                            :class="errors.content ? 'border-red-500' : ''"
                        ></textarea>
                        <!-- nampilin pesan error validasi konten kalo ada error -->
                        <p v-if="errors.content" class="text-xs text-red-600 mt-1">
                            {{ errors.content }}
                        </p>
                    </div>

                    <!-- baris bawah form buat tombol batal, reset ke awal, sama perbarui -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                        <!-- link batal yang ngarahin balik ke halaman daftar artikel -->
                        <Link
                            :href="route('posts.index')"
                            class="px-3 py-1.5 rounded-md text-xs font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors"
                        >
                            Batal
                        </Link>

                        <!-- wadah buat tombol reset dan tombol simpan perubahan di sebelah kanan -->
                        <div class="flex items-center gap-2">
                            <!-- tombol reset buat balikin isi inputan ke data awal sebelum diedit -->
                            <button
                                type="button"
                                @click="resetToOriginal"
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
                                {{ form.processing ? 'Menyimpan...' : 'Perbarui Artikel' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>