<script setup>
import Layout from '../../Layouts/App.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    title: props.post.title || '',
    content: props.post.content || ''
})

const submit = () => {
    form.put(`/posts/${props.post.id}`)
}

const resetToOriginal = () => {
    form.title = props.post.title || ''
    form.content = props.post.content || ''
}
</script>

<template>
    <Layout>
        <div class="max-w-2xl mx-auto space-y-4">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-stone-500 font-medium">
                <Link :href="route('posts.index')" class="hover:text-stone-900 transition-colors">
                    ← Kembali ke Semua Artikel
                </Link>
                <span>/</span>
                <span class="text-stone-800">Edit Artikel #{{ post.id }}</span>
            </nav>

            <!-- Card Form Container -->
            <div class="bg-white rounded-lg border border-stone-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-stone-200 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-stone-900">
                            Edit Artikel
                        </h1>
                        <p class="text-xs text-stone-500 mt-1">
                            Perubahan akan langsung disimpan ke database MySQL.
                        </p>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-mono font-semibold rounded bg-stone-100 text-stone-700">
                        ID: {{ post.id }}
                    </span>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <!-- Title Input Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-stone-800">
                                Judul Artikel <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-mono text-stone-400">
                                {{ form.title.length }} karakter
                            </span>
                        </div>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="Masukkan Judul Artikel..."
                            class="w-full px-3.5 py-2.5 rounded-md bg-stone-50 border border-stone-300 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors"
                            :class="errors.title ? 'border-red-500' : ''"
                        />
                        <p v-if="errors.title" class="text-xs text-red-600 mt-1">
                            {{ errors.title }}
                        </p>
                    </div>

                    <!-- Content Textarea Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-stone-800">
                                Isi Konten Artikel <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] font-mono text-stone-400">
                                {{ form.content.length }} karakter
                            </span>
                        </div>
                        <textarea
                            v-model="form.content"
                            rows="7"
                            required
                            placeholder="Masukkan Isi Konten Artikel..."
                            class="w-full px-3.5 py-2.5 rounded-md bg-stone-50 border border-stone-300 text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 transition-colors resize-y leading-relaxed"
                            :class="errors.content ? 'border-red-500' : ''"
                        ></textarea>
                        <p v-if="errors.content" class="text-xs text-red-600 mt-1">
                            {{ errors.content }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                        <Link
                            :href="route('posts.index')"
                            class="px-3 py-1.5 rounded-md text-xs font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors"
                        >
                            Batal
                        </Link>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="resetToOriginal"
                                class="px-3 py-1.5 rounded-md text-xs font-medium text-stone-500 hover:text-stone-800 cursor-pointer"
                            >
                                Reset
                            </button>

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