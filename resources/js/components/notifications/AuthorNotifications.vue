<script setup>
import {onBeforeUnmount, onMounted, ref} from 'vue';
import {useAuth} from '../../auth.js';

const notifications = ref([]);
const timeouts = new Map();
const {user, checkAuth} = useAuth();
let channel = null;
let channelName = null;

const removeNotification = (id) => {
    notifications.value = notifications.value.filter((item) => item.id !== id);

    if (timeouts.has(id)) {
        clearTimeout(timeouts.get(id));
        timeouts.delete(id);
    }
};

const addNotification = (payload) => {
    const id = `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
    notifications.value.push({
        id,
        message: payload.message ?? '',
        meta: payload.meta ?? {},
        timestamp: payload.timestamp ?? null,
    });
    
    const timeoutId = setTimeout(() => removeNotification(id), 4000);
    timeouts.set(id, timeoutId);
};

const unsubscribe = () => {
    if (channelName) {
        Echo.leave(channelName);
    }

    channel = null;
    channelName = null;
};

const subscribe = () => {
    unsubscribe();

    channelName = `author_notification_${user.value.id}`
    channel = Echo.private(channelName)
        .listen('.author.notification', (event) => {
            console.log(event)
            addNotification(event);
        });
};

onMounted(async () => {
    await checkAuth();
    if (user.value?.id) {
        subscribe();
    }
});

onBeforeUnmount(() => {
    unsubscribe();
    Array.from(timeouts.values()).forEach((timeoutId) => clearTimeout(timeoutId));
    timeouts.clear();
});
</script>

<template>
    <div class="fixed bottom-4 right-4 z-50 flex flex-col-reverse space-y-2 space-y-reverse">
        <transition-group name="fade">
            <div
                v-for="notification in notifications"
                :key="notification.id"
                class="w-72 rounded-lg bg-white p-4 shadow-xl ring-1 ring-black/5 mt-2"
            >
                <p class="text-sm font-medium text-gray-900">
                    {{ notification.message }}
                </p>
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(8px);
}
</style>

