<script setup>
import axios from 'axios'
import {computed, ref, watch} from 'vue'

const props = defineProps({
    newsId: {
        type: [Number, String],
        required: true,
    },
    isLiked: {
        type: Boolean,
        default: false,
    },
    likesCount: {
        type: Number,
        default: 0,
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
})

const emit = defineEmits(['update:isLiked', 'update:likesCount', 'error'])

const liked = ref(props.isLiked)
const count = ref(props.likesCount)
const loading = ref(false)

watch(
    () => props.isLiked,
    (value) => {
        liked.value = value
    }
)

watch(
    () => props.likesCount,
    (value) => {
        count.value = value
    }
)

const sizeConfig = computed(() => {
    switch (props.size) {
    case 'sm':
        return {
            button: 'px-2 py-1 text-xs',
            icon: 'h-4 w-4',
            count: 'text-xs',
        }
    case 'lg':
        return {
            button: 'px-4 py-2 text-base',
            icon: 'h-6 w-6',
            count: 'text-base',
        }
    default:
        return {
            button: 'px-3 py-1.5 text-sm',
            icon: 'h-5 w-5',
            count: 'text-sm',
        }
    }
})

const heartClasses = computed(() => liked.value ? 'text-red-600' : 'text-gray-400')

const toggleLike = async () => {
    if (loading.value) {
        return
    }

    loading.value = true

    try {
        const url = `/api/news/${props.newsId}/likes`
        const response = liked.value
            ? await axios.delete(url)
            : await axios.post(url)

        liked.value = response.data.is_liked
        count.value = response.data.likes_count

        emit('update:isLiked', liked.value)
        emit('update:likesCount', count.value)
    } catch (error) {
        emit('error', error)
        console.error('Failed to toggle like', error)
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <button
        type="button"
        :disabled="loading"
        :aria-pressed="liked"
        :class="[
            'inline-flex items-center rounded-full border border-gray-200 bg-white text-gray-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-70 cursor-pointer',
            sizeConfig.button
        ]"
        @click.stop.prevent="toggleLike"
    >
        <svg
            :class="[sizeConfig.icon, heartClasses]"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            aria-hidden="true"
        >
            <path
                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
            />
        </svg>
        <span class="ml-2 font-medium" :class="sizeConfig.count">
            {{ count }}
        </span>
    </button>
</template>

<style scoped>
</style>

