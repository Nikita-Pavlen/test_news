import { ref, computed } from 'vue'

const user = ref(null)
const isAuthenticated = computed(() => !!user.value)

export function useAuth() {
    const checkAuth = async () => {
        try {
            const response = await axios.get('/api/user')
            user.value = response.data
            return true
        } catch (error) {
            user.value = null
            return false
        }
    }

    return {
        user,
        isAuthenticated,
        checkAuth
    }
}
