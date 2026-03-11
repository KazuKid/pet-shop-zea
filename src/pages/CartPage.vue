<template>
  <div class="min-h-screen">
    <!-- Header -->
    <div class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="text-sm text-gray-500 mb-2">
          <RouterLink to="/" class="hover:text-primary-600">Beranda</RouterLink>
          <span class="mx-2">/</span>
          <span class="text-gray-800 font-medium">Keranjang</span>
        </nav>
        <h1 class="text-3xl font-display font-bold text-gray-900">
          Keranjang Belanja
          <span v-if="cartStore.totalItems > 0" class="text-lg text-gray-500 font-normal">({{ cartStore.totalItems }} item)</span>
        </h1>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Empty cart -->
      <div v-if="cartStore.items.length === 0" class="text-center py-24">
        <div class="text-7xl mb-5">🛒</div>
        <h2 class="text-2xl font-display font-bold text-gray-700 mb-2">Keranjang Masih Kosong</h2>
        <p class="text-gray-500 mb-6 text-sm">Yuk, mulai belanja produk untuk hewan peliharaanmu!</p>
        <RouterLink to="/products" class="btn-primary">Mulai Belanja</RouterLink>
      </div>

      <!-- Cart content -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart items -->
        <div class="lg:col-span-2 space-y-4">
          <div v-for="item in cartStore.items" :key="item.id" class="card p-4 flex gap-4">
            <RouterLink :to="`/products/${item.id}`" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-gray-100 shrink-0">
              <img :src="item.image" :alt="item.name" class="w-full h-full object-cover hover:scale-105 transition-transform" />
            </RouterLink>
            <div class="flex-1 min-w-0">
              <RouterLink :to="`/products/${item.id}`" class="font-semibold text-gray-800 text-sm sm:text-base hover:text-primary-600 transition-colors line-clamp-2">
                {{ item.name }}
              </RouterLink>
              <div class="text-primary-600 font-bold mt-1 text-lg">{{ formatPrice(item.price) }}</div>
              <div class="flex items-center justify-between mt-3 flex-wrap gap-3">
                <!-- Qty controls -->
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                  <button @click="cartStore.updateQty(item.id, item.qty - 1)" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition-colors text-gray-600 font-bold">−</button>
                  <span class="w-10 text-center text-sm font-semibold">{{ item.qty }}</span>
                  <button @click="cartStore.updateQty(item.id, item.qty + 1)" :disabled="item.qty >= item.stock" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition-colors text-gray-600 font-bold disabled:opacity-40">+</button>
                </div>
                <!-- Item total -->
                <div class="text-sm text-gray-500">
                  = <span class="font-semibold text-gray-700">{{ formatPrice(item.price * item.qty) }}</span>
                </div>
                <!-- Remove -->
                <button @click="cartStore.removeItem(item.id)" class="text-red-400 hover:text-red-600 transition-colors p-1.5 rounded-lg hover:bg-red-50">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Clear cart -->
          <div class="flex justify-end">
            <button @click="cartStore.clearCart()" class="text-sm text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              Kosongkan Keranjang
            </button>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="card p-6 sticky top-20">
            <h3 class="font-display font-bold text-xl text-gray-900 mb-5">Ringkasan Pesanan</h3>
            <div class="space-y-3 text-sm">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal ({{ cartStore.totalItems }} item)</span>
                <span class="font-medium">{{ formatPrice(cartStore.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Biaya Pengiriman</span>
                <span v-if="cartStore.shippingCost > 0" class="font-medium">{{ formatPrice(cartStore.shippingCost) }}</span>
                <span v-else class="text-accent-600 font-semibold">GRATIS</span>
              </div>
              <div v-if="cartStore.shippingCost > 0" class="text-xs text-gray-400 -mt-1">
                Tambah {{ formatPrice(200000 - cartStore.subtotal) }} lagi untuk gratis ongkir
              </div>
              <hr class="border-gray-100 my-1" />
              <div class="flex justify-between text-gray-900 font-bold text-lg">
                <span>Total</span>
                <span class="text-primary-600">{{ formatPrice(cartStore.total) }}</span>
              </div>
            </div>

            <!-- Promo code -->
            <div class="mt-5">
              <div class="flex gap-2">
                <input
                  v-model="promoCode"
                  type="text"
                  placeholder="Kode promo"
                  class="input-field text-sm py-2.5"
                />
                <button @click="applyPromo" class="shrink-0 px-4 py-2.5 border border-primary-500 text-primary-600 rounded-xl text-sm font-semibold hover:bg-primary-50 transition-colors">
                  Terapkan
                </button>
              </div>
              <p v-if="promoMsg" class="text-xs mt-1.5" :class="promoSuccess ? 'text-green-600' : 'text-red-500'">
                {{ promoMsg }}
              </p>
            </div>

            <RouterLink to="/checkout" class="btn-primary w-full text-center block mt-5">
              Lanjut ke Checkout →
            </RouterLink>
            <RouterLink to="/products" class="block text-center text-sm text-primary-600 hover:text-primary-700 mt-3 transition-colors">
              ← Lanjut Belanja
            </RouterLink>

            <!-- Payment methods -->
            <div class="mt-5 pt-4 border-t border-gray-100">
              <p class="text-xs text-gray-400 text-center mb-3">Metode Pembayaran</p>
              <div class="flex items-center justify-center gap-2 flex-wrap text-xs text-gray-500">
                <span class="bg-gray-100 px-2 py-1 rounded">Transfer Bank</span>
                <span class="bg-gray-100 px-2 py-1 rounded">GoPay</span>
                <span class="bg-gray-100 px-2 py-1 rounded">OVO</span>
                <span class="bg-gray-100 px-2 py-1 rounded">Dana</span>
                <span class="bg-gray-100 px-2 py-1 rounded">COD</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()
const promoCode = ref('')
const promoMsg = ref('')
const promoSuccess = ref(false)

function formatPrice(n) {
  return 'Rp' + n.toLocaleString('id-ID')
}

function applyPromo() {
  if (promoCode.value.toUpperCase() === 'ZEAPET10') {
    promoSuccess.value = true
    promoMsg.value = '✓ Kode promo berhasil — diskon 10% diterapkan!'
  } else {
    promoSuccess.value = false
    promoMsg.value = 'Kode promo tidak valid atau sudah kadaluarsa.'
  }
}
</script>
