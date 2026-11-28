<script setup>
// ambil computed dari vue buat bikin nilai turunan yang otomatis keupdate
import { computed } from 'vue';
// ambil layout tamu buat wadah halaman verifikasi email
import GuestLayout from '@/Layouts/GuestLayout.vue';
// ambil komponen tombol utama buat tombol kirim ulang email
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil Head buat judul tab, Link buat tombol logout, dan useForm buat kirim request form
import { Head, Link, useForm } from '@inertiajs/vue3';

// terima props dari controller dan simpen ke variabel props
const props = defineProps({
    // props status buat ngecek apakah email verifikasi baru aja dikirim ulang
    status: {
        // tipe datanya berupa teks string
        type: String,
    },
});

// bikin form kosong pake useForm karena kirim ulang verifikasi gak butuh inputan
const form = useForm({});

// fungsi buat minta kirim ulang email verifikasi pas tombol ditekan
const submit = () => {
    // kirim request POST ke route verification.send
    form.post(route('verification.send'));
};

// bikin status computed buat ngecek apakah link verifikasi baru udah kekirim
const verificationLinkSent = computed(
    // balikin true kalo nilai props.status sama dengan verification-link-sent
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <!-- bungkus halaman verifikasi email pake layout tamu -->
    <GuestLayout>
        <!-- set judul tab browser jadi Email Verification -->
        <Head title="Email Verification" />

        <!-- teks instruksi yang nyuruh user ngecek email buat klik link verifikasi -->
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <!-- notif hijau yang cuma muncul kalo link verifikasi baru udah berhasil dikirim -->
        <div
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <!-- form buat kirim ulang email verifikasi tanpa reload halaman -->
        <form @submit.prevent="submit">
            <!-- baris tombol kirim ulang di kiri dan link logout di kanan -->
            <div class="mt-4 flex items-center justify-between">
                <!-- tombol buat minta kirim ulang email verifikasi, nonaktif pas lagi proses kirim -->
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <!-- link buat keluar akun pake method POST yang tampilannya dibikin kayak tombol -->
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >Log Out</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
