<template>
  <div class="min-h-screen">
    <!-- Header -->
    <div class="bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="text-sm text-gray-500 mb-2">
          <RouterLink to="/" class="hover:text-primary-600">Beranda</RouterLink>
          <span class="mx-2">/</span>
          <RouterLink to="/cart" class="hover:text-primary-600">Keranjang</RouterLink>
          <span class="mx-2">/</span>
          <span class="text-gray-800 font-medium">Checkout</span>
        </nav>
        <h1 class="text-3xl font-display font-bold text-gray-900">Checkout</h1>
      </div>
    </div>

    <!-- Order placed success -->
    <div v-if="orderPlaced" class="max-w-md mx-auto px-4 py-20 text-center">
      <div class="text-7xl mb-5">🎉</div>
      <h2 class="text-3xl font-display font-bold text-gray-900 mb-3">Pesanan Berhasil!</h2>
      <p class="text-gray-500 mb-2">No. Pesanan: <span class="font-bold text-primary-600">#{{ orderId }}</span></p>
      <p class="text-gray-500 text-sm mb-8">Terima kasih sudah berbelanja di Zea Pet Shop! Pesanan kamu sedang kami proses dan akan segera dikirimkan.</p>
      <RouterLink to="/" class="btn-primary">Kembali ke Beranda</RouterLink>
    </div>

    <!-- Empty cart redirect hint -->
    <div v-else-if="cartStore.items.length === 0" class="max-w-md mx-auto px-4 py-20 text-center">
      <div class="text-6xl mb-4">🛒</div>
      <h2 class="text-2xl font-display font-bold text-gray-700 mb-4">Keranjangmu Kosong</h2>
      <RouterLink to="/products" class="btn-primary">Mulai Belanja</RouterLink>
    </div>

    <!-- Checkout form -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Step indicator -->
          <div class="flex items-center gap-2 mb-6">
            <div v-for="(step, i) in steps" :key="i" class="flex items-center gap-2">
              <div
                class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                :class="i <= currentStep ? 'bg-primary-500 text-white' : 'bg-gray-200 text-gray-500'"
              >
                {{ i < currentStep ? '✓' : i + 1 }}
              </div>
              <span class="text-xs font-medium hidden sm:inline" :class="i <= currentStep ? 'text-primary-600' : 'text-gray-400'">{{ step }}</span>
              <div v-if="i < steps.length - 1" class="w-8 h-px" :class="i < currentStep ? 'bg-primary-400' : 'bg-gray-200'"></div>
            </div>
          </div>

          <!-- Step 1: Shipping info -->
          <div v-show="currentStep === 0" class="card p-6">
            <h3 class="font-display font-bold text-xl text-gray-900 mb-5 flex items-center gap-2">
              <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center text-sm font-bold">1</span>
              Informasi Pengiriman
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Depan *</label>
                <input v-model="form.firstName" type="text" class="input-field" placeholder="John" required />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Belakang *</label>
                <input v-model="form.lastName" type="text" class="input-field" placeholder="Doe" required />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                <input v-model="form.email" type="email" class="input-field" placeholder="email@contoh.com" required />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor HP / WA *</label>
                <input v-model="form.phone" type="tel" class="input-field" placeholder="0812-3456-7890" required />
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap *</label>
                <textarea v-model="form.address" class="input-field resize-none" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW..." required></textarea>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kota *</label>
                <input v-model="form.city" type="text" class="input-field" placeholder="Bandung" required />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Provinsi *</label>
                <select v-model="form.province" class="input-field">
                  <option value="">Pilih Provinsi</option>
                  <option v-for="prov in provinces" :key="prov" :value="prov">{{ prov }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Pos *</label>
                <input v-model="form.zip" type="text" class="input-field" placeholder="40123" maxlength="5" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kurir</label>
                <select v-model="form.courier" class="input-field">
                  <option value="jne">JNE Regular (2-3 hari)</option>
                  <option value="jnt">J&T Express (1-2 hari)</option>
                  <option value="sicepat">SiCepat (1 hari)</option>
                  <option value="gosend">GoSend (Same Day)</option>
                </select>
              </div>
            </div>
            <button @click="nextStep" class="btn-primary mt-6">Lanjut ke Pembayaran →</button>
          </div>

          <!-- Step 2: Payment -->
          <div v-show="currentStep === 1" class="card p-6">
            <h3 class="font-display font-bold text-xl text-gray-900 mb-5 flex items-center gap-2">
              <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center text-sm font-bold">2</span>
              Metode Pembayaran
            </h3>
            <div class="space-y-3">
              <label
                v-for="method in paymentMethods"
                :key="method.id"
                class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all"
                :class="form.payment === method.id ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
              >
                <input type="radio" name="payment" :value="method.id" v-model="form.payment" class="accent-primary-500" />
                <span class="text-xl">{{ method.icon }}</span>
                <div>
                  <div class="font-semibold text-gray-800 text-sm">{{ method.name }}</div>
                  <div class="text-xs text-gray-500">{{ method.desc }}</div>
                </div>
              </label>
            </div>
            <div class="flex gap-3 mt-6">
              <button @click="currentStep = 0" class="btn-secondary flex-1 text-center">← Kembali</button>
              <button @click="nextStep" class="btn-primary flex-1">Lanjut ke Konfirmasi →</button>
            </div>
          </div>

          <!-- Step 3: Confirmation -->
          <div v-show="currentStep === 2" class="card p-6">
            <h3 class="font-display font-bold text-xl text-gray-900 mb-5 flex items-center gap-2">
              <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center text-sm font-bold">3</span>
              Konfirmasi Pesanan
            </h3>
            <div class="space-y-4 text-sm">
              <div class="p-4 bg-gray-50 rounded-xl space-y-2">
                <p class="font-semibold text-gray-700 mb-2">Data Pengiriman</p>
                <p>Nama: {{ form.firstName }} {{ form.lastName }}</p>
                <p>Email: {{ form.email }}</p>
                <p>HP: {{ form.phone }}</p>
                <p>Alamat: {{ form.address }}, {{ form.city }}, {{ form.province }} {{ form.zip }}</p>
                <p>Kurir: {{ form.courier.toUpperCase() }}</p>
              </div>
              <div class="p-4 bg-gray-50 rounded-xl">
                <p class="font-semibold text-gray-700 mb-2">Metode Pembayaran</p>
                <p>{{ paymentMethods.find(m => m.id === form.payment)?.name }}</p>
              </div>
            </div>
            <div class="flex gap-3 mt-6">
              <button @click="currentStep = 1" class="btn-secondary flex-1 text-center">← Kembali</button>
              <button @click="placeOrder" :disabled="submitting" class="btn-accent flex-1">
                {{ submitting ? 'Memproses...' : '🎉 Buat Pesanan' }}
              </button>
            </div>
            <p v-if="submitError" class="text-red-500 text-sm mt-3 text-center">{{ submitError }}</p>
          </div>
        </div>

        <!-- Order summary (sticky) -->
        <div class="lg:col-span-1">
          <div class="card p-6 sticky top-20">
            <h3 class="font-display font-bold text-xl text-gray-900 mb-5">Ringkasan</h3>
            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
              <div v-for="item in cartStore.items" :key="item.id" class="flex gap-3">
                <img :src="item.image" :alt="item.name" class="w-12 h-12 rounded-lg object-cover bg-gray-100 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="text-xs text-gray-700 font-medium line-clamp-2">{{ item.name }}</p>
                  <p class="text-xs text-gray-500 mt-0.5">{{ item.qty }}x {{ formatPrice(item.price) }}</p>
                </div>
                <p class="text-xs font-bold text-gray-800 shrink-0">{{ formatPrice(item.price * item.qty) }}</p>
              </div>
            </div>
            <hr class="border-gray-100 mb-3" />
            <div class="space-y-2 text-sm">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>{{ formatPrice(cartStore.subtotal) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Ongkir</span>
                <span v-if="cartStore.shippingCost > 0">{{ formatPrice(cartStore.shippingCost) }}</span>
                <span v-else class="text-accent-600 font-semibold">GRATIS</span>
              </div>
              <hr class="border-gray-100" />
              <div class="flex justify-between font-bold text-base text-gray-900">
                <span>Total</span>
                <span class="text-primary-600">{{ formatPrice(cartStore.total) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()
const currentStep = ref(0)
const orderPlaced = ref(false)
const orderId = ref('')

const steps = ['Pengiriman', 'Pembayaran', 'Konfirmasi']

const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  province: '',
  zip: '',
  courier: 'jne',
  payment: 'transfer',
})

const provinces = [
  'Aceh', 'Bali', 'Banten', 'Bengkulu', 'DI Yogyakarta', 'DKI Jakarta',
  'Gorontalo', 'Jambi', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur',
  'Kalimantan Barat', 'Kalimantan Selatan', 'Kalimantan Tengah', 'Kalimantan Timur',
  'Kalimantan Utara', 'Kepulauan Bangka Belitung', 'Kepulauan Riau',
  'Lampung', 'Maluku', 'Maluku Utara', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
  'Papua', 'Papua Barat', 'Riau', 'Sulawesi Barat', 'Sulawesi Selatan',
  'Sulawesi Tengah', 'Sulawesi Tenggara', 'Sulawesi Utara', 'Sumatera Barat',
  'Sumatera Selatan', 'Sumatera Utara',
]

const paymentMethods = [
  { id: 'transfer', icon: '🏦', name: 'Transfer Bank', desc: 'BCA, BNI, BRI, Mandiri' },
  { id: 'gopay',    icon: '💚', name: 'GoPay',         desc: 'Bayar lewat aplikasi Gojek' },
  { id: 'ovo',      icon: '💜', name: 'OVO',           desc: 'Bayar lewat aplikasi OVO' },
  { id: 'dana',     icon: '💙', name: 'DANA',          desc: 'Bayar lewat aplikasi DANA' },
  { id: 'cod',      icon: '💵', name: 'COD',           desc: 'Bayar di tempat saat barang tiba' },
]

function formatPrice(n) {
  return 'Rp' + n.toLocaleString('id-ID')
}

function nextStep() {
  if (currentStep.value < 2) currentStep.value++
}

const submitting = ref(false)
const submitError = ref('')

async function placeOrder() {
  submitting.value = true
  submitError.value = ''
  try {
    const result = await cartStore.placeOrder({
      first_name      : form.firstName,
      last_name       : form.lastName,
      email           : form.email,
      phone           : form.phone,
      address         : form.address,
      city            : form.city,
      province        : form.province,
      postal_code     : form.zip,
      shipping_method : form.courier,
      payment_method  : form.payment,
    })
    orderId.value    = result.order_number
    orderPlaced.value = true
  } catch (err) {
    const msg = err?.response?.data?.message ?? 'Gagal membuat pesanan. Coba lagi.'
    submitError.value = msg
  } finally {
    submitting.value = false
  }
}
</script>
