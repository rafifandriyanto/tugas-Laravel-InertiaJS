<script setup>
// import layout component
import Layout from '../../Layouts/App.vue';

// receive validation errors from controller (if any)
defineProps({
  errors: Object
});

import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

// reactive state for the post form
const post = reactive({
  title: '',
  content: ''
});

// submit handler
function storePost() {
  router.post('/posts', post);
}
</script>

<template>
  <Layout>
    <div class="card border-0 rounded shadow">
      <div class="card-body">
        <h4 class="mb-3">TAMBAH POST</h4>
        <hr />
        <form @submit.prevent="storePost">
          <div class="mb-3">
            <label class="form-label">TITLE POST</label>
            <input
              type="text"
              class="form-control"
              v-model="post.title"
              placeholder="Masukkan Title Post"
            />
            <div v-if="errors.title" class="mt-2 alert alert-danger">
              {{ errors.title }}
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">CONTENT</label>
            <textarea
              class="form-control"
              rows="5"
              v-model="post.content"
              placeholder="Masukkan Content Post"
            ></textarea>
            <div v-if="errors.content" class="mt-2 alert alert-danger">
              {{ errors.content }}
            </div>
          </div>

          <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-md shadow-sm me-2">
              SIMPAN
            </button>
            <button type="reset" class="btn btn-warning btn-md shadow-sm">
              RESET
            </button>
          </div>
        </form>
      </div>
    </div>
  </Layout>
</template>
