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
        <h2 class="text-2xl font-display font-bold text-gray-900 text-center mb-1">Buat Akun Baru</h2>
        <p class="text-gray-500 text-sm text-center mb-7">Bergabung dengan Zea Pet Shop! 🐾</p>

        <!-- Error -->
        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5">
          {{ error }}
        </div>

        <form @submit.prevent="handleRegister" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap *</label>
            <input
              v-model="form.name"
              type="text"
              class="input-field"
              :class="{ 'border-red-400': errors.name }"
              placeholder="Budi Santoso"
              required
              autocomplete="name"
            />
            <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
            <input
              v-model="form.email"
              type="email"
              class="input-field"
              :class="{ 'border-red-400': errors.email }"
              placeholder="email@contoh.com"
              required
              autocomplete="email"
            />
            <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP / WA</label>
            <input
              v-model="form.phone"
              type="tel"
              class="input-field"
              placeholder="0812-3456-7890"
              autocomplete="tel"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password *</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                class="input-field pr-11"
                :class="{ 'border-red-400': errors.password }"
                placeholder="Minimal 8 karakter"
                required
                autocomplete="new-password"
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
            <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password[0] }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password *</label>
            <div class="relative">
              <input
                v-model="form.password_confirmation"
                :type="showConfirm ? 'text' : 'password'"
                class="input-field pr-11"
                :class="{ 'border-red-400': confirmError }"
                placeholder="Ulangi password"
                required
                autocomplete="new-password"
              />
              <button
                type="button"
                @click="showConfirm = !showConfirm"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <svg v-if="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
            <p v-if="confirmError" class="text-red-500 text-xs mt-1">{{ confirmError }}</p>
          </div>

          <!-- Strength indicator -->
          <div v-if="form.password" class="space-y-1">
            <div class="flex gap-1">
              <div
                v-for="i in 4" :key="i"
                class="h-1 flex-1 rounded-full transition-colors"
                :class="i <= passwordStrength ? strengthColor : 'bg-gray-200'"
              ></div>
            </div>
            <p class="text-xs" :class="strengthTextColor">{{ strengthLabel }}</p>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="btn-primary w-full flex items-center justify-center gap-2 mt-2"
          >
            <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ loading ? 'Mendaftar...' : 'Daftar Sekarang' }}
          </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
          Sudah punya akun?
          <RouterLink to="/login" class="text-primary-600 font-semibold hover:underline">Masuk di sini</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth   = useAuthStore()
const router = useRouter()

const loading      = ref(false)
const error        = ref('')
const errors       = ref({})
const showPassword = ref(false)
const showConfirm  = ref(false)

const form = reactive({
  name                 : '',
  email                : '',
  phone                : '',
  password             : '',
  password_confirmation: '',
})

const confirmError = computed(() => {
  if (form.password_confirmation && form.password !== form.password_confirmation) {
    return 'Password tidak cocok.'
  }
  return ''
})

// Password strength
const passwordStrength = computed(() => {
  const p = form.password
  if (!p) return 0
  let score = 0
  if (p.length >= 8)              score++
  if (/[A-Z]/.test(p))            score++
  if (/[0-9]/.test(p))            score++
  if (/[^A-Za-z0-9]/.test(p))    score++
  return score
})

const strengthLabel = computed(() => {
  return ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'][passwordStrength.value] ?? ''
})
const strengthColor = computed(() => {
  return ['', 'bg-red-400', 'bg-yellow-400', 'bg-blue-400', 'bg-green-500'][passwordStrength.value]
})
const strengthTextColor = computed(() => {
  return ['', 'text-red-500', 'text-yellow-600', 'text-blue-600', 'text-green-600'][passwordStrength.value]
})

async function handleRegister() {
  if (confirmError.value) return
  loading.value = true
  error.value   = ''
  errors.value  = {}
  try {
    await auth.doRegister({
      name                 : form.name,
      email                : form.email,
      phone                : form.phone || undefined,
      password             : form.password,
      password_confirmation: form.password_confirmation,
    })
    router.push('/')
  } catch (err) {
    if (err?.response?.data?.errors) {
      errors.value = err.response.data.errors
    }
    error.value = err?.response?.data?.message ?? 'Pendaftaran gagal. Coba lagi.'
  } finally {
    loading.value = false
  }
}
</script>
