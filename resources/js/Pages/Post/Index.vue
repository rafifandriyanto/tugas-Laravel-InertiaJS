<script setup>
// import layout app
import Layout from '../../Layouts/App.vue'

// import Link
import { Link } from '@inertiajs/vue3';

// get props "posts" dari controller
defineProps({
    posts: Array
});
</script>

<template>
    <Layout>
        <div class="mt-5">
            <!-- flash message -->
            <div v-if="$page.props.flash.message" class="alert alert-success" role="alert">
                {{ $page.props.flash.message }}
            </div>

            <div class="mb-3">
                <Link href="/posts/create" class="btn btn-md btn-primary">TAMBAH DATA</Link>
            </div>

            <div class="card border-0 rounded shadow-sm">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">TITLE</th>
                                <th scope="col">CONTENT</th>
                                <th scope="col">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="post in posts" :key="post.id">
                                <td>{{ post.title }}</td>
                                <td>{{ post.content }}</td>
                                <td class="text-center"></td>
                                <td class="d-flex gap-2">
                                    <Link :href="`/posts/${post.id}/edit`" class="btn btn-primary btn-md">EDIT</Link>
                                    <Link :href="`/posts/${post.id}`" method="delete" class="btn btn-danger btn-md">DELETE</Link>
                                </td>
                            </tr>       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Layout>
</template>
                                