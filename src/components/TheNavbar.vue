<template>
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <!-- Top bar -->
    <div class="bg-primary-500 text-white text-center text-sm py-2 px-4">
      🚚 Kami melayani pengiriman ke seluruh indonesia! &nbsp;|&nbsp; 📞 WA: 0812-3456-7890
    </div>

    <!-- Main navbar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <RouterLink to="/" class="flex items-center gap-2 group">
          <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center shadow-md group-hover:bg-primary-600 transition-colors">
            <span class="text-white text-xl">🐾</span>
          </div>
          <div class="leading-tight">
            <div class="font-display text-xl font-bold text-primary-600">Zea</div>
            <div class="text-xs text-gray-500 -mt-1 font-medium">Pet Shop</div>
          </div>
        </RouterLink>

        <!-- Search bar (desktop) -->
        <div class="hidden md:flex flex-1 max-w-xl mx-8 relative">
          <input
            v-model="search"
            @keyup.enter="goSearch"
            type="text"
            placeholder="Cari produk untuk hewan peliharaanmu..."
            class="w-full border border-gray-200 rounded-xl pl-5 pr-14 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all"
          />
          <button
            @click="goSearch"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg px-3 py-1.5 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </button>
        </div>

        <!-- Nav Actions -->
        <div class="flex items-center gap-2">
          <!-- Desktop nav links -->
          <nav class="hidden lg:flex items-center gap-1 mr-2">
            <RouterLink
              v-for="link in navLinks"
              :key="link.to"
              :to="link.to"
              class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-colors"
              active-class="text-primary-600 bg-primary-50"
            >
              {{ link.label }}
            </RouterLink>
          </nav>

          <!-- Auth buttons -->
          <template v-if="!authStore.isLoggedIn">
            <RouterLink to="/login" class="hidden sm:inline-flex btn-secondary text-sm px-4 py-2">Masuk</RouterLink>
            <RouterLink to="/register" class="hidden sm:inline-flex btn-primary text-sm px-4 py-2">Daftar</RouterLink>
          </template>

          <!-- User dropdown -->
          <div v-else class="relative" ref="dropdownRef">
            <button
              @click="dropdownOpen = !dropdownOpen"
              class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-primary-50 transition-colors"
            >
              <div class="w-7 h-7 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                {{ authStore.user?.name?.[0]?.toUpperCase() ?? '?' }}
              </div>
              <span class="text-sm font-medium text-gray-700 max-w-[100px] truncate">{{ authStore.user?.name }}</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>

            <!-- Dropdown menu -->
            <Transition name="fade-down">
              <div
                v-if="dropdownOpen"
                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
              >
                <div class="px-4 py-2 border-b border-gray-100">
                  <p class="text-xs text-gray-500">Login sebagai</p>
                  <p class="text-sm font-semibold text-gray-800 truncate">{{ authStore.user?.email }}</p>
                </div>
                <RouterLink
                  to="/account"
                  @click="dropdownOpen = false"
                  class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Akun Saya
                </RouterLink>
                <RouterLink
                  v-if="authStore.isAdmin"
                  to="/admin/dashboard"
                  @click="dropdownOpen = false"
                  class="w-full text-left px-4 py-2.5 text-sm text-primary-600 hover:bg-primary-50 transition-colors flex items-center gap-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Dashboard Admin
                </RouterLink>
                <button
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Keluar
                </button>
              </div>
            </Transition>
          </div>

          <!-- Cart -->
          <RouterLink
            to="/cart"
            class="relative p-2 rounded-xl hover:bg-primary-50 transition-colors text-gray-600 hover:text-primary-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span
              v-if="cartStore.totalItems > 0"
              class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold"
            >
              {{ cartStore.totalItems > 99 ? '99+' : cartStore.totalItems }}
            </span>
          </RouterLink>

          <!-- Mobile menu button -->
          <button
            @click="mobileOpen = !mobileOpen"
            class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors"
          >
            <svg v-if="!mobileOpen" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg v-else class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile search -->
      <div class="md:hidden pb-3">
        <div class="relative">
          <input
            v-model="search"
            @keyup.enter="goSearch"
            type="text"
            placeholder="Cari produk..."
            class="w-full border border-gray-200 rounded-xl pl-4 pr-12 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
          />
          <button
            @click="goSearch"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary-500 text-white rounded-lg px-2 py-1.5"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <Transition name="slide-down">
      <div v-if="mobileOpen" class="lg:hidden bg-white border-t border-gray-100 px-4 pb-4">
        <nav class="flex flex-col gap-1 pt-2">
          <RouterLink
            v-for="link in navLinks"
            :key="link.to"
            :to="link.to"
            @click="mobileOpen = false"
            class="px-4 py-3 rounded-xl text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-colors"
            active-class="text-primary-600 bg-primary-50"
          >
            {{ link.label }}
          </RouterLink>
          <!-- Mobile auth links -->
          <template v-if="!authStore.isLoggedIn">
            <RouterLink to="/login"    @click="mobileOpen = false" class="px-4 py-3 rounded-xl text-sm font-medium text-primary-600 hover:bg-primary-50 transition-colors">Masuk</RouterLink>
            <RouterLink to="/register" @click="mobileOpen = false" class="px-4 py-3 rounded-xl text-sm font-medium text-primary-600 hover:bg-primary-50 transition-colors">Daftar</RouterLink>
          </template>
          <template v-else>
            <RouterLink
              to="/account"
              @click="mobileOpen = false"
              class="px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Akun Saya
            </RouterLink>
            <RouterLink
              v-if="authStore.isAdmin"
              to="/admin/dashboard"
              @click="mobileOpen = false"
              class="px-4 py-3 rounded-xl text-sm font-medium text-primary-600 hover:bg-primary-50 transition-colors flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Dashboard Admin
            </RouterLink>
            <button @click="handleLogout; mobileOpen = false" class="px-4 py-3 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors text-left">
              Keluar ({{ authStore.user?.name }})
            </button>
          </template>
        </nav>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useProductStore } from '@/stores/products'
import { useAuthStore } from '@/stores/auth'

const cartStore    = useCartStore()
const productStore = useProductStore()
const authStore    = useAuthStore()
const router       = useRouter()
const search       = ref('')
const mobileOpen   = ref(false)
const dropdownOpen = ref(false)
const dropdownRef  = ref(null)

const navLinks = [
  { to: '/', label: 'Beranda' },
  { to: '/products', label: 'Produk' },
  { to: '/about', label: 'Tentang Kami' },
  { to: '/contact', label: 'Kontak' },
]

function goSearch() {
  if (search.value.trim()) {
    productStore.searchQuery = search.value.trim()
    router.push('/products')
    mobileOpen.value = false
  }
}

async function handleLogout() {
  dropdownOpen.value = false
  await authStore.doLogout()
  router.push('/')
}

function onClickOutside(e) {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    dropdownOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', onClickOutside))
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

.fade-down-enter-active,
.fade-down-leave-active {
  transition: all 0.15s ease;
}
.fade-down-enter-from,
.fade-down-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
