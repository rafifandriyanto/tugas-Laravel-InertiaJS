<script setup>
// ambil komponen buat nampilin pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul kolom input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat simpan perubahan profil
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen kotak input teks bawaan breeze
import TextInput from '@/Components/TextInput.vue';
// ambil Link buat kirim ulang verifikasi email, useForm buat atur form, sama usePage buat baca data user login
import { Link, useForm, usePage } from '@inertiajs/vue3';

// tangkep props yang dioper dari halaman Profile/Edit.vue
defineProps({
    // prop buat ngecek apakah aplikasi mewajibkan verifikasi email
    mustVerifyEmail: {
        // tipe datanya boolean true atau false
        type: Boolean,
    },
    // prop status buat nandain link verifikasi baru udah kekirim
    status: {
        // tipe datanya string
        type: String,
    },
});

// ngambil data user yang lagi login dari shared props auth inertia
const user = usePage().props.auth.user;

// bikin state form update profil dan langsung isi nilai awal dari data user sekarang
const form = useForm({
    // isi awal nama user dari database
    name: user.name,
    // isi awal email user dari database
    email: user.email,
});
</script>

<template>
    <!-- bagian halaman buat form edit nama dan email profil user -->
    <section>
        <!-- header bagian informasi profil -->
        <header>
            <!-- judul bagian Profile Information -->
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Profile Information
            </h2>

            <!-- penjelasan singkat kalo bagian ini buat ngubah nama dan email akun -->
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <!-- form update profil yang langsung ngirim request patch ke route profile.update pas disubmit -->
        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <!-- blok input nama user -->
            <div>
                <!-- label buat kolom nama -->
                <InputLabel for="name" value="Name" />

                <!-- kotak input nama yang nyambung ke form.name pake v-model -->
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <!-- nampilin error validasi kalo isian nama ga sesuai -->
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- blok input email user -->
            <div>
                <!-- label buat kolom email -->
                <InputLabel for="email" value="Email" />

                <!-- kotak input email yang nyambung ke form.email pake v-model -->
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <!-- nampilin error validasi kalo format email salah atau udah dipake akun lain -->
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- blok peringatan yang cuma muncul kalo fitur verifikasi email aktif tapi email user belum diverifikasi -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <!-- info ke user kalo emailnya belum diverifikasi beserta tombol kirim ulang -->
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <!-- link yang dibikin jadi tombol post buat ngirim ulang email verifikasi -->
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <!-- pesan ijo yang tampil kalo link verifikasi baru aja dikirim ke email -->
                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <!-- baris bawah form buat tombol Save sama tulisan Saved -->
            <div class="flex items-center gap-4">
                <!-- tombol simpan perubahan profil yang dikunci pas form lagi diproses -->
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <!-- animasi fade buat nampilin tulisan Saved pas profil baru berhasil diupdate -->
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
