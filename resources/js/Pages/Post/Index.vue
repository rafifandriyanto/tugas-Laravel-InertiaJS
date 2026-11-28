<script setup>
// ambil ref buat bikin state reaktif sama computed buat data turunan yang ngikutin state
import { ref, computed } from 'vue'
// ambil layout utama aplikasi artikel
import Layout from '../../Layouts/App.vue'
// ambil Link pindah halaman, router buat kirim request manual, sama usePage buat baca shared props
import { Link, router, usePage } from '@inertiajs/vue3'

// tangkep props daftar artikel yang dikirim dari PostController laravel
const props = defineProps({
    // prop posts nampung array semua data artikel
    posts: {
        // tipe datanya wajib array
        type: Array,
        // kasih array kosong kalo belum ada data artikel
        default: () => []
    }
})

// ambil objek halaman inertia aktif biar bisa akses shared props
const page = usePage()
// ambil data user yang lagi login secara reaktif dari shared props auth
const authUser = computed(() => page.props.auth?.user)

// state kata kunci pencarian yang diketik user di kotak cari
const searchQuery = ref('')

// daftar artikel yang udah disaring ngikutin kata kunci pencarian
const filteredPosts = computed(() => {
    // kalo kotak pencarian masih kosong balikin semua artikel tanpa disaring
    if (!searchQuery.value.trim()) {
        // langsung kirim semua data artikel dari props
        return props.posts
    }
    // ubah kata kunci pencarian ke huruf kecil biar pencocokan ga peduli kapital
    const q = searchQuery.value.toLowerCase()
    // saring artikel yang judul atau isi kontennya cocok sama kata kunci
    return props.posts.filter(post => 
        // cek kecocokan kata kunci di judul artikel
        (post.title && post.title.toLowerCase().includes(q)) ||
        // atau cek kecocokan kata kunci di isi konten artikel
        (post.content && post.content.toLowerCase().includes(q))
    )
})

// fungsi bantuan buat milih gambar sampul kartu artikel secara bergiliran
const getPostImage = (id, index) => {
    // daftar tiga file gambar lokal di folder public/images
    const images = [
        // gambar tema teknologi
        '/images/post_technology.jpg',
        // gambar tema arsitektur
        '/images/post_architecture.jpg',
        // gambar tema editorial
        '/images/hero_editorial.jpg'
    ]
    // pake sisa bagi id atau index biar tiap kartu dapet gambar yang muter
    return images[(id || index) % images.length]
}

// state buat nyimpen data artikel yang dipilih buat dihapus sekaligus buka modal konfirmasi
const postToDelete = ref(null)
// state penanda proses hapus lagi jalan biar tombol ga kepencet berkali-kali
const isDeleting = ref(false)

// fungsi buat nyimpen artikel yang mau dihapus biar modal konfirmasi muncul
const confirmDelete = (post) => {
    // masukin data artikel yang diklik ke state postToDelete
    postToDelete.value = post
}

// fungsi buat batalin hapus dan nutup modal konfirmasi
const cancelDelete = () => {
    // kosongin state postToDelete biar modalnya ilang dari layar
    postToDelete.value = null
}

// fungsi buat eksekusi hapus artikel ke backend lewat inertia
const executeDelete = () => {
    // stop proses kalo ga ada artikel yang dipilih
    if (!postToDelete.value) return
    // tandain proses hapus lagi jalan
    isDeleting.value = true
    // kirim request delete ke endpoint hapus artikel sesuai id-nya
    router.delete(`/posts/${postToDelete.value.id}`, {
        // pas request kelar baik sukses maupun gagal balikin lagi state-nya
        onFinish: () => {
            // buka lagi kunci tombol hapus
            isDeleting.value = false
            // tutup modal konfirmasi dengan ngosongin state postToDelete
            postToDelete.value = null
        }
    })
}

// fungsi bantuan buat ngubah format tanggal database jadi format indonesia yang gampang dibaca
const formatDate = (dateString) => {
    // kalo tanggalnya kosong langsung balikin teks Baru Saja
    if (!dateString) return 'Baru Saja'
    // coba parsing string tanggal ke format lokal indonesia
    try {
        // ubah string tanggal dari backend jadi objek Date javascript
        const date = new Date(dateString)
        // format tanggal ke gaya indonesia dengan nama bulan lengkap
        return new Intl.DateTimeFormat('id-ID', {
            // nampilin angka tanggal
            day: 'numeric',
            // nampilin nama bulan lengkap bahasa indonesia
            month: 'long',
            // nampilin tahun empat digit
            year: 'numeric'
        // jalankan format tanggal ke objek Date tadi
        }).format(date)
    // kalo gagal parsing tanggal langsung balikin teks aslinya aja
    } catch {
        // balikin string tanggal mentah biar ga error
        return dateString
    }
}
</script>

