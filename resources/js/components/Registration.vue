<script setup>
import {ref, onMounted} from 'vue'
import {router} from "../router.js";

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const role = ref('')
const roles = ref([])

onMounted(() => {
    axios.get('/api/roles').then(response => {
        roles.value = response.data
    })
})

const register = function () {
    axios.get('/sanctum/csrf-cookie').then(response => {
        axios.post('/auth/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
            role_id: role.value
        }).then(r => {
            router.push({name: 'news.index'})
        })
    })
}
</script>

<template>
    <div class="min-h-screen flex items-start justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 pt-24">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Create an account
                </h2>
            </div>
            <form class="mt-8 space-y-6" action="#" method="POST">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="name" class="sr-only">Name</label>
                        <input
                            v-model="name"
                            id="name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Name"
                        />
                    </div>
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input
                            v-model="email"
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Email address"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input
                            v-model="password"
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Password"
                        />
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">Confirm Password</label>
                        <input
                            v-model="passwordConfirmation"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Confirm Password"
                        />
                    </div>
                    <div>
                        <label for="role" class="sr-only">Role</label>
                        <select
                            v-model="role"
                            id="role"
                            name="role"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        >
                            <option value="" disabled>Select a role</option>
                            <option v-for="roleOption in roles" :key="roleOption.id" :value="roleOption.id">
                                {{ roleOption.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <RouterLink to="login" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign in to your account
                        </RouterLink>
                    </div>
                </div>

                <div>
                    <button
                        @click.prevent="register"
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                        Create account
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
