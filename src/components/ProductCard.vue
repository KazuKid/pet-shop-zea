<template>
  <div class="card overflow-hidden group cursor-pointer" @click="$router.push(`/products/${product.id}`)">
    <!-- Image -->
    <div class="relative overflow-hidden bg-gray-100 aspect-square">
      <img
        :src="product.image"
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        loading="lazy"
      />
      <!-- Badges -->
      <div class="absolute top-3 left-3 flex flex-col gap-1.5">
        <span v-if="product.isPromo" class="badge bg-red-500 text-white">PROMO</span>
        <span v-if="product.isNew" class="badge bg-accent-500 text-white">BARU</span>
      </div>
      <!-- Wishlist button -->
      <button
        @click.stop="productStore.toggleWishlist(product.id)"
        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center hover:bg-primary-50 transition-colors"
      >
        <svg
          class="w-4 h-4 transition-colors"
          :class="productStore.isWishlisted(product.id) ? 'text-red-500 fill-red-500' : 'text-gray-400'"
          fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
      </button>
      <!-- Quick add -->
      <div class="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-200">
        <button
          @click.stop="addToCart"
          class="w-full bg-primary-500 hover:bg-primary-600 text-white text-sm font-semibold py-2.5 transition-colors"
        >
          + Tambah ke Keranjang
        </button>
      </div>
    </div>

    <!-- Info -->
    <div class="p-4">
      <!-- Category -->
      <div class="text-xs text-primary-500 font-semibold mb-1">{{ product.categoryLabel }}</div>
      <!-- Name -->
      <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 leading-snug">{{ product.name }}</h3>
      <!-- Rating -->
      <div class="flex items-center gap-1.5 mb-2">
        <div class="flex">
          <span v-for="s in 5" :key="s" class="text-xs" :class="s <= Math.round(product.rating) ? 'text-yellow-400' : 'text-gray-200'">★</span>
        </div>
        <span class="text-xs text-gray-500">{{ product.rating }} ({{ product.reviews }})</span>
      </div>
      <!-- Price -->
      <div class="flex items-center gap-2 flex-wrap">
        <span class="font-bold text-primary-600 text-base">{{ formatPrice(product.price) }}</span>
        <span v-if="product.originalPrice" class="text-xs text-gray-400 line-through">{{ formatPrice(product.originalPrice) }}</span>
        <span v-if="product.originalPrice" class="badge bg-red-100 text-red-600">
          -{{ discountPercent }}%
        </span>
      </div>
      <!-- Sold -->
      <div class="text-xs text-gray-400 mt-1.5">{{ product.sold }}+ terjual</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useProductStore } from '@/stores/products'

const props = defineProps({
  product: { type: Object, required: true },
})

const cartStore = useCartStore()
const productStore = useProductStore()

const discountPercent = computed(() =>
  props.product.originalPrice
    ? Math.round((1 - props.product.price / props.product.originalPrice) * 100)
    : 0
)

function formatPrice(n) {
  return 'Rp' + n.toLocaleString('id-ID')
}

function addToCart() {
  cartStore.addItem(props.product, 1)
}
</script>
