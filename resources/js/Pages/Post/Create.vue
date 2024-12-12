<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {ref} from "vue";
import axios from "axios";

const postData = ref({
    title: null,
    image: null,
    content: null,
});

const errors = ref({
    postData: {
        title: null,
        image: null,
        content: null,
    },
});

const checkErrors = (obj, field) => {
    if (obj) {
        errors.value[obj][field] = '';
    } else {
        errors.value[field] = '';
    }
};

const createPost = async () => {
    const formData = new FormData();
    formData.append('title', postData.value.title);
    formData.append('image', postData.value.image);
    formData.append('content', postData.value.content);

    try {
        const response = await axios.post(route('post.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        if (response.data.success) {
            router.visit('/');
        }

        postData.value = {title: null, image: null, content: null};
    } catch (error) {
        errors.value.postData = Object.assign(errors.value.postData, error.response.data.errors);
    }
};
</script>

<template>
    <Head title="Create Post"/>
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 mt-10">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Create a New Post</h2>
        <form @submit.prevent="createPost">
            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Title
                </label>
                <input
                    v-model="postData.title"
                    type="text"
                    id="title"
                    placeholder="Enter title"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                    @input="checkErrors('postData', 'title')"
                />
                <p v-if="errors.postData.title" class="text-red-500 text-sm mt-2">
                    {{ errors.postData.title[0] }}
                </p>
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Image
                </label>
                <input
                    @change="(event) => postData.image = event.target.files[0]"
                    type="file"
                    id="image"
                    class="mt-2 block w-full px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
                <p class="mt-2 text-xs text-gray-500">Supported formats: JPG, PNG</p>
                <p v-if="errors.postData.image" class="text-red-500 text-sm mt-2">
                    {{ errors.postData.image[0] }}
                </p>
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Content
                </label>
                <textarea
                    v-model="postData.content"
                    id="content"
                    rows="5"
                    placeholder="Write your content here"
                    class="mt-2 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                    @input="checkErrors('postData', 'content')"
                ></textarea>
                <p v-if="errors.postData.content" class="text-red-500 text-sm mt-2">
                    {{ errors.postData.content[0] }}
                </p>
            </div>

            <div class="flex justify-end mt-8 space-x-4">
                <Link :href="route('post.index')">
                    <button
                        type="button"
                        class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg shadow hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-2 focus:ring-gray-400"
                    >
                        Back to List
                    </button>
                </Link>
                <button
                    type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500"
                >
                    Create Post
                </button>
            </div>
        </form>
    </div>
</template>
