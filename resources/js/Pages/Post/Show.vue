<script setup>
import {Head, Link} from '@inertiajs/vue3';
import { ref } from "vue";

const { post } = defineProps(['post']);

const editPostData = ref({
    title: post.title,
    image: null,
    content: post.content,
});

const imagePreview = ref('/storage/' + post.image || '');
</script>

<template>
    <Head title="Show Post"/>
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 mt-10">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">View Post</h2>

        <div class="mb-6">
            <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Title</label>
            <input
                v-model="editPostData.title"
                type="text"
                id="title"
                class="mt-2 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                placeholder="Edit title"
                disabled
            />
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Image</label>
            <div v-if="imagePreview" class="mt-2">
                <img
                    :src="imagePreview"
                    alt="Post Image"
                    class="w-48 h-48 object-cover border rounded-lg shadow-md"
                />
            </div>
        </div>

        <div class="mb-6">
            <label for="content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Content</label>
            <textarea
                v-model="editPostData.content"
                id="content"
                rows="5"
                class="mt-2 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                placeholder="Edit content here"
                disabled
            ></textarea>
        </div>

        <div class="mt-8 flex justify-center">
            <Link :href="route('post.index')">
                <button
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    Back to List
                </button>
            </Link>
        </div>
    </div>
</template>
