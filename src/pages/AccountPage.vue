<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
      <!-- Header -->
      <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-full bg-primary-500 text-white flex items-center justify-center text-2xl font-bold shrink-0">
          {{ authStore.user?.name?.[0]?.toUpperCase() }}
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800">{{ authStore.user?.name }}</h1>
          <p class="text-sm text-gray-500">{{ authStore.user?.email }}</p>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex gap-1 bg-white rounded-2xl p-1 shadow-sm border border-gray-100 mb-6 w-fit">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          class="px-5 py-2 rounded-xl text-sm font-medium transition-colors"
          :class="activeTab === tab.id
            ? 'bg-primary-500 text-white shadow-sm'
            : 'text-gray-500 hover:text-gray-700'"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- ── TAB: PROFIL ─────────────────────────────────────────────── -->
      <div v-if="activeTab === 'profile'" class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
          <h2 class="text-lg font-bold text-gray-800">Edit Profil</h2>
          <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi akun kamu</p>
        </div>

        <form @submit.prevent="handleProfileUpdate" class="px-6 py-6 flex flex-col gap-5">
          <!-- Name + Email -->
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
              <input v-model="profileForm.name" type="text" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                :class="{'border-red-400': profileErrors.name}"
              />
              <p v-if="profileErrors.name" class="text-red-500 text-xs mt-1">{{ profileErrors.name[0] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
              <input v-model="profileForm.email" type="email" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                :class="{'border-red-400': profileErrors.email}"
              />
              <p v-if="profileErrors.email" class="text-red-500 text-xs mt-1">{{ profileErrors.email[0] }}</p>
            </div>
          </div>

          <!-- Phone + Address -->
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
              <input v-model="profileForm.phone" type="text" placeholder="0812-xxxx-xxxx"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
              <input v-model="profileForm.address" type="text" placeholder="Jl. ..."
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
              />
            </div>
          </div>

          <!-- Divider -->
          <div class="border-t border-gray-100 pt-4">
            <p class="text-sm font-semibold text-gray-700 mb-4">
              Ubah Password
              <span class="text-xs text-gray-400 font-normal ml-1">(kosongkan jika tidak ingin mengubah)</span>
            </p>
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                  <input v-model="profileForm.password" :type="showPass ? 'text' : 'password'"
                    placeholder="Min. 8 karakter"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                    :class="{'border-red-400': profileErrors.password}"
                  />
                  <button type="button" @click="showPass = !showPass"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg v-if="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                  </button>
                </div>
                <p v-if="profileErrors.password" class="text-red-500 text-xs mt-1">{{ profileErrors.password[0] }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input v-model="profileForm.password_confirmation" :type="showPass ? 'text' : 'password'"
                  placeholder="Ulangi password baru"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                />
              </div>
            </div>
          </div>

          <!-- Feedback -->
          <div v-if="profileSuccess" class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Profil berhasil diperbarui!
          </div>
          <div v-if="profileError" class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600">
            {{ profileError }}
          </div>

          <div class="flex justify-end">
            <button type="submit" :disabled="profileSaving"
              class="btn-primary px-8 py-2.5 text-sm disabled:opacity-50">
              <span v-if="profileSaving">Menyimpan...</span>
              <span v-else>Simpan Perubahan</span>
            </button>
          </div>
        </form>
      </div>

      <!-- ── TAB: RIWAYAT PESANAN ──────────────────────────────────────── -->
      <div v-if="activeTab === 'orders'">
        <!-- Loading -->
        <div v-if="ordersLoading" class="text-center py-12 text-gray-400">
          <svg class="animate-spin w-6 h-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          Memuat riwayat pesanan...
        </div>

        <!-- Empty -->
        <div v-else-if="!orders.length" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <p class="text-gray-500 font-medium">Belum ada pesanan</p>
          <p class="text-sm text-gray-400 mt-1 mb-4">Yuk, mulai belanja di Zea Pet Shop!</p>
          <RouterLink to="/products" class="btn-primary text-sm px-6 py-2">Lihat Produk</RouterLink>
        </div>

        <!-- List -->
        <div v-else class="flex flex-col gap-3">
          <div
            v-for="order in orders"
            :key="order.id"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
          >
            <!-- Order header -->
            <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-gray-50">
              <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                <span class="font-mono text-sm font-semibold text-gray-800">{{ order.order_number }}</span>
                <span class="text-xs text-gray-400">{{ formatDate(order.created_at) }}</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-800">{{ formatCurrency(order.total) }}</span>
                <span :class="statusClass(order.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold">
                  {{ statusLabel(order.status) }}
                </span>
              </div>
            </div>

            <!-- Items preview -->
            <div class="px-5 py-3">
              <div class="flex flex-wrap gap-3 mb-3">
                <div
                  v-for="item in order.items.slice(0, 4)"
                  :key="item.id"
                  class="flex items-center gap-2"
                >
                  <img
                    :src="item.product_image || '/placeholder.png'"
                    :alt="item.product_name"
                    class="w-10 h-10 rounded-lg object-cover bg-gray-100"
                  />
                  <div class="text-xs text-gray-600 max-w-[120px]">
                    <p class="truncate font-medium">{{ item.product_name }}</p>
                    <p class="text-gray-400">{{ item.qty }}x {{ formatCurrency(item.price) }}</p>
                  </div>
                </div>
                <div v-if="order.items.length > 4" class="flex items-center text-xs text-gray-400 font-medium">
                  +{{ order.items.length - 4 }} lainnya
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="text-xs text-gray-400">
                  Pengiriman: <span class="capitalize">{{ order.shipping_method }}</span>
                  &nbsp;·&nbsp;
                  Pembayaran: <span class="capitalize">{{ order.payment_method }}</span>
                </div>
                <button @click="openDetail(order)"
                  class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline underline-offset-2 transition-colors">
                  Lihat Detail →
                </button>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="ordersMeta.last_page > 1" class="flex items-center justify-between text-sm text-gray-500 mt-2">
            <span>Halaman {{ ordersMeta.current_page }} dari {{ ordersMeta.last_page }}</span>
            <div class="flex gap-2">
              <button @click="loadOrders(ordersMeta.current_page - 1)" :disabled="ordersMeta.current_page === 1"
                class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 text-xs">Sebelumnya</button>
              <button @click="loadOrders(ordersMeta.current_page + 1)" :disabled="ordersMeta.current_page === ordersMeta.last_page"
                class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40 text-xs">Berikutnya</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Order Detail Modal ─────────────────────────────────────────── -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="detailOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="detailOrder = null"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
            <!-- Header -->
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between z-10 rounded-t-2xl">
              <div>
                <h2 class="font-bold text-gray-800">Detail Pesanan</h2>
                <p class="text-xs text-gray-400 font-mono">{{ detailOrder.order_number }}</p>
              </div>
              <div class="flex items-center gap-3">
                <span :class="statusClass(detailOrder.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold">
                  {{ statusLabel(detailOrder.status) }}
                </span>
                <button @click="detailOrder = null" class="p-1 rounded-lg hover:bg-gray-100">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="px-6 py-4 flex flex-col gap-5">
              <!-- Items -->
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Produk Dipesan</p>
                <div class="flex flex-col gap-2">
                  <div v-for="item in detailOrder.items" :key="item.id"
                    class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                    <img :src="item.product_image || '/placeholder.png'" :alt="item.product_name"
                      class="w-12 h-12 rounded-lg object-cover bg-gray-100 shrink-0"/>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-800 truncate">{{ item.product_name }}</p>
                      <p class="text-xs text-gray-400">{{ item.qty }} × {{ formatCurrency(item.price) }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 shrink-0">{{ formatCurrency(item.total) }}</p>
                  </div>
                </div>
              </div>

              <!-- Totals -->
              <div class="bg-gray-50 rounded-xl px-4 py-3 flex flex-col gap-1.5 text-sm">
                <div class="flex justify-between text-gray-600">
                  <span>Subtotal</span>
                  <span>{{ formatCurrency(detailOrder.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                  <span>Ongkir ({{ detailOrder.shipping_method }})</span>
                  <span>{{ formatCurrency(detailOrder.shipping_cost) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 border-t border-gray-200 pt-1.5 mt-0.5">
                  <span>Total</span>
                  <span>{{ formatCurrency(detailOrder.total) }}</span>
                </div>
              </div>

              <!-- Shipping info -->
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Info Pengiriman</p>
                <div class="grid grid-cols-2 gap-2 text-sm">
                  <div><p class="text-xs text-gray-400">Penerima</p><p class="font-medium text-gray-800">{{ detailOrder.first_name }} {{ detailOrder.last_name }}</p></div>
                  <div><p class="text-xs text-gray-400">No. HP</p><p class="font-medium text-gray-800">{{ detailOrder.phone }}</p></div>
                  <div class="col-span-2"><p class="text-xs text-gray-400">Alamat</p><p class="font-medium text-gray-800">{{ detailOrder.address }}, {{ detailOrder.city }}, {{ detailOrder.province }} {{ detailOrder.postal_code }}</p></div>
                  <div><p class="text-xs text-gray-400">Metode Bayar</p><p class="font-medium text-gray-800 capitalize">{{ detailOrder.payment_method }}</p></div>
                  <div><p class="text-xs text-gray-400">Tanggal Pesan</p><p class="font-medium text-gray-800">{{ formatDate(detailOrder.created_at) }}</p></div>
                </div>
              </div>

              <!-- Notes -->
              <div v-if="detailOrder.notes">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Catatan</p>
                <p class="text-sm text-gray-600">{{ detailOrder.notes }}</p>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { updateProfile, fetchMyOrders } from '@/services/api'

const authStore = useAuthStore()

// ── Tabs ──────────────────────────────────────────────────────────────────

const tabs = [
  { id: 'profile', label: 'Profil Saya' },
  { id: 'orders',  label: 'Riwayat Pesanan' },
]
const activeTab = ref('profile')

// ── Profile Form ──────────────────────────────────────────────────────────

const profileForm = reactive({
  name: authStore.user?.name ?? '',
  email: authStore.user?.email ?? '',
  phone: authStore.user?.phone ?? '',
  address: authStore.user?.address ?? '',
  password: '',
  password_confirmation: '',
})

const profileErrors  = ref({})
const profileError   = ref('')
const profileSuccess = ref(false)
const profileSaving  = ref(false)
const showPass       = ref(false)

async function handleProfileUpdate() {
  profileSaving.value  = true
  profileErrors.value  = {}
  profileError.value   = ''
  profileSuccess.value = false
  try {
    const res = await updateProfile(profileForm)
    authStore.updateUser(res.data)
    profileSuccess.value = true
    profileForm.password = ''
    profileForm.password_confirmation = ''
    setTimeout(() => { profileSuccess.value = false }, 3000)
  } catch (err) {
    const data = err?.response?.data
    if (data?.errors) profileErrors.value = data.errors
    else profileError.value = data?.message ?? 'Gagal menyimpan perubahan.'
  } finally {
    profileSaving.value = false
  }
}

// ── Orders ────────────────────────────────────────────────────────────────

const orders      = ref([])
const ordersLoading = ref(true)
const ordersMeta  = ref({ current_page: 1, last_page: 1 })
const detailOrder = ref(null)

async function loadOrders(page = 1) {
  ordersLoading.value = true
  try {
    const res = await fetchMyOrders({ page })
    orders.value    = res.data.data
    ordersMeta.value = {
      current_page: res.data.current_page,
      last_page:    res.data.last_page,
    }
  } catch (e) {
    console.error(e)
  } finally {
    ordersLoading.value = false
  }
}

function openDetail(order) {
  detailOrder.value = order
}

// ── Helpers ───────────────────────────────────────────────────────────────

function formatCurrency(val) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val)
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
}

const STATUS_MAP = {
  pending:    { label: 'Menunggu',    cls: 'bg-yellow-100 text-yellow-700' },
  processing: { label: 'Diproses',   cls: 'bg-blue-100 text-blue-700' },
  shipped:    { label: 'Dikirim',    cls: 'bg-indigo-100 text-indigo-700' },
  delivered:  { label: 'Terkirim',   cls: 'bg-green-100 text-green-700' },
  cancelled:  { label: 'Dibatalkan', cls: 'bg-red-100 text-red-600' },
}

function statusLabel(s) { return STATUS_MAP[s]?.label ?? s }
function statusClass(s) { return STATUS_MAP[s]?.cls ?? 'bg-gray-100 text-gray-600' }

// ── Init ──────────────────────────────────────────────────────────────────

onMounted(() => loadOrders())
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
