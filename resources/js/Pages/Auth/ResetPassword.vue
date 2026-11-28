<script setup>
// ambil layout tamu buat tampilan card halaman reset password
import GuestLayout from '@/Layouts/GuestLayout.vue';
// ambil komponen penampil error di bawah inputan
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul tiap input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen tombol utama buat simpan password baru
import PrimaryButton from '@/Components/PrimaryButton.vue';
// ambil komponen kotak input teks
import TextInput from '@/Components/TextInput.vue';
// ambil Head buat judul tab browser dan useForm buat atur state form
import { Head, useForm } from '@inertiajs/vue3';

// terima props email sama token dari controller dan simpen ke variabel props
const props = defineProps({
    // props email akun yang lagi mau direset passwordnya
    email: {
        // tipe datanya string teks
        type: String,
        // wajib ada alias gak boleh kosong
        required: true,
    },
    // props token unik dari link email reset password
    token: {
        // tipe datanya string teks
        type: String,
        // wajib dikirim biar server bisa verifikasi link resetnya valid
        required: true,
    },
});

// bikin objek form pake useForm buat ngirim password baru
const form = useForm({
    // isi otomatis token dari props biar ikut terkirim ke backend
    token: props.token,
    // isi otomatis email dari props biar user gak perlu ngetik ulang
    email: props.email,
    // tampung inputan password baru, nilai awal kosong
    password: '',
    // tampung inputan konfirmasi password baru, nilai awal kosong
    password_confirmation: '',
});

// fungsi buat ngirim data reset password pas form disubmit
const submit = () => {
    // kirim form lewat POST ke route password.store
    form.post(route('password.store'), {
        // kalo udah beres, bersihin lagi isi password sama konfirmasinya
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <!-- bungkus halaman reset password pake layout tamu -->
    <GuestLayout>
        <!-- atur judul tab browser jadi Reset Password -->
        <Head title="Reset Password" />

        <!-- form ganti password baru, cegah reload halaman pas disubmit -->
        <form @submit.prevent="submit">
            <!-- blok inputan email -->
            <div>
                <!-- label teks Email buat inputan email -->
                <InputLabel for="email" value="Email" />

                <!-- kotak input email yang udah keisi otomatis dan langsung fokus -->
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <!-- tampilin pesan error kalo email gak valid -->
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- blok inputan password baru -->
            <div class="mt-4">
                <!-- label teks Password buat inputan password baru -->
                <InputLabel for="password" value="Password" />

                <!-- kotak input password baru yang nyambung ke form.password -->
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <!-- tampilin error validasi kalo password baru gak memenuhi syarat -->
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- blok inputan konfirmasi password baru -->
            <div class="mt-4">
                <!-- label teks Confirm Password buat inputan ulang password baru -->
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <!-- kotak input ulang password baru yang nyambung ke form.password_confirmation -->
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <!-- tampilin error kalo konfirmasi password gak sama -->
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <!-- wadah tombol submit yang diratain ke kanan -->
            <div class="mt-4 flex items-center justify-end">
                <!-- tombol Reset Password yang meredup dan gak bisa diklik pas lagi proses -->
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