<template>
    <!-- bungkus seluruh halaman daftar artikel pake layout utama -->
    <Layout>
        <!-- pembungkus utama konten halaman dengan jarak vertikal antar bagian -->
        <div class="space-y-8">
            <!-- banner info buat tamu yang belum login biar tau cuma bisa baca doang -->
            <div 
                v-if="!authUser"
                class="p-4 rounded-lg bg-stone-100 border border-stone-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-stone-800 text-xs"
            >
                <!-- bagian kiri banner berisi ikon gembok sama teks penjelasan -->
                <div class="flex items-center gap-2.5">
                    <!-- ikon gembok penanda mode baca tamu -->
                    <span class="text-sm">🔒</span>
                    <!-- wadah judul dan keterangan mode pratinjau tamu -->
                    <div>
                        <!-- label tebal penanda status tamu -->
                        <span class="font-semibold block sm:inline">Mode Pratinjau Tamu:</span>
                        <!-- pesan ajakan login kalo mau nambah, edit, atau hapus artikel -->
                        <span class="text-stone-600 sm:ml-1">Anda dapat membaca semua artikel. Untuk membuat, mengedit, atau menghapus postingan, silakan login.</span>
                    </div>
                </div>
                <!-- tombol link yang ngarahin tamu ke halaman login -->
                <Link
                    :href="route('login')"
                    class="shrink-0 px-3 py-1.5 rounded-md text-xs font-medium text-white bg-stone-900 hover:bg-stone-800 transition-colors"
                >
                    Masuk ke Akun
                </Link>
            </div>

            <!-- bagian header halaman daftar artikel -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-2 border-b border-stone-200">
                <!-- sisi kiri header buat label kategori, judul halaman, sama total artikel -->
                <div>
                    <!-- teks kecil kategori halaman di atas judul -->
                    <span class="text-xs font-semibold uppercase tracking-wider text-stone-500 block mb-1">
                        Koleksi Publikasi
                    </span>
                    <!-- judul utama halaman daftar artikel -->
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-950">
                        Daftar Postingan Artikel
                    </h1>
                    <!-- info jumlah total artikel yang kesimpen di database mysql -->
                    <p class="text-xs sm:text-sm text-stone-600 mt-1">
                        Total {{ posts.length }} artikel tersimpan di database MySQL (Laragon).
                    </p>
                </div>

                <!-- sisi kanan header buat tombol tambah artikel baru -->
                <div class="flex items-center gap-3">
                    <!-- tombol tulis artikel baru yang cuma muncul kalo user udah login -->
                    <Link
                        v-if="authUser"
                        :href="route('posts.create')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md text-xs font-semibold text-white bg-stone-900 hover:bg-stone-800 transition-colors shadow-xs"
                    >
                        <!-- teks tombol buat bikin artikel baru -->
                        <span>+ Tulis Artikel Baru</span>
                    </Link>
                </div>
            </div>

            <!-- baris kontrol buat kotak pencarian artikel sama info jumlah hasil -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <!-- pembungkus kotak input cari artikel beserta tombol clear -->
                <div class="relative w-full sm:max-w-xs">
                    <!-- input teks pencarian yang nyambung ke state searchQuery -->
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari judul atau isi artikel..."
                        class="w-full px-3.5 py-2 rounded-md bg-white border border-stone-300 text-stone-900 text-xs placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors shadow-2xs"
                    />
                    <!-- tombol silang kecil buat ngosongin pencarian yang muncul pas ada kata kunci -->
                    <span v-if="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-2 text-xs text-stone-400 hover:text-stone-700 cursor-pointer">
                        ✕
                    </span>
                </div>

                <!-- teks penghitung jumlah artikel yang ketemu dibanding total semua artikel -->
                <div class="text-xs text-stone-500">
                    Menampilkan <strong>{{ filteredPosts.length }}</strong> dari {{ posts.length }} postingan
                </div>
            </div>

            <!-- grid kartu artikel yang muncul kalo hasil pencarian ada isinya -->
            <div v-if="filteredPosts.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- looping tiap artikel hasil filter buat dijadiin kartu -->
                <article
                    v-for="(post, index) in filteredPosts"
                    :key="post.id"
                    class="bg-white border border-stone-200 rounded-lg overflow-hidden flex flex-col justify-between hover:border-stone-400 transition-colors shadow-xs group"
                >
                    <!-- bagian atas kartu berisi gambar sampul sama isi ringkas artikel -->
                    <div>
                        <!-- wadah gambar sampul artikel dengan rasio 16:9 -->
                        <div class="aspect-video w-full overflow-hidden bg-stone-100">
                            <!-- gambar sampul kartu yang dipilih lewat fungsi getPostImage -->
                            <img 
                                :src="getPostImage(post.id, index)" 
                                :alt="post.title" 
                                class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300"
                            />
                        </div>

                        <!-- bagian isi kartu buat nampilin id, tanggal, judul, sama cuplikan konten -->
                        <div class="p-5">
                            <!-- baris info id artikel di kiri sama tanggal rilis di kanan -->
                            <div class="flex items-center justify-between text-[11px] font-mono text-stone-400 mb-2">
                                <!-- nomor id artikel -->
                                <span>#{{ post.id }}</span>
                                <!-- tanggal artikel dibuat yang udah diformat pake formatDate -->
                                <span>{{ formatDate(post.created_at) }}</span>
                            </div>

                            <!-- judul artikel yang dibatasi maksimal dua baris -->
                            <h2 class="font-bold text-stone-900 text-base leading-snug line-clamp-2">
                                {{ post.title }}
                            </h2>

                            <!-- cuplikan isi artikel yang dibatasi maksimal tiga baris -->
                            <p class="text-xs text-stone-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ post.content }}
                            </p>
                        </div>
                    </div>

                    <!-- bagian bawah kartu buat tombol aksi edit, hapus, atau info mode baca -->
                    <div class="px-5 pb-5 pt-3 border-t border-stone-100 flex items-center justify-between text-xs">
                        <!-- tombol edit sama hapus cuma muncul kalo user udah login -->
                        <template v-if="authUser">
                            <!-- wadah tombol edit dan hapus di sebelah kiri -->
                            <div class="flex items-center gap-3">
                                <!-- link buat buka halaman edit artikel sesuai id-nya -->
                                <Link
                                    :href="`/posts/${post.id}/edit`"
                                    class="text-xs font-medium text-stone-700 hover:text-stone-950 underline"
                                >
                                    Edit
                                </Link>
                                <!-- tombol hapus buat ngebuka modal konfirmasi hapus artikel ini -->
                                <button
                                    type="button"
                                    @click="confirmDelete(post)"
                                    class="text-xs font-medium text-red-600 hover:text-red-800 underline cursor-pointer"
                                >
                                    Hapus
                                </button>
                            </div>
                            <!-- label kecil penanda artikel udah kesimpen di database -->
                            <span class="text-[11px] text-stone-400 font-mono">Tersimpan</span>
                        </template>

                        <!-- tampilan bagian bawah kartu kalo yang buka tamu belum login -->
                        <template v-else>
                            <!-- label penanda cuma bisa baca -->
                            <span class="text-[11px] text-stone-400">Mode Baca</span>
                            <!-- link ajakan login buat ngelola artikel -->
                            <Link :href="route('login')" class="text-xs font-medium text-stone-800 hover:underline">
                                Login untuk kelola
                            </Link>
                        </template>
                    </div>
                </article>
            </div>

            <!-- tampilan kosong kalo ga ada artikel yang cocok sama kata kunci pencarian -->
            <div v-else class="text-center py-14 bg-white border border-dashed border-stone-300 rounded-lg">
                <!-- pesan info artikel yang dicari ga ketemu -->
                <p class="text-sm font-medium text-stone-700">Tidak ada artikel yang cocok dengan pencarian.</p>
                <!-- tombol buat reset kata kunci pencarian yang muncul kalo lagi ada pencarian aktif -->
                <button
                    v-if="searchQuery"
                    type="button"
                    @click="searchQuery = ''"
                    class="mt-2 text-xs text-stone-900 underline font-medium cursor-pointer"
                >
                    Reset Pencarian
                </button>
            </div>

            <!-- modal konfirmasi hapus artikel yang muncul kalo postToDelete ada isinya -->
            <div v-if="postToDelete" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/40 backdrop-blur-2xs">
                <!-- kotak putih isi dialog konfirmasi hapus -->
                <div class="bg-white rounded-lg border border-stone-200 max-w-sm w-full p-6 shadow-lg">
                    <!-- judul pertanyaan konfirmasi di dalam modal -->
                    <h3 class="text-base font-bold text-stone-900">
                        Hapus Artikel?
                    </h3>
                    <!-- teks peringatan yang nyebutin judul artikel yang bakal dihapus permanen -->
                    <p class="text-xs text-stone-600 mt-2 leading-relaxed">
                        Apakah Anda yakin ingin menghapus artikel <strong>"{{ postToDelete.title }}"</strong>? Tindakan ini akan menghapus data dari database MySQL secara permanen.
                    </p>

                    <!-- baris tombol batal dan tombol konfirmasi hapus di bagian bawah modal -->
                    <div class="mt-6 flex items-center justify-end gap-3 text-xs">
                        <!-- tombol batal buat nutup modal tanpa ngehapus artikel -->
                        <button
                            type="button"
                            @click="cancelDelete"
                            :disabled="isDeleting"
                            class="px-3 py-2 rounded-md border border-stone-300 text-stone-700 hover:bg-stone-50 cursor-pointer font-medium"
                        >
                            Batal
                        </button>
                        <!-- tombol merah buat beneran hapus artikel dari database -->
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