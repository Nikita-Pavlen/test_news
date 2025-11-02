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
                    <h1 class="text-2xl font-bold text-gray-900">BlogApp</h1>
                </div>

                <!-- Menu -->
                <nav class="flex items-center space-x-6">
                    <a href="#" v-if="isAuthenticated" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">News</a>
                    <a href="#" v-if="isAuthenticated" @click="logout" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">Logout</a>
                    <a href="#" v-if="!isAuthenticated" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">Login</a>
                    <a href="#" v-if="!isAuthenticated" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">Register</a>
                </nav>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>
