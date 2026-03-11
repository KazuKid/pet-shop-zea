<template>
  <div v-if="product" class="min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="text-sm text-gray-500 flex items-center gap-2 flex-wrap">
          <RouterLink to="/" class="hover:text-primary-600">Beranda</RouterLink>
          <span>/</span>
          <RouterLink to="/products" class="hover:text-primary-600">Produk</RouterLink>
          <span>/</span>
          <span class="text-gray-800 font-medium truncate max-w-xs">{{ product.name }}</span>
        </nav>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <!-- Images -->
        <div>
          <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100 mb-3">
            <img :src="activeImage" :alt="product.name" class="w-full h-full object-cover" />
          </div>
          <div class="flex gap-2 overflow-x-auto">
            <button
              v-for="(img, i) in product.images"
              :key="i"
              @click="activeImage = img"
              class="w-16 h-16 rounded-xl overflow-hidden border-2 shrink-0 flex-none transition-colors"
              :class="activeImage === img ? 'border-primary-500' : 'border-transparent'"
            >
              <img :src="img" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>

        <!-- Info -->
        <div class="flex flex-col">
          <!-- Badges -->
          <div class="flex gap-2 mb-3">
            <span v-if="product.isPromo" class="badge bg-red-100 text-red-600">PROMO</span>
            <span v-if="product.isNew" class="badge bg-accent-100 text-accent-700">BARU</span>
            <span class="badge bg-primary-100 text-primary-700">{{ product.categoryLabel }}</span>
          </div>

          <h1 class="text-2xl lg:text-3xl font-display font-bold text-gray-900 mb-3 leading-snug">{{ product.name }}</h1>

          <!-- Rating -->
          <div class="flex items-center gap-3 mb-4">
            <div class="flex">
              <span v-for="s in 5" :key="s" class="text-lg" :class="s <= Math.round(product.rating) ? 'text-yellow-400' : 'text-gray-200'">★</span>
            </div>
            <span class="text-gray-700 font-semibold">{{ product.rating }}</span>
            <span class="text-gray-400 text-sm">({{ product.reviews }} ulasan)</span>
            <span class="text-gray-400 text-sm">|</span>
            <span class="text-gray-500 text-sm">{{ product.sold }}+ terjual</span>
          </div>

          <!-- Price -->
          <div class="bg-primary-50 rounded-2xl p-4 mb-5">
            <div class="flex items-baseline gap-3">
              <span class="text-3xl font-bold text-primary-600">{{ formatPrice(product.price) }}</span>
              <span v-if="product.originalPrice" class="text-lg text-gray-400 line-through">{{ formatPrice(product.originalPrice) }}</span>
              <span v-if="product.originalPrice" class="badge bg-red-500 text-white ml-1">-{{ discountPct }}%</span>
            </div>
            <p v-if="product.originalPrice" class="text-green-600 text-sm font-medium mt-1">
              Hemat {{ formatPrice(product.originalPrice - product.price) }}
            </p>
          </div>

          <!-- Description -->
          <div class="mb-5">
            <h3 class="font-semibold text-gray-800 mb-2">Deskripsi Produk</h3>
            <p class="text-gray-600 text-sm leading-relaxed">{{ product.description }}</p>
          </div>

          <!-- Tags -->
          <div class="flex flex-wrap gap-2 mb-5">
            <span v-for="tag in product.tags" :key="tag" class="badge bg-gray-100 text-gray-600">
              #{{ tag }}
            </span>
          </div>

          <!-- Stock -->
          <div class="flex items-center gap-2 mb-5 text-sm">
            <span
              class="w-2 h-2 rounded-full"
              :class="product.stock > 10 ? 'bg-green-500' : product.stock > 0 ? 'bg-yellow-400' : 'bg-red-500'"
            ></span>
            <span :class="product.stock > 10 ? 'text-green-600' : product.stock > 0 ? 'text-yellow-600' : 'text-red-600'" class="font-medium">
              {{ product.stock > 10 ? `Stok tersedia (${product.stock})` : product.stock > 0 ? `Stok terbatas (${product.stock} tersisa)` : 'Stok habis' }}
            </span>
          </div>

          <!-- Quantity & Cart -->
          <div class="flex items-center gap-3 mb-4">
            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
              <button @click="decreaseQty" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors font-bold text-lg">−</button>
              <span class="w-12 text-center font-semibold text-gray-800">{{ qty }}</span>
              <button @click="increaseQty" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors font-bold text-lg">+</button>
            </div>
            <button
              @click="addToCart"
              :disabled="product.stock === 0"
              class="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed text-center"
            >
              🛒 Tambah ke Keranjang
            </button>
          </div>

          <button
            @click="productStore.toggleWishlist(product.id)"
            class="w-full btn-secondary flex items-center justify-center gap-2"
          >
            <svg
              class="w-5 h-5"
              :class="productStore.isWishlisted(product.id) ? 'text-red-500 fill-red-500' : 'text-gray-500'"
              fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            {{ productStore.isWishlisted(product.id) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
          </button>

          <!-- Shipping info -->
          <div class="mt-5 p-4 bg-gray-50 rounded-xl text-sm text-gray-600 space-y-2">
            <div class="flex items-center gap-2"><span>🚚</span> Gratis ongkir min. Rp200.000</div>
            <div class="flex items-center gap-2"><span>↩️</span> Retur mudah dalam 7 hari</div>
            <div class="flex items-center gap-2"><span>✅</span> Produk 100% original dan bergaransi</div>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <div v-if="related.length">
        <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
          <ProductCard v-for="p in related" :key="p.id" :product="p" />
        </div>
      </div>
    </div>

    <!-- Toast notification -->
    <Transition name="toast">
      <div v-if="toastVisible" class="fixed bottom-6 right-6 bg-gray-900 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium flex items-center gap-2 z-50">
        <span class="text-accent-400">✓</span> Ditambahkan ke keranjang!
      </div>
    </Transition>
  </div>

  <!-- Not found -->
  <div v-else class="min-h-screen flex items-center justify-center text-center px-4">
    <div>
      <div class="text-6xl mb-4">😿</div>
      <h2 class="text-2xl font-display font-bold text-gray-700 mb-2">Produk Tidak Ditemukan</h2>
      <RouterLink to="/products" class="btn-primary mt-4 inline-block">Kembali ke Produk</RouterLink>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useProductStore } from '@/stores/products'
import { useCartStore } from '@/stores/cart'
import ProductCard from '@/components/ProductCard.vue'

const route = useRoute()
const productStore = useProductStore()
const cartStore = useCartStore()

const product = computed(() => productStore.getById(route.params.id))
const activeImage = ref(product.value?.images[0] ?? '')
watch(product, (p) => { if (p) activeImage.value = p.images[0] })

const qty = ref(1)

const discountPct = computed(() =>
  product.value?.originalPrice
    ? Math.round((1 - product.value.price / product.value.originalPrice) * 100)
    : 0
)

const related = computed(() =>
  productStore.products
    .filter(p => p.category === product.value?.category && p.id !== product.value?.id)
    .slice(0, 4)
)

const toastVisible = ref(false)

function formatPrice(n) {
  return 'Rp' + n.toLocaleString('id-ID')
}

function increaseQty() {
  if (product.value && qty.value < product.value.stock) qty.value++
}

function decreaseQty() {
  if (qty.value > 1) qty.value--
}

function addToCart() {
  if (product.value) {
    cartStore.addItem(product.value, qty.value)
    showToast()
  }
}

function showToast() {
  toastVisible.value = true
  setTimeout(() => { toastVisible.value = false }, 2500)
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(16px);
}
</style>
