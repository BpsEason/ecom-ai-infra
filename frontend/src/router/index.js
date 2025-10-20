import { createRouter, createWebHistory } from 'vue-router';
const routes = [
    { path: '/', name: 'Dashboard', component: () => import('../views/DashboardView.vue') },
    { path: '/products', name: 'Products', component: () => import('../views/ProductView.vue') },
    { path: '/orders', name: 'Orders', component: () => import('../views/OrderView.vue') },
    { path: '/marketing', name: 'Marketing', component: () => import('../views/MarketingView.vue') },
    { path: '/customerservice', name: 'CustomerService', component: () => import('../views/CustomerView.vue') },
];
const router = createRouter({ history: createWebHistory(), routes });
export default router;
