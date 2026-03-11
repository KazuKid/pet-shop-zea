import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'https://pet-shop-zea-production.up.railway.app/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

// Attach Bearer token from localStorage if available
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

// ── Products ──────────────────────────────────────────────────────────────

export function fetchProducts(params = {}) {
  return api.get('/products', { params })
}

export function fetchProduct(id) {
  return api.get(`/products/${id}`)
}

// ── Categories ────────────────────────────────────────────────────────────

export function fetchCategories() {
  return api.get('/categories')
}

// ── Orders ────────────────────────────────────────────────────────────────

export function createOrder(payload) {
  return api.post('/orders', payload)
}

export function fetchOrder(orderNumber) {
  return api.get(`/orders/${orderNumber}`)
}

// ── Auth ──────────────────────────────────────────────────────────────────

export function login(credentials) {
  return api.post('/auth/login', credentials)
}

export function register(data) {
  return api.post('/auth/register', data)
}

export function logout() {
  return api.post('/auth/logout')
}

export function fetchAuthUser() {
  return api.get('/auth/user')
}

// Profile
export function updateProfile(data) {
  return api.put('/profile', data)
}

export function fetchMyOrders(params = {}) {
  return api.get('/profile/orders', { params })
}

// ── Admin ─────────────────────────────────────────────────────────────────

export function fetchAdminStats() {
  return api.get('/admin/stats')
}

// Admin Products
export function adminCreateProduct(data) {
  return api.post('/admin/products', data)
}

export function adminUpdateProduct(id, data) {
  return api.put(`/admin/products/${id}`, data)
}

export function adminDeleteProduct(id) {
  return api.delete(`/admin/products/${id}`)
}

// Admin Categories
export function adminCreateCategory(data) {
  return api.post('/admin/categories', data)
}

export function adminUpdateCategory(id, data) {
  return api.put(`/admin/categories/${id}`, data)
}

export function adminDeleteCategory(id) {
  return api.delete(`/admin/categories/${id}`)
}

// Admin Orders
export function fetchAdminOrders(params = {}) {
  return api.get('/admin/orders', { params })
}

export function adminUpdateOrderStatus(id, status) {
  return api.put(`/admin/orders/${id}/status`, { status })
}

// Admin Users
export function fetchAdminUsers(params = {}) {
  return api.get('/admin/users', { params })
}

export function fetchAdminUser(id) {
  return api.get(`/admin/users/${id}`)
}

export function adminUpdateUser(id, data) {
  return api.put(`/admin/users/${id}`, data)
}

export function adminDeleteUser(id) {
  return api.delete(`/admin/users/${id}`)
}

export default api
