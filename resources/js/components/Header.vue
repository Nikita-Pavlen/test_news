<script setup>
import { onMounted } from 'vue'
import { useAuth } from '../auth.js'
import {router} from "../router.js";

const { user, isAuthenticated, checkAuth } = useAuth()

onMounted(async () => {
    await checkAuth()
})

const logout = function () {
    axios.post('/auth/logout').then(response => {
        router.push({name: 'login'})
    })
}
</script>

<template>
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <RouterLink :to="{name: 'news.index'}" class="text-2xl font-bold text-gray-900">
                        BlogApp
                    </RouterLink>
                </div>

                <!-- Menu -->
                <nav class="flex items-center space-x-6">
                    <RouterLink
                        v-if="isAuthenticated"
                        :to="{name: 'news.index'}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors"
                    >
                        News
                    </RouterLink>
                    <button
                        v-if="isAuthenticated"
                        @click="logout"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors cursor-pointer"
                        type="button"
                    >
                        Logout
                    </button>
                    <RouterLink
                        v-if="!isAuthenticated"
                        :to="{name: 'login'}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors"
                    >
                        Login
                    </RouterLink>
                    <RouterLink
                        v-if="!isAuthenticated"
                        :to="{name: 'registration'}"
                        class="text-gray-700 hover:text-gray-900 font-medium transition-colors"
                    >
                        Register
                    </RouterLink>
                </nav>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>
