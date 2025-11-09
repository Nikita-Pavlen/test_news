<script setup>
import {ref, onMounted} from 'vue'
import {formatDate} from "../../date.js";
import {useAuth} from '../../auth.js'
import LikeButton from './LikeButton.vue'

const news = ref([])
const loading = ref(true)
const { user } = useAuth()

const fetchNews = async () => {
    try {
        loading.value = true
        const response = await axios.get('/api/news')
        news.value = response.data
    } catch (error) {
        console.error('Error fetching news:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchNews()
})
</script>

<template>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Latest News</h1>
                    <p class="text-gray-600">Stay updated with the latest articles and stories</p>
                </div>
                <div v-if="user.role_id === 1 || user.role_id === 3">
                    <RouterLink
                        :to="{name: 'news.create'}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700"
                    >
                        Create news
                    </RouterLink>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="news.length === 0" class="text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No news yet</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for updates.</p>
            </div>
        </div>

        <!-- News List -->
        <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <RouterLink
                v-for="item in news"
                :key="item.id"
                :to="`/news/${item.id}`"
                class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <!-- News Card Content -->
                <div class="p-6 flex-1 flex flex-col">
                    <!-- Title -->
                    <h2 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                        {{ item.title }}
                    </h2>

                    <!-- Description -->
                    <p class="text-gray-600 mb-4 line-clamp-3 flex-1">
                        {{ item.description }}
                    </p>

                    <!-- Read more label -->
                    <div class="mt-1 mb-2">
                        <span class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            Read more
                            <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>

                    <!-- Footer Info -->
                    <div class="mt-auto pt-4 border-t border-gray-200">
                        <!-- Author -->
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            {{ item.user?.name?.charAt(0)?.toUpperCase() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ item.user?.name }}
                                    </p>
                                </div>
                            </div>
                            <LikeButton
                                :news-id="item.id"
                                :is-liked="item.is_liked"
                                :likes-count="item.likes_count ?? 0"
                                @update:isLiked="value => item.is_liked = value"
                                @update:likesCount="value => item.likes_count = value"
                            />
                        </div>

                        <!-- Date -->
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <time :datetime="item.created_at">
                                {{ formatDate(item.created_at) }}
                            </time>
                        </div>
                    </div>
                </div>
            </RouterLink>
        </div>
    </div>
</template>
