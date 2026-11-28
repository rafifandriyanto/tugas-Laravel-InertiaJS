<script setup>
// ambil helper vue buat bikin computed, lifecycle hook, ref elemen, sama watcher
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

// terima pengaturan modal dari komponen parent
const props = defineProps({
    // status modal lagi ditampilin atau disembunyiin
    show: {
        // tipe datanya boolean
        type: Boolean,
        // default-nya ketutup alias false
        default: false,
    },
    // batas lebar maksimal kotak modal
    maxWidth: {
        // tipe datanya string
        type: String,
        // ukuran default-nya 2xl
        default: '2xl',
    },
    // nentuin apakah modal boleh ditutup pas klik luar atau pencet tombol escape
    closeable: {
        // tipe datanya boolean
        type: Boolean,
        // default-nya boleh ditutup
        default: true,
    },
});

// daftarin event close buat ngabarin komponen parent pas modal minta ditutup
const emit = defineEmits(['close']);
// ref buat megang elemen html <dialog> asli di dom
const dialog = ref();
// state buat nahan isi slot biar tetep muncul selama animasi nutup jalan
const showSlot = ref(props.show);

// pantau terus perubahan prop show biar bisa buka atau tutup <dialog>
watch(
    // sumber data yang dipantau adalah props.show
    () => props.show,
    // fungsi yang jalan tiap kali nilai props.show berubah
    () => {
        // kalo modal disuruh kebuka
        if (props.show) {
            // kunci scroll halaman belakang biar gak ikut gerak pas modal kebuka
            document.body.style.overflow = 'hidden';
            // aktifin isi slot supaya konten modal di-render
            showSlot.value = true;

            // panggil method bawaan browser buat nampilin elemen <dialog> sebagai modal
            dialog.value?.showModal();
        // kalo modal disuruh nutup
        } else {
            // balikin scroll halaman belakang biar normal lagi
            document.body.style.overflow = '';

            // tunggu 200ms sampe animasi nutup selesai baru beneran tutup dialog dan sembunyiin slot
            setTimeout(() => {
                // tutup elemen <dialog> lewat method bawaan dom
                dialog.value?.close();
                // matiin render isi slot setelah animasi beres
                showSlot.value = false;
            }, 200);
        }
    },
);

// fungsi buat nutup modal dengan ngirim event close ke parent
const close = () => {
    // cuma kirim event close kalo prop closeable nilainya true
    if (props.closeable) {
        // panggil emit close ke komponen parent
        emit('close');
    }
};

// fungsi buat nangani tombol escape di keyboard buat nutup modal
const closeOnEscape = (e) => {
    // cek apakah tombol yang dipencet adalah Escape
    if (e.key === 'Escape') {
        // cegah perilaku bawaan browser buat <dialog> biar animasi vue tetep jalan rapi
        e.preventDefault();

        // kalo modal lagi kebuka, jalanin fungsi close
        if (props.show) {
            // tutup modal lewat fungsi close
            close();
        }
    }
};

// pas komponen dipasang ke halaman, daftarin listener keyboard
onMounted(() => document.addEventListener('keydown', closeOnEscape));

// pas komponen dicopot dari halaman, bersihin listener dan balikin scroll body
onUnmounted(() => {
    // hapus event listener keydown biar gak numpuk
    document.removeEventListener('keydown', closeOnEscape);

    // pastiin overflow body dibalikin normal lagi
    document.body.style.overflow = '';
});

// ubah nilai prop maxWidth jadi class lebar maksimal tailwind
const maxWidthClass = computed(() => {
    // pilih class tailwind yang cocok sama nilai prop maxWidth
    return {
        // ukuran kecil
        sm: 'sm:max-w-sm',
        // ukuran sedang
        md: 'sm:max-w-md',
        // ukuran besar
        lg: 'sm:max-w-lg',
        // ukuran ekstra besar
        xl: 'sm:max-w-xl',
        // ukuran dua kali ekstra besar
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <!-- elemen dialog html5 bawaan yang disambungin ke ref dialog -->
    <dialog
        class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        ref="dialog"
    >
        <!-- pembungkus fixed selayar penuh buat nampung overlay sama kotak modal -->
        <div
            class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
            scroll-region
        >
            <!-- animasi fade buat lapisan gelap di belakang modal -->
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <!-- area backdrop yang kalo diklik bakal nutup modal -->
                <div
                    v-show="show"
                    class="fixed inset-0 transform transition-all"
                    @click="close"
                >
                    <!-- warna abu gelap semi transparan biar konten belakang keliatan redup -->
                    <div
                        class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"
                    />
                </div>
            </Transition>

            <!-- animasi muncul dan ilang buat kotak utama modal -->
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <!-- kotak putih isi modal yang lebarnya ngikutin maxWidthClass -->
                <div
                    v-show="show"
                    class="mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full dark:bg-gray-800"
                    :class="maxWidthClass"
                >
                    <!-- tampilin konten modal dari parent cuma pas showSlot bernilai true -->
                    <slot v-if="showSlot" />
                </div>
            </Transition>
        </div>
    </dialog>
</template>
