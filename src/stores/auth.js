import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { login, register, logout, fetchAuthUser } from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(JSON.parse(localStorage.getItem('auth_user') ?? 'null'))
  const token = ref(localStorage.getItem('auth_token') ?? null)

  const isLoggedIn = computed(() => !!token.value)
  const isAdmin    = computed(() => user.value?.role === 'admin')

  function _persist(userData, tokenValue) {
    user.value  = userData
    token.value = tokenValue
    localStorage.setItem('auth_user',  JSON.stringify(userData))
    localStorage.setItem('auth_token', tokenValue)
  }

  function _clear() {
    user.value  = null
    token.value = null
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
  }

  async function doLogin(email, password) {
    const res = await login({ email, password })
    _persist(res.data.user, res.data.token)
    return res.data
  }

  async function doRegister(data) {
    const res = await register(data)
    _persist(res.data.user, res.data.token)
    return res.data
  }

  async function doLogout() {
    try { await logout() } catch {}
    _clear()
  }

  async function fetchUser() {
    try {
      const res = await fetchAuthUser()
      user.value = res.data
      localStorage.setItem('auth_user', JSON.stringify(res.data))
    } catch {
      _clear()
    }
  }

  function updateUser(userData) {
    user.value = userData
    localStorage.setItem('auth_user', JSON.stringify(userData))
  }

  return { user, token, isLoggedIn, isAdmin, doLogin, doRegister, doLogout, fetchUser, updateUser }
})
