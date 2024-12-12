<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import { ref } from "vue";
import axios from "axios";

const { post } = defineProps(['post']);

const editPostData = ref({
    title: post.title,
    image: null,
    content: post.content,
});

const errors = ref({
    editPostData: {
        title: null,
        image: null,
        content: null,
    },
});

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        editPostData.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const imagePreview = ref('/storage/' + post.image || '');

const checkErrors = (obj, field) => {
    if (obj) {
        errors.value[obj][field] = '';
    } else {
        errors.value[field] = '';
    }
};

const updatePost = async () => {
    const formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('title', editPostData.value.title);
    formData.append('image', editPostData.value.image);
    formData.append('content', editPostData.value.content);

    try {
        const response = await axios.post(route('post.update', { post: post.id }), formData, {
            _method: 'PATCH',
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        if (response.data.success) {
            router.visit('/');
        }

        editPostData.value = {title: null, image: null, content: null};
    } catch (error) {
        errors.value.editPostData = Object.assign(errors.value.editPostData, error.response.data.errors)
    }
};
</script>

<template>
    <Head title="Edit Post"/>
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg mt-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Edit Your Post</h2>

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input
                v-model="editPostData.title"
                type="text"
                id="title"
                class="mt-2 block w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                placeholder="Edit title"
                @input="checkErrors('editPostData', 'title')"
            />
            <div class="text-red-500 text-sm mt-1" v-if="errors.editPostData.title">
                {{ errors.editPostData.title[0] }}
            </div>
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
            <div v-if="imagePreview" class="mb-4">
                <img
                    width="100"
                    :src="imagePreview"
                    alt="Preview"
                    class="w-32 h-32 object-cover border rounded-md shadow-sm"
                />
            </div>
            <input
                @change="onFileChange"
                type="file"
                id="image"
                class="mt-2 block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
            <p class="mt-2 text-xs text-gray-500">Upload a new image to replace the current one</p>
            <div class="text-red-500 text-sm mt-1" v-if="errors.editPostData.image">
                {{ errors.editPostData.image[0] }}
            </div>
        </div>

        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
            <textarea
                v-model="editPostData.content"
                id="content"
                rows="4"
                class="mt-2 block w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                placeholder="Edit content here"
                @input="checkErrors('editPostData', 'content')"
            ></textarea>
            <div class="text-red-500 text-sm mt-1" v-if="errors.editPostData.content">
                {{ errors.editPostData.content[0] }}
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <Link :href="route('post.index')">
                <button
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400"
                >
                    Back to List
                </button>
            </Link>
            <button
                @click="updatePost"
                class="px-4 py-2 text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                Update Post
            </button>
        </div>
    </div>
</template>
