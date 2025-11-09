<script setup>
import {ref} from 'vue'
import {router} from '../../router.js'

const title = ref('')
const description = ref('')
const content = ref('')
const loading = ref(false)
const errors = ref({})

const submit = async () => {
    try {
        loading.value = true
        errors.value = {}
        const response = await axios.post('/api/news', {
            title: title.value,
            description: description.value,
            content: content.value,
        })
        const created = response.data
        if (created?.id) {
            router.push({ name: 'news.show', params: { id: created.id } })
        } else {
            router.push({ name: 'news.index' })
        }
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors || {}
        } else if (e.response?.status === 403) {
            errors.value = { general: ['You are not allowed to create news.'] }
        } else {
            errors.value = { general: ['Something went wrong. Please try again.'] }
        }
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create News</h1>
        </div>

        <div v-if="errors.general" class="mb-4 bg-red-50 text-red-800 px-4 py-3 rounded">
            {{ errors.general[0] }}
        </div>

        <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input
                    type="text"
                    v-model="title"
                    class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-blue-500 bg-white p-2"
                    maxlength="100"
                    required
                />
                <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title[0] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                    v-model="description"
                    class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-blue-500 bg-white p-2"
                    rows="3"
                    maxlength="1024"
                    required
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description[0] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea
                    v-model="content"
                    class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-blue-500 bg-white p-2"
                    rows="10"
                    required
                ></textarea>
                <p v-if="errors.content" class="mt-1 text-sm text-red-600">{{ errors.content[0] }}</p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                    <svg v-if="loading" class="-ml-1 mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v4m0 0a4 4 0 00-4 4H4m8 8a8 8 0 008-8h-4" />
                    </svg>
                    Save
                </button>
                <RouterLink :to="{name: 'news.index'}" class="text-gray-600 hover:text-gray-800">Cancel</RouterLink>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
