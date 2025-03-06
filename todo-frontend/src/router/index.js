import { createRouter, createWebHistory } from 'vue-router';

const isAuthenticated = () => !!localStorage.getItem('token');

const routes = [
    { path: '/login', component: () => import('@/components/Login.vue') },
    { 
        path: '/tasks', 
        component: () => import('@/components/TaskList.vue'), 
        meta: { requiresAuth: true } 
    },
    { path: '/', redirect: '/login' }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !isAuthenticated()) {
        next('/login'); 
    } else {
        next();
    }
});

export default router;
