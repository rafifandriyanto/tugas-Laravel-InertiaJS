<script setup>
// ambil komponen tombol merah buat aksi bahaya kayak hapus akun
import DangerButton from '@/Components/DangerButton.vue';
// ambil komponen buat nampilin pesan error validasi
import InputError from '@/Components/InputError.vue';
// ambil komponen label buat judul kolom input
import InputLabel from '@/Components/InputLabel.vue';
// ambil komponen popup modal bawaan breeze
import Modal from '@/Components/Modal.vue';
// ambil komponen tombol abu-abu buat tombol batal
import SecondaryButton from '@/Components/SecondaryButton.vue';
// ambil komponen kotak input teks bawaan breeze
import TextInput from '@/Components/TextInput.vue';
// ambil useForm buat ngelola form dan kirim request hapus akun ke backend
import { useForm } from '@inertiajs/vue3';
// ambil nextTick buat nunggu dom kelar update sama ref buat bikin state reaktif
import { nextTick, ref } from 'vue';

// state penanda modal konfirmasi hapus akun lagi kebuka atau engga
const confirmingUserDeletion = ref(false);
// ref buat nangkep elemen input password biar bisa difokusin dari js
const passwordInput = ref(null);

// bikin state form buat nampung password konfirmasi hapus akun
const form = useForm({
    // field password awalnya dikosongin
    password: '',
});

// fungsi buat ngebuka modal konfirmasi pas tombol Delete Account diklik
const confirmUserDeletion = () => {
    // ubah state jadi true biar modalnya muncul
    confirmingUserDeletion.value = true;

    // tunggu modal selesai muncul di layar baru arahin kursor ke kolom password
    nextTick(() => passwordInput.value.focus());
};

// fungsi buat ngirim permintaan hapus akun permanen ke server
const deleteUser = () => {
    // kirim request delete ke route profile.destroy lewat inertia
    form.delete(route('profile.destroy'), {
        // jaga posisi scroll halaman biar ga balik ke paling atas
        preserveScroll: true,
        // kalo berhasil hapus akun langsung tutup modalnya
        onSuccess: () => closeModal(),
        // kalo gagal misalnya password salah fokusin lagi kursor ke input password
        onError: () => passwordInput.value.focus(),
        // abis proses selesai bersihin lagi isi formnya
        onFinish: () => form.reset(),
    });
};

// fungsi buat nutup modal konfirmasi dan bersihin sisa inputan
const closeModal = () => {
    // set jadi false biar modalnya ketutup
    confirmingUserDeletion.value = false;

    // hapus pesan error lama biar ga kebawa pas modal dibuka lagi nanti
    form.clearErrors();
    // kosongin lagi isi password di state form
    form.reset();
};
</script>

<template>
    <!-- bagian halaman buat fitur hapus akun permanen -->
    <section class="space-y-6">
        <!-- header bagian hapus akun buat judul dan peringatan -->
        <header>
            <!-- judul bagian Delete Account -->
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Delete Account
            </h2>

            <!-- teks peringatan kalo hapus akun bakal ngilangin semua data secara permanen -->
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <!-- tombol merah buat ngebuka popup konfirmasi hapus akun -->
        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <!-- komponen popup modal yang muncul kalo confirmingUserDeletion bernilai true -->
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <!-- pembungkus isi konten di dalam modal -->
            <div class="p-6">
                <!-- pertanyaan konfirmasi biar user yakin mau hapus akun -->
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    Are you sure you want to delete your account?
                </h2>

                <!-- penjelasan di modal yang minta user masukin password buat konfirmasi -->
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <!-- pembungkus kolom input password di dalam modal -->
                <div class="mt-6">
                    <!-- label tersembunyi buat aksesibilitas screen reader -->
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <!-- kotak input password konfirmasi yang bisa langsung kirim pas teken enter -->
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <!-- nampilin error kalo password konfirmasi yang dimasukin salah -->
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <!-- bagian bawah modal buat tombol batal sama tombol konfirmasi hapus -->
                <div class="mt-6 flex justify-end">
                    <!-- tombol batal buat nutup modal tanpa ngehapus akun -->
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <!-- tombol merah final buat eksekusi hapus akun -->
                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
