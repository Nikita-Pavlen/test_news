import {createWebHistory, createRouter} from 'vue-router'
import {useAuth} from "./auth.js";

const routes = [
    {
        path: '/',
        name: 'news.index',
        component: () => import('./components/news/Index.vue'),
        meta: {requiresAuth: true}
    },
    {
        path: '/news/:id',
        name: 'news.show',
        component: () => import('./components/news/Show.vue'),
        meta: {requiresAuth: true}
    },
    {
        path: '/news/create',
        name: 'news.create',
        component: () => import('./components/news/Create.vue'),
        meta: {requiresAuth: true, roleAuthor: true},
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
    const {checkAuth, user, isAuthenticated} = useAuth()
    await checkAuth()

    if (to.meta.requiresAuth && !isAuthenticated.value) {
        next({name: 'login'})
        return
    }

    if (to.meta.requiresGuest && isAuthenticated.value) {
        next({name: 'news.index'})
        return
    }

    if (to.meta.roleAuthor && user.value.role_id !== 1 && user.value.role_id !== 3) {
        next({name: 'news.index'})
        return
    }

    next()
})
