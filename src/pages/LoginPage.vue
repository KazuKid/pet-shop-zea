<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 px-4">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <!-- Logo -->
      <RouterLink to="/" class="flex items-center justify-center gap-2 mb-8">
        <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center shadow-md">
          <span class="text-white text-2xl">🐾</span>
        </div>
        <div class="leading-tight">
          <div class="font-display text-2xl font-bold text-primary-600">Zea</div>
          <div class="text-xs text-gray-500 -mt-1 font-medium">Pet Shop</div>
        </div>
      </RouterLink>

      <!-- Card -->
      <div class="card p-8">
        <h2 class="text-2xl font-display font-bold text-gray-900 text-center mb-1">Masuk ke Akunmu</h2>
        <p class="text-gray-500 text-sm text-center mb-7">Selamat datang kembali! 👋</p>

        <!-- Error -->
        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
          {{ error }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="input-field"
              placeholder="email@contoh.com"
              required
              autocomplete="email"
            />
          </div>

          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-sm font-semibold text-gray-700">Password</label>
            </div>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                class="input-field pr-11"
                placeholder="••••••••"
                required
                autocomplete="current-password"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="btn-primary w-full flex items-center justify-center gap-2"
          >
            <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ loading ? 'Memproses...' : 'Masuk' }}
          </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
          Belum punya akun?
          <RouterLink to="/register" class="text-primary-600 font-semibold hover:underline">Daftar sekarang</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth    = useAuthStore()
const router  = useRouter()
const route   = useRoute()

const loading      = ref(false)
const error        = ref('')
const showPassword = ref(false)

const form = reactive({ email: '', password: '' })

async function handleLogin() {
  loading.value = true
  error.value   = ''
  try {
    await auth.doLogin(form.email, form.password)
    const redirect = route.query.redirect ?? '/'
    router.push(redirect)
  } catch (err) {
    const msg = err?.response?.data?.errors?.email?.[0]
              ?? err?.response?.data?.message
              ?? 'Login gagal. Periksa email dan password kamu.'
    error.value = msg
  } finally {
    loading.value = false
  }
}
</script>
