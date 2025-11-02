import {createWebHistory, createRouter} from 'vue-router'
import {useAuth} from "./auth.js";

const routes = [
    {
        path: '/',
        name: 'index',
        component: () => import('./components/Index.vue'),
        meta: {requiresAuth: true}
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('./components/Login.vue'),
        meta: {requiresGuest: true}
    },
    {
        path: '/registration',
        name: 'registration',
        component: () => import('./components/Registration.vue'),
        meta: {requiresGuest: true}
    },
]

export const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from, next) => {
    const {checkAuth} = useAuth()
    const authenticated = await checkAuth()

    if (to.meta.requiresAuth && !authenticated) {
        next({name: 'login'})
        return
    }


    if (to.meta.requiresGuest && authenticated) {
        next({name: 'index'})
        return
    }

    next()
})
