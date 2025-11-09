<script setup>
import Header from "./Header.vue";
import AuthorNotifications from "./notifications/AuthorNotifications.vue";
import {useAuth} from "../auth.js";
import {onMounted} from "vue";

const {user} = useAuth();

</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <AuthorNotifications v-if="user?.role_id === 1 || user?.role_id === 3" />

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
