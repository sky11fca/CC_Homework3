import {createRouter, createWebHistory, RouteRecordRaw} from "vue-router";

const routes: Array<RouteRecordRaw> = [
    {path: "/", redirect: "/login"},
    {path: "/login", name: "LoginOrRegister" ,component: () => import("@/pages/LoginOrRegister.vue")},
    {path: "/shop", name: "ShopPage", component: () => import("@/pages/ShopPage.vue")},
    {path: "/admin", name: "AdminDashboard", component: () => import("@/pages/AdminDashboard.vue")},
    {path: "/ai", name: "AiAssistant", component: () => import("@/pages/AiAssistant.vue")}
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Global route guard to redirect unauthenticated users to login
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('jwt_token');
    const isAuthenticated = !!token;

    if (to.name !== 'LoginOrRegister' && !isAuthenticated) {
        next({ name: 'LoginOrRegister' });
    } else {
        next();
    }
})

export default router