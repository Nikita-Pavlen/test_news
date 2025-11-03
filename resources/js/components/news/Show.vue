<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { formatDate } from '../../date.js'

const route = useRoute()

const loading = ref(true)
const errorMessage = ref('')
const item = ref(null)

const fetchNewsItem = async () => {
    try {
        loading.value = true
        errorMessage.value = ''
        const response = await axios.get(`/api/news/${route.params.id}`)
        item.value = response.data
    } catch (error) {
        errorMessage.value = 'Failed to load the article.'
        console.error('Error fetching news item:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchNewsItem()
})
</script>

<template>
    <div class="container mx-auto px-4 py-8">
        <!-- Back link -->
        <div class="mb-6">
            <RouterLink :to="{name:'news.index'}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to News
            </RouterLink>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Error -->
        <div v-else-if="errorMessage" class="bg-red-50 text-red-800 px-4 py-3 rounded">
            {{ errorMessage }}
        </div>

        <!-- Not found -->
        <div v-else-if="!item" class="text-center text-gray-600 py-12">
            Article not found.
        </div>

        <!-- Article -->
        <article v-else class="bg-white rounded-lg shadow-sm p-6">
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ item.title }}</h1>
                <div class="flex flex-wrap items-center text-sm text-gray-500">
                    <div class="flex items-center mr-4">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                            <span class="text-blue-600 font-semibold text-sm">{{ item.user?.name?.charAt(0)?.toUpperCase() }}</span>
                        </div>
                        <span class="text-gray-900">{{ item.user?.name }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <time :datetime="item.created_at">{{ formatDate(item.created_at) }}</time>
                    </div>
                </div>
            </header>

            <p class="text-gray-700 mb-6">{{ item.description }}</p>

            <div class="prose max-w-none">
                <div v-html="item.content"></div>
            </div>
        </article>
    </div>
</template>

<style scoped>
</style>
