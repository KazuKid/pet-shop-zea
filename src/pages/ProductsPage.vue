<template>
  <div class="min-h-screen">
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="text-sm text-gray-500 mb-2">
          <RouterLink to="/" class="hover:text-primary-600">Beranda</RouterLink>
          <span class="mx-2">/</span>
          <span class="text-gray-800 font-medium">Produk</span>
        </nav>
        <h1 class="text-3xl font-display font-bold text-gray-900">Semua Produk</h1>
        <p class="text-gray-500 text-sm mt-1">{{ productStore.filtered.length }} produk ditemukan</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 shrink-0">
          <div class="card p-5 sticky top-20">
            <h3 class="font-display font-bold text-gray-900 mb-4 text-lg">Filter</h3>

            <!-- Category filter -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-700 text-sm mb-3">Kategori</h4>
              <div class="space-y-2">
                <label
                  v-for="cat in categories"
                  :key="cat.id"
                  class="flex items-center gap-3 cursor-pointer group"
                >
                  <input
                    type="radio"
                    name="category"
                    :value="cat.id"
                    v-model="productStore.selectedCategory"
                    class="accent-primary-500"
                  />
                  <span class="text-sm text-gray-600 group-hover:text-primary-600 transition-colors flex-1">
                    {{ cat.icon }} {{ cat.name }}
                  </span>
                </label>
              </div>
            </div>

            <!-- Sort -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-700 text-sm mb-3">Urutkan</h4>
              <select v-model="productStore.sortBy" class="input-field text-sm">
                <option value="default">Default</option>
                <option value="price-asc">Harga: Termurah</option>
                <option value="price-desc">Harga: Termahal</option>
                <option value="rating">Rating Tertinggi</option>
                <option value="popular">Terlaris</option>
              </select>
            </div>

            <!-- Labels -->
            <div>
              <h4 class="font-semibold text-gray-700 text-sm mb-3">Label</h4>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="lbl in labels"
                  :key="lbl.id"
                  @click="toggleLabel(lbl.id)"
                  class="badge transition-all"
                  :class="activeLabels.includes(lbl.id)
                    ? 'bg-primary-100 text-primary-700 ring-2 ring-primary-400'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                  {{ lbl.name }}
                </button>
              </div>
            </div>

            <button
              @click="resetFilters"
              class="mt-6 w-full text-sm text-gray-500 hover:text-primary-600 transition-colors border border-gray-200 rounded-xl py-2 hover:border-primary-300"
            >
              Reset Filter
            </button>
          </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
          <!-- Search & Sort bar -->
          <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1">
              <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input
                v-model="productStore.searchQuery"
                type="text"
                placeholder="Cari produk..."
                class="input-field pl-10 text-sm"
              />
            </div>
            <div class="sm:hidden">
              <select v-model="productStore.sortBy" class="input-field text-sm">
                <option value="default">Default</option>
                <option value="price-asc">Harga ↑</option>
                <option value="price-desc">Harga ↓</option>
                <option value="rating">Rating</option>
                <option value="popular">Terlaris</option>
              </select>
            </div>
          </div>

          <!-- Loading state -->
          <div v-if="productStore.loading" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-5">
            <div v-for="n in 8" :key="n" class="bg-white rounded-2xl overflow-hidden animate-pulse">
              <div class="h-48 bg-gray-200"></div>
              <div class="p-4 space-y-2">
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
              </div>
            </div>
          </div>

          <!-- Empty state -->
          <div v-else-if="productStore.filtered.length === 0" class="text-center py-20">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="font-display font-bold text-xl text-gray-700 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 text-sm mb-4">Coba kata kunci yang berbeda atau reset filter</p>
            <button @click="resetFilters" class="btn-primary text-sm px-5 py-2">Reset Filter</button>
          </div>

          <!-- Grid -->
          <div v-else class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-5">
            <ProductCard
              v-for="p in productStore.filtered"
              :key="p.id"
              :product="p"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useProductStore } from '@/stores/products'
import ProductCard from '@/components/ProductCard.vue'

const productStore = useProductStore()
const activeLabels = ref([])

const categories = [
  { id: 'all',    name: 'Semua',   icon: '🐾' },
  { id: 'dog',    name: 'Anjing',  icon: '🐶' },
  { id: 'cat',    name: 'Kucing',  icon: '🐱' },
  { id: 'bird',   name: 'Burung',  icon: '🐦' },
  { id: 'fish',   name: 'Ikan',    icon: '🐟' },
  { id: 'rabbit', name: 'Kelinci', icon: '🐰' },
]

const labels = [
  { id: 'promo', name: 'Promo' },
  { id: 'new',   name: 'Baru' },
]

function toggleLabel(id) {
  const idx = activeLabels.value.indexOf(id)
  if (idx >= 0) activeLabels.value.splice(idx, 1)
  else activeLabels.value.push(id)
}

function resetFilters() {
  productStore.selectedCategory = 'all'
  productStore.searchQuery = ''
  productStore.sortBy = 'default'
  activeLabels.value = []
}

// Load on mount
onMounted(() => productStore.loadProducts())

// Reload when filters change
watch(
  () => [productStore.selectedCategory, productStore.sortBy],
  () => productStore.loadProducts()
)

// Debounce search input
let searchTimer
watch(
  () => productStore.searchQuery,
  () => {
    clearTimeout(searchTimer)
    searchTimer = setTimeout(() => productStore.loadProducts(), 400)
  }
)
</script>
