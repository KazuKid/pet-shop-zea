<template>
  <div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-500 via-primary-400 to-amber-300 overflow-hidden">
      <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.3) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(255,255,255,0.3) 0%, transparent 50%);"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="flex flex-col lg:flex-row items-center gap-10">
          <div class="flex-1 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm text-white rounded-full px-4 py-2 text-sm font-medium mb-6">
              <span>🐾</span> Toko Hewan Peliharaan Terpercaya
            </div>
            <h1 class="text-4xl lg:text-6xl font-display font-bold text-white leading-tight mb-4">
              Semua yang <span class="text-yellow-200">Dibutuhkan</span><br/>Hewan Peliharaanmu
            </h1>
            <p class="text-white/90 text-lg mb-8 max-w-lg mx-auto lg:mx-0">
              Produk premium berkualitas tinggi untuk anjing, kucing, burung, ikan, kelinci, dan hewan peliharaan lainnya dengan harga terjangkau.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
              <RouterLink to="/products" class="btn-primary bg-white text-primary-600 hover:bg-yellow-50 shadow-lg text-center">
                Belanja Sekarang
              </RouterLink>
              <RouterLink to="/about" class="btn-secondary border-white text-white hover:bg-white/20 hover:text-white text-center">
                Pelajari Lebih
              </RouterLink>
            </div>
            <div class="flex items-center gap-6 mt-8 justify-center lg:justify-start">
              <div class="text-center">
                <div class="text-2xl font-bold text-white">500+</div>
                <div class="text-white/70 text-xs">Produk</div>
              </div>
              <div class="w-px h-8 bg-white/30"></div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">10K+</div>
                <div class="text-white/70 text-xs">Pelanggan</div>
              </div>
              <div class="w-px h-8 bg-white/30"></div>
              <div class="text-center">
                <div class="text-2xl font-bold text-white">4.9★</div>
                <div class="text-white/70 text-xs">Rating</div>
              </div>
            </div>
          </div>
          <!-- Hero illustration -->
          <div class="flex-1 flex justify-center">
            <div class="relative w-72 h-72 lg:w-96 lg:h-96">
              <div class="w-full h-full rounded-3xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-2xl">
                <div class="text-center">
                  <div class="text-8xl lg:text-9xl">🐾</div>
                  <div class="mt-4 grid grid-cols-3 gap-3">
                    <div class="bg-white/30 rounded-xl p-2 text-3xl">🐶</div>
                    <div class="bg-white/30 rounded-xl p-2 text-3xl">🐱</div>
                    <div class="bg-white/30 rounded-xl p-2 text-3xl">🐦</div>
                    <div class="bg-white/30 rounded-xl p-2 text-3xl">🐟</div>
                    <div class="bg-white/30 rounded-xl p-2 text-3xl">🐰</div>
                    <div class="bg-white/30 rounded-xl p-2 text-3xl">🦜</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Bar -->
    <section class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="f in features" :key="f.title" class="flex items-center gap-3 py-2">
            <span class="text-2xl">{{ f.icon }}</span>
            <div>
              <div class="text-sm font-semibold text-gray-800">{{ f.title }}</div>
              <div class="text-xs text-gray-500">{{ f.sub }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories -->
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Kategori Hewan</h2>
        <p class="text-gray-500">Temukan semua yang dibutuhkan hewan peliharaanmu</p>
      </div>
      <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
        <RouterLink
          v-for="cat in categories"
          :key="cat.id"
          to="/products"
          @click="setCategory(cat.id)"
          class="card p-4 flex flex-col items-center gap-2 hover:border-primary-300 border-2 border-transparent cursor-pointer transition-all hover:-translate-y-1"
        >
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl" :class="cat.bg">
            {{ cat.icon }}
          </div>
          <span class="text-sm font-semibold text-gray-700">{{ cat.name }}</span>
          <span class="text-xs text-gray-400">{{ cat.count }} produk</span>
        </RouterLink>
      </div>
    </section>

    <!-- Promo Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
      <div class="bg-gradient-to-r from-primary-500 to-primary-400 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
        <div class="absolute right-0 top-0 w-64 h-full opacity-10 text-9xl flex items-center justify-end pr-8">🛒</div>
        <div class="relative z-10">
          <div class="badge bg-yellow-300 text-yellow-900 mb-3">PROMO SPESIAL</div>
          <h3 class="text-white font-display font-bold text-2xl md:text-3xl mb-2">Diskon hingga 30%</h3>
          <p class="text-white/80 text-sm md:text-base">Belanja produk pilihan dengan harga terbaik. Gratis ongkir pembelian min. Rp200.000</p>
        </div>
        <RouterLink
          to="/products"
          class="relative z-10 btn-primary bg-white text-primary-600 hover:bg-yellow-50 whitespace-nowrap shrink-0"
        >
          Lihat Promo
        </RouterLink>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="py-4 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">Produk Unggulan</h2>
          <p class="text-gray-500 text-sm">Rating terbaik pilihan pelanggan</p>
        </div>
        <RouterLink to="/products" class="text-primary-600 hover:text-primary-700 font-semibold text-sm flex items-center gap-1">
          Lihat Semua
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </RouterLink>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
        <ProductCard v-for="p in productStore.featured" :key="p.id" :product="p" />
      </div>
    </section>

    <!-- New Products -->
    <section class="py-4 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-3xl font-display font-bold text-gray-900 mb-1">Produk Terbaru</h2>
          <p class="text-gray-500 text-sm">Baru tersedia di Zea Pet Shop</p>
        </div>
        <RouterLink to="/products" class="text-primary-600 hover:text-primary-700 font-semibold text-sm flex items-center gap-1">
          Lihat Semua
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </RouterLink>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
        <ProductCard v-for="p in productStore.newProducts" :key="p.id" :product="p" />
      </div>
    </section>

    <!-- Testimonials -->
    <section class="bg-gray-100 py-14">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
          <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Kata Pelanggan Kami</h2>
          <p class="text-gray-500">Ribuan pelanggan puas berbelanja di Zea Pet Shop</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="t in testimonials" :key="t.id" class="card p-6">
            <div class="flex items-center gap-3 mb-4">
              <img :src="t.avatar" :alt="t.name" class="w-12 h-12 rounded-full object-cover" />
              <div>
                <div class="font-semibold text-gray-800 text-sm">{{ t.name }}</div>
                <div class="text-xs text-gray-500">{{ t.pet }}</div>
              </div>
            </div>
            <div class="flex mb-3">
              <span v-for="s in 5" :key="s" class="text-yellow-400 text-base">★</span>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">{{ t.comment }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-10 text-center">
        <span class="text-4xl mb-4 block">📧</span>
        <h2 class="text-2xl font-display font-bold text-white mb-2">Dapatkan Info Promo Terbaru</h2>
        <p class="text-gray-400 mb-6 text-sm">Daftarkan email kamu dan dapatkan notifikasi promo eksklusif setiap minggu</p>
        <form @submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
          <input
            v-model="email"
            type="email"
            placeholder="Masukkan email kamu..."
            class="flex-1 input-field"
            required
          />
          <button type="submit" class="btn-primary shrink-0">Daftar</button>
        </form>
        <p v-if="subscribed" class="text-accent-400 text-sm mt-3">✓ Berhasil mendaftar! Cek email kamu.</p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useProductStore } from '@/stores/products'
