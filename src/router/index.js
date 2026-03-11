import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('@/pages/HomePage.vue'),
    meta: { title: 'Beranda – Zea Pet Shop' },
  },
  {
    path: '/products',
    name: 'Products',
    component: () => import('@/pages/ProductsPage.vue'),
    meta: { title: 'Produk – Zea Pet Shop' },
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: () => import('@/pages/ProductDetailPage.vue'),
    meta: { title: 'Detail Produk – Zea Pet Shop' },
  },
  {
    path: '/cart',
    name: 'Cart',
    component: () => import('@/pages/CartPage.vue'),
    meta: { title: 'Keranjang – Zea Pet Shop' },
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('@/pages/CheckoutPage.vue'),
    meta: { title: 'Checkout – Zea Pet Shop' },
  },
  {
    path: '/about',
    name: 'About',
    component: () => import('@/pages/AboutPage.vue'),
    meta: { title: 'Tentang Kami – Zea Pet Shop' },
  },
  {
    path: '/contact',
    name: 'Contact',
    component: () => import('@/pages/ContactPage.vue'),
    meta: { title: 'Kontak – Zea Pet Shop' },
  },
  {
    path: '/account',
    name: 'Account',
    component: () => import('@/pages/AccountPage.vue'),
    meta: { title: 'Akun Saya – Zea Pet Shop', requiresAuth: true },
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/pages/LoginPage.vue'),
    meta: { title: 'Masuk – Zea Pet Shop', guest: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('@/pages/RegisterPage.vue'),
    meta: { title: 'Daftar – Zea Pet Shop', guest: true },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/pages/NotFoundPage.vue'),
    meta: { title: '404 – Zea Pet Shop' },
  },

  // ── Admin ──────────────────────────────────────────────────────────────
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: () => import('@/pages/admin/AdminLoginPage.vue'),
    meta: { title: 'Admin Login – Zea Pet Shop', guestAdmin: true },
  },
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAdmin: true },
    children: [
      { path: '', redirect: '/admin/dashboard' },
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: () => import('@/pages/admin/AdminDashboardPage.vue'),
        meta: { title: 'Dashboard – Admin', requiresAdmin: true },
      },
      {
        path: 'products',
        name: 'AdminProducts',
        component: () => import('@/pages/admin/AdminProductsPage.vue'),
        meta: { title: 'Produk – Admin', requiresAdmin: true },
      },
      {
        path: 'categories',
        name: 'AdminCategories',
        component: () => import('@/pages/admin/AdminCategoriesPage.vue'),
        meta: { title: 'Kategori – Admin', requiresAdmin: true },
      },
      {
        path: 'orders',
        name: 'AdminOrders',
        component: () => import('@/pages/admin/AdminOrdersPage.vue'),
        meta: { title: 'Pesanan – Admin', requiresAdmin: true },
      },
      {
        path: 'users',
        name: 'AdminUsers',
        component: () => import('@/pages/admin/AdminUsersPage.vue'),
        meta: { title: 'Pengguna – Admin', requiresAdmin: true },
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    return { top: 0, behavior: 'smooth' }
  },
})

router.afterEach((to) => {
  document.title = to.meta.title || 'Zea Pet Shop'
})

// Redirect logged-in users away from guest-only pages
router.beforeEach((to) => {
  const auth = useAuthStore()

  // Protected user routes
  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    return { path: '/login', query: { redirect: to.fullPath } }
  }

  // Admin protected routes
  if (to.meta.requiresAdmin) {
    if (!auth.isLoggedIn) return { path: '/admin/login', query: { redirect: to.fullPath } }
    if (!auth.isAdmin)    return { path: '/' }
  }

  // Admin login page – redirect to dashboard if already admin
  if (to.meta.guestAdmin && auth.isLoggedIn && auth.isAdmin) return '/admin/dashboard'

  // Regular guest-only routes
  if (to.meta.guest && auth.isLoggedIn) return '/'
})

export default router
