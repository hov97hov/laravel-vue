<script setup>
import {Link, router, usePage} from '@inertiajs/vue3';
import {computed, defineProps, ref} from 'vue';
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    posts: {
        type: Object,
        required: true,
    },
});

const confirmingPostDeletion = ref(false);
const postId = ref();

const {posts} = usePage().props;

const loadPage = (page) => {
    if (page > 0 && page <= posts.meta.last_page) {
        router.visit(`/?page=${page}`);
    }
};

const pages = computed(() => {
    let start = Math.max(posts.meta.current_page - 2, 1);
    let end = Math.min(posts.meta.current_page + 2, posts.meta.last_page);
    const result = [];

    for (let i = start; i <= end; i++) {
        result.push(i);
    }

    return result;
});

const closeModal = () => {
    confirmingPostDeletion.value = false;
};

const openDeleteConfirmationModal = (id) => {
    confirmingPostDeletion.value = true
    postId.value = id
}

const deletePost = async () => {
    try {
        const response = await axios.delete(route('post.destroy', {post: postId.value}));

        if (response.data.success) {
            router.visit('/');
        }

    } catch (error) {
        console.log(error)
    }
};

const {canLogin, canRegister} = props;
</script>
<template>
    <div class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen flex flex-col items-center">
        <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8 py-10">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Posts</h1>
                </div>
                <nav>
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                          class="inline-block px-4 py-2 rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                              class="inline-block px-4 py-2 rounded-md text-gray-900 bg-gray-200 hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                              class="inline-block px-4 py-2 rounded-md text-gray-900 bg-gray-200 hover:bg-gray-300 ml-3 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="bg-white shadow-md rounded-lg p-6 dark:bg-gray-800">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Post List</h2>
                    <Link v-if="$page.props.auth.user" :href="route('post.create')"
                          class="bg-green-500 text-white px-5 py-2 rounded-md hover:bg-green-600 focus:ring-2 focus:ring-green-400 focus:outline-none">
                        Create New Post
                    </Link>
                </div>
                <div
                    v-if="posts.data.length"
                    class="overflow-x-auto" >
                    <table class="min-w-full bg-gray-50 dark:bg-gray-700 border-collapse">
                        <thead>
                        <tr class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                            <th class="px-6 py-3 text-left text-sm font-medium">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Title</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Image</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Content</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Creator</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        <tr v-for="post in posts.data" :key="post.id">
                            <td class="px-6 py-4">{{ post.id }}</td>
                            <td class="px-6 py-4">{{ post.title }}</td>
                            <td class="px-6 py-4">
                                <img :src="`/storage/${post.image}`" alt="Preview"
                                     class="w-16 h-16 rounded-md object-cover shadow"/>
                            </td>
                            <td class="px-6 py-4">{{ post.content }}</td>
                            <td class="px-6 py-4">{{ post.user.name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <Link v-if="$page.props.auth.user && $page.props.auth.user.id === post.user.id"
                                          :href="route('post.edit', post.id)"
                                          class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Edit
                                    </Link>
                                    <Link
                                          :href="route('post.show', post.id)"
                                          class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Show
                                    </Link>
                                    <button
                                        v-if="$page.props.auth.user && $page.props.auth.user.id === post.user.id"
                                        @click="openDeleteConfirmationModal(post.id)"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-center" v-if="posts.data.length">
                    <nav class="flex space-x-2">
                        <button :disabled="!posts.meta.prev_page_url"
                                @click="loadPage(posts.meta.current_page - 1)"
                                class="px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 disabled:opacity-50">
                            Prev
                        </button>
                        <button v-for="page in pages" :key="page" @click="loadPage(page)"
                                :class="{
                                    'px-3 py-2 bg-blue-500 text-white rounded-md': posts.meta.current_page === page,
                                    'px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500': posts.meta.current_page !== page
                                }">
                            {{ page }}
                        </button>
                        <button :disabled="!posts.meta.next_page_url"
                                @click="loadPage(posts.meta.current_page + 1)"
                                class="px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 disabled:opacity-50">
                            Next
                        </button>
                    </nav>
                </div>
            </main>
        </div>

        <Modal :show="confirmingPostDeletion" @close="closeModal">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Are you sure you want to delete this post?</p>
                <div class="mt-4 flex justify-end space-x-3">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>
                    <DangerButton @click="deletePost">
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>

