<script setup>
import Header from "./Header.vue";
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <Header />

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <RouterView />
        </main>
    </div>
</template>

<style scoped>

</style>
