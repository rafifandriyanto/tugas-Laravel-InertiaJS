<script setup>
// ambil ref dari vue buat nyimpen state buka-tutup menu navigasi mobile
import { ref } from 'vue';
// ambil komponen logo aplikasi
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
// ambil komponen dropdown buat menu profil user di pojok kanan atas
import Dropdown from '@/Components/Dropdown.vue';
// ambil komponen item link di dalem dropdown
import DropdownLink from '@/Components/DropdownLink.vue';
// ambil komponen link navigasi navbar versi desktop
import NavLink from '@/Components/NavLink.vue';
// ambil komponen link navigasi versi layar hp
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
// ambil komponen Link dari inertia buat pindah halaman tanpa reload
import { Link } from '@inertiajs/vue3';

// state buat ngatur menu dropdown navigasi di tampilan hp lagi kebuka atau ketutup
const showingNavigationDropdown = ref(false);
</script>

<template>
    <!-- wadah root template layout halaman yang butuh login -->
    <div>
        <!-- pembungkus utama tinggi minimal selayar penuh dengan latar abu-abu muda -->
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- bar navigasi atas halaman authenticated -->
            <nav
                class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800"
            >
                <!-- wadah pembatas lebar menu navigasi utama -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- baris flex isi navbar kiri sama kanan dengan tinggi tetap -->
                    <div class="flex h-16 justify-between">
                        <!-- kelompok kiri isi logo sama link menu utama -->
                        <div class="flex">
                            <!-- wadah logo di pojok kiri navbar -->
                            <div class="flex shrink-0 items-center">
                                <!-- klik logo bakal ngarahin balik ke halaman dashboard -->
                                <Link :href="route('dashboard')">
                                    <!-- komponen gambar logo aplikasi -->
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                    />
                                </Link>
                            </div>

                            <!-- kumpulan link navigasi desktop yang disembunyiin di layar hp -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <!-- link menu menuju halaman dashboard dan nyala kalo route-nya lagi di dashboard -->
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <!-- link menu menuju halaman daftar artikel dan nyala kalo lagi buka route posts -->
                                <NavLink
                                    :href="route('posts.index')"
                                    :active="route().current('posts.*')"
                                >
                                    Kelola Artikel
                                </NavLink>
                            </div>
                        </div>

                        <!-- kelompok kanan buat nampilin dropdown nama akun di layar besar -->
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- pembungkus menu dropdown pengaturan akun -->
                            <div class="relative ms-3">
                                <!-- komponen dropdown rata kanan dengan lebar w-48 -->
                                <Dropdown align="right" width="48">
                                    <!-- slot trigger buat tombol nama user yang memicu dropdown kebuka -->
                                    <template #trigger>
                                        <!-- wadah inline-flex buat tombol pemicu dropdown -->
                                        <span class="inline-flex rounded-md">
                                            <!-- tombol yang nampilin nama user sama ikon panah ke bawah -->
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                            >
                                                <!-- nampilin nama user yang lagi login dari shared props -->
                                                {{ $page.props.auth.user.name }}

                                                <!-- ikon panah kecil ke bawah di sebelah nama user -->
                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <!-- bentuk vektor panah chevron bawah -->
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <!-- slot content isi pilihan menu pas dropdown lagi kebuka -->
                                    <template #content>
                                        <!-- link ke halaman edit profil user -->
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <!-- tombol logout yang ngirim request POST ke route logout -->
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- tombol hamburger buat buka-tutup menu khusus di tampilan layar hp -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <!-- tombol yang ngebalik state showingNavigationDropdown pas diklik -->
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                            >
                                <!-- ikon svg yang berubah antara garis tiga dan tanda silang -->
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <!-- garis tiga hamburger yang muncul pas menu mobile ketutup -->
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <!-- ikon tanda silang x yang muncul pas menu mobile lagi kebuka -->
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- laci menu navigasi responsif yang cuma ditampilin di layar kecil pas showingNavigationDropdown true -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <!-- daftar link menu utama versi mobile -->
                    <div class="space-y-1 pb-3 pt-2">
                        <!-- link mobile menuju halaman dashboard -->
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <!-- link mobile menuju halaman kelola artikel -->
                        <ResponsiveNavLink
                            :href="route('posts.index')"
                            :active="route().current('posts.*')"
                        >
                            Kelola Artikel
                        </ResponsiveNavLink>
                    </div>

                    <!-- bagian info akun dan opsi pengaturan di menu mobile -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                    >
                        <!-- wadah info nama sama email user yang lagi login -->
                        <div class="px-4">
                            <!-- nampilin nama lengkap user yang lagi login -->
                            <div
                                class="text-base font-medium text-gray-800 dark:text-gray-200"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <!-- nampilin alamat email user yang lagi login -->
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <!-- daftar link profil dan logout di menu mobile -->
                        <div class="mt-3 space-y-1">
                            <!-- link mobile ke halaman edit profil -->
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <!-- tombol mobile buat logout pake method post -->
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- bagian judul halaman yang cuma ditampilin kalo halaman ngisi slot header -->
            <header
                class="bg-white shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <!-- wadah pembatas lebar teks judul header -->
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <!-- slot header buat naro judul halaman dari komponen page -->
                    <slot name="header" />
                </div>
            </header>

            <!-- area utama buat nampilin isi halaman -->
            <main>
                <!-- slot default buat isi konten halaman dashboard atau profil -->
                <slot />
            </main>
        </div>
    </div>
</template>
