import { createMemoryHistory, createRouter } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'index',
        component: () => import('./components/Index.vue'),
    },
]

export const router = createRouter({
    history: createMemoryHistory(),
    routes,
})