import { useRouter } from 'vue-router'
import ProductCard from '@/components/ProductCard.vue'
import { testimonials } from '@/data/products'

const productStore = useProductStore()
const router = useRouter()
const email = ref('')
const subscribed = ref(false)

const features = [
  { icon: '🚚', title: 'Gratis Ongkir', sub: 'Min. pembelian Rp200rb' },
  { icon: '✅', title: 'Produk Original', sub: '100% terjamin kualitas' },
  { icon: '↩️', title: 'Mudah Return', sub: 'Retur dalam 7 hari' },
  { icon: '🔒', title: 'Pembayaran Aman', sub: 'Berbagai metode tersedia' },
]

const categories = [
  { id: 'dog',    name: 'Anjing',  icon: '🐶', bg: 'bg-amber-100',   count: 4 },
  { id: 'cat',    name: 'Kucing',  icon: '🐱', bg: 'bg-purple-100',  count: 4 },
  { id: 'bird',   name: 'Burung',  icon: '🐦', bg: 'bg-sky-100',     count: 2 },
  { id: 'fish',   name: 'Ikan',    icon: '🐟', bg: 'bg-cyan-100',    count: 2 },
  { id: 'rabbit', name: 'Kelinci', icon: '🐰', bg: 'bg-rose-100',    count: 2 },
]

function setCategory(id) {
  productStore.selectedCategory = id
}

function subscribe() {
  subscribed.value = true
  email.value = ''
  setTimeout(() => { subscribed.value = false }, 4000)
}
</script>
