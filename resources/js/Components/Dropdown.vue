<script setup>
// ambil helper vue buat bikin computed, lifecycle hook, sama state reaktif ref
import { computed, onMounted, onUnmounted, ref } from 'vue';

// terima pengaturan dropdown dari komponen parent
const props = defineProps({
    // posisi munculnya menu dropdown, mau rata kiri, kanan, atau tengah
    align: {
        // tipe datanya string
        type: String,
        // default-nya rata kanan
        default: 'right',
    },
    // ukuran lebar kotak menu dropdown
    width: {
        // tipe datanya string
        type: String,
        // lebar bawaannya 48 (w-48)
        default: '48',
    },
    // class tambahan buat ngatur tampilan kotak isi dropdown
    contentClasses: {
        // tipe datanya string
        type: String,
        // default-nya ada padding vertikal dan background putih atau abu gelap pas dark mode
        default: 'py-1 bg-white dark:bg-gray-700',
    },
});

// fungsi buat nutup menu dropdown pas user mencet tombol escape di keyboard
const closeOnEscape = (e) => {
    // cek dulu dropdown lagi kebuka dan tombol yang dipencet beneran Escape
    if (open.value && e.key === 'Escape') {
        // ubah status open jadi false biar menu ketutup
        open.value = false;
    }
};

// pas komponen udah muncul di halaman, pasang listener tombol keyboard
onMounted(() => document.addEventListener('keydown', closeOnEscape));
// pas komponen dihapus dari halaman, lepas listener keyboard biar gak bocor memori
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

// ubah nilai prop width jadi class lebar tailwind yang sesuai
const widthClass = computed(() => {
    // cari mapping class lebar berdasarkan nilai prop width yang dijadiin string
    return {
        // kalo width-nya 48, pake class tailwind w-48
        48: 'w-48',
    }[props.width.toString()];
});

// tentuin class posisi dan arah animasi dropdown sesuai prop align
const alignmentClasses = computed(() => {
    // kalo rata kiri, tempel di sisi kiri dan arah animasi dari kiri atas
    if (props.align === 'left') {
        // balikin class tailwind buat rata kiri
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    // kalo rata kanan, tempel di sisi kanan dan arah animasi dari kanan atas
    } else if (props.align === 'right') {
        // balikin class tailwind buat rata kanan
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    // selain kiri atau kanan, arah animasinya dari tengah atas
    } else {
        // balikin class origin-top buat posisi default
        return 'origin-top';
    }
});

// state buat nyimpen kondisi dropdown lagi kebuka atau ketutup
const open = ref(false);
</script>

<template>
    <!-- wadah utama dropdown pake position relative biar posisi menunya ngikut ke tombol ini -->
    <div class="relative">
        <!-- pembungkus tombol pemicu yang ngebalik nilai open tiap diklik -->
        <div @click="open = !open">
            <!-- slot trigger buat naro tombol atau elemen pemicu dropdown -->
            <slot name="trigger" />
        </div>

        <!-- lapisan transparan selayar penuh buat nutup dropdown pas user klik di luar menu -->
        <div
            v-show="open"
            class="fixed inset-0 z-40"
            @click="open = false"
        ></div>

        <!-- efek transisi halus pas kotak menu dropdown muncul atau ilang -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <!-- kotak melayang isi dropdown yang muncul pas open bernilai true dan nutup lagi pas diklik -->
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses]"
                style="display: none"
                @click="open = false"
            >
                <!-- lapisan dalam kotak menu yang dikasih border tipis sama class dari prop contentClasses -->
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5"
                    :class="contentClasses"
                >
                    <!-- slot content buat naro daftar link atau item menu di dalem dropdown -->
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
