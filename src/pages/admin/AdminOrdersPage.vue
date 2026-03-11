<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Kelola Pesanan</h1>
      <div class="flex items-center gap-2 text-sm text-gray-500">
        Total: <strong class="text-gray-800">{{ meta.total ?? 0 }}</strong> pesanan
      </div>
    </div>

    <!-- Filter tabs -->
    <div class="flex flex-wrap gap-2 mb-4">
      <button
        v-for="tab in statusTabs"
        :key="tab.value"
        @click="filterStatus = tab.value; loadOrders(1)"
        :class="[
          'px-4 py-1.5 rounded-xl text-sm font-medium transition-colors',
          filterStatus === tab.value
            ? 'bg-primary-500 text-white'
            : 'bg-white border border-gray-200 text-gray-600 hover:border-primary-300',
        ]"
      >
        {{ tab.label }}
        <span v-if="tab.value === '' || stats[tab.value]" class="ml-1 text-xs opacity-75">
          {{ tab.value === '' ? '' : `(${stats[tab.value] ?? 0})` }}
        </span>
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-gray-400">
        <svg class="animate-spin w-6 h-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        Memuat data...
      </div>

      <div v-else-if="!orders.length" class="p-8 text-center text-gray-400">
        Tidak ada pesanan ditemukan.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-left text-xs text-gray-500 font-medium uppercase tracking-wide">
              <th class="px-4 py-3">No. Pesanan</th>
              <th class="px-4 py-3">Pelanggan</th>
              <th class="px-4 py-3">Pengiriman</th>
              <th class="px-4 py-3 text-right">Total</th>
              <th class="px-4 py-3 text-center">Status</th>
              <th class="px-4 py-3">Tanggal</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3">
                <span class="font-mono text-xs text-gray-700">{{ order.order_number }}</span>
              </td>
              <td class="px-4 py-3">
                <p class="font-medium text-gray-800">{{ order.first_name }} {{ order.last_name }}</p>
                <p class="text-xs text-gray-400">{{ order.email }}</p>
              </td>
              <td class="px-4 py-3 text-gray-600 uppercase text-xs">{{ order.shipping_method }}</td>
              <td class="px-4 py-3 text-right font-semibold text-gray-800">{{ formatRupiah(order.total) }}</td>
              <td class="px-4 py-3 text-center">
                <span :class="statusBadge(order.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold">
                  {{ statusLabel(order.status) }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ formatDate(order.created_at) }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-1">
                  <button
                    @click="openDetail(order)"
                    class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                    title="Detail"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                  <button
                    @click="openStatusModal(order)"
                    class="p-1.5 rounded-lg text-primary-600 hover:bg-primary-50 transition-colors"
                    title="Update Status"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
        <span>Halaman {{ meta.current_page }} dari {{ meta.last_page }}</span>
        <div class="flex gap-2">
          <button @click="changePage(meta.current_page - 1)" :disabled="meta.current_page === 1"
            class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40">Sebelumnya</button>
          <button @click="changePage(meta.current_page + 1)" :disabled="meta.current_page === meta.last_page"
            class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40">Berikutnya</button>
        </div>
      </div>
    </div>

    <!-- Detail Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="detailOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="detailOrder = null"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 class="text-lg font-bold text-gray-800">Detail Pesanan</h2>
              <button @click="detailOrder = null" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div class="px-6 py-4 space-y-4">
              <!-- Order info -->
              <div class="flex items-center justify-between">
                <span class="font-mono text-sm text-gray-700">{{ detailOrder.order_number }}</span>
                <span :class="statusBadge(detailOrder.status)" class="px-2.5 py-1 rounded-full text-xs font-semibold">
                  {{ statusLabel(detailOrder.status) }}
                </span>
              </div>

              <!-- Customer -->
              <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-1">
                <p class="font-semibold text-gray-800 mb-2">Informasi Pelanggan</p>
                <p class="text-gray-600"><span class="text-gray-400 w-24 inline-block">Nama</span>{{ detailOrder.first_name }} {{ detailOrder.last_name }}</p>
                <p class="text-gray-600"><span class="text-gray-400 w-24 inline-block">Email</span>{{ detailOrder.email }}</p>
                <p class="text-gray-600"><span class="text-gray-400 w-24 inline-block">HP</span>{{ detailOrder.phone }}</p>
                <p class="text-gray-600"><span class="text-gray-400 w-24 inline-block">Alamat</span>{{ detailOrder.address }}, {{ detailOrder.city }}, {{ detailOrder.province }} {{ detailOrder.postal_code }}</p>
              </div>

              <!-- Items -->
              <div>
                <p class="font-semibold text-gray-800 mb-2 text-sm">Produk Dipesan</p>
                <div class="space-y-2">
                  <div v-for="item in detailOrder.items" :key="item.id" class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                    <img :src="item.product_image" class="w-10 h-10 rounded-lg object-cover bg-white" />
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-800 truncate">{{ item.product_name }}</p>
                      <p class="text-xs text-gray-400">{{ formatRupiah(item.price) }} × {{ item.qty }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 shrink-0">{{ formatRupiah(item.total) }}</p>
                  </div>
                </div>
              </div>

              <!-- Totals -->
              <div class="border-t border-gray-100 pt-3 text-sm space-y-1">
                <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>{{ formatRupiah(detailOrder.subtotal) }}</span></div>
                <div class="flex justify-between text-gray-600"><span>Ongkir ({{ detailOrder.shipping_method?.toUpperCase() }})</span><span>{{ formatRupiah(detailOrder.shipping_cost) }}</span></div>
                <div class="flex justify-between font-bold text-gray-800 text-base pt-1"><span>Total</span><span>{{ formatRupiah(detailOrder.total) }}</span></div>
              </div>

              <div v-if="detailOrder.notes" class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-800">
                <span class="font-medium">Catatan:</span> {{ detailOrder.notes }}
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Update Status Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="statusTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="statusTarget = null"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Update Status Pesanan</h3>
            <p class="text-sm text-gray-500 mb-4 font-mono">{{ statusTarget.order_number }}</p>

            <div class="space-y-2 mb-6">
              <button
                v-for="s in statusOptions"
                :key="s.value"
                @click="newStatus = s.value"
                :class="[
                  'w-full flex items-center gap-3 px-4 py-3 rounded-xl border-2 transition-colors text-left text-sm',
                  newStatus === s.value
                    ? 'border-primary-400 bg-primary-50 text-primary-700'
                    : 'border-gray-100 hover:border-gray-200 text-gray-700',
                ]"
              >
                <span :class="s.dot" class="w-3 h-3 rounded-full shrink-0"></span>
                {{ s.label }}
                <span v-if="statusTarget.status === s.value" class="ml-auto text-xs text-gray-400">(saat ini)</span>
              </button>
            </div>

            <div class="flex gap-3">
              <button @click="statusTarget = null" class="flex-1 btn-secondary py-2.5 text-sm">Batal</button>
              <button
                @click="handleUpdateStatus"
                :disabled="submitting || newStatus === statusTarget.status"
                class="flex-1 btn-primary py-2.5 text-sm disabled:opacity-50"
              >
                <span v-if="submitting">Menyimpan...</span>
                <span v-else>Simpan</span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchAdminOrders, adminUpdateOrderStatus } from '@/services/api'

// ── State ─────────────────────────────────────────────────────────────────

const orders       = ref([])
const loading      = ref(true)
const filterStatus = ref('')
const meta         = ref({ current_page: 1, last_page: 1, total: 0 })
const stats        = ref({})
let   currentPage  = 1

const detailOrder  = ref(null)
const statusTarget = ref(null)
const newStatus    = ref('')
const submitting   = ref(false)

const statusTabs = [
  { value: '',           label: 'Semua' },
  { value: 'pending',    label: 'Menunggu' },
  { value: 'processing', label: 'Diproses' },
  { value: 'shipped',    label: 'Dikirim' },
  { value: 'delivered',  label: 'Terkirim' },
  { value: 'cancelled',  label: 'Dibatalkan' },
]

const statusOptions = [
  { value: 'pending',    label: 'Menunggu Pembayaran', dot: 'bg-yellow-400' },
  { value: 'processing', label: 'Sedang Diproses',     dot: 'bg-blue-400'   },
  { value: 'shipped',    label: 'Sedang Dikirim',      dot: 'bg-indigo-400' },
  { value: 'delivered',  label: 'Terkirim / Selesai',  dot: 'bg-green-400'  },
  { value: 'cancelled',  label: 'Dibatalkan',          dot: 'bg-red-400'    },
]

// ── Load ──────────────────────────────────────────────────────────────────

async function loadOrders(page = 1) {
  loading.value = true
  currentPage   = page
  try {
    const params = { page, per_page: 15 }
    if (filterStatus.value) params.status = filterStatus.value
    const res    = await fetchAdminOrders(params)
    orders.value = res.data.data
    meta.value   = {
      current_page: res.data.current_page,
      last_page:    res.data.last_page,
      total:        res.data.total,
    }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

function changePage(page) {
  if (page < 1 || page > meta.value.last_page) return
  loadOrders(page)
}

// ── Helpers ───────────────────────────────────────────────────────────────

function formatRupiah(val) {
  return 'Rp ' + Number(val ?? 0).toLocaleString('id-ID')
}

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}

function statusLabel(s) {
  return statusOptions.find(o => o.value === s)?.label ?? s
}

function statusBadge(s) {
  const map = {
    pending:    'bg-yellow-100 text-yellow-700',
    processing: 'bg-blue-100 text-blue-700',
    shipped:    'bg-indigo-100 text-indigo-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-600'
}

// ── Actions ───────────────────────────────────────────────────────────────

function openDetail(order) {
  detailOrder.value = order
}

function openStatusModal(order) {
  statusTarget.value = order
  newStatus.value    = order.status
}

async function handleUpdateStatus() {
  submitting.value = true
  try {
    await adminUpdateOrderStatus(statusTarget.value.id, newStatus.value)
    statusTarget.value = null
    loadOrders(currentPage)
  } catch (err) {
    alert(err?.response?.data?.message ?? 'Gagal mengubah status.')
  } finally {
    submitting.value = false
  }
}

// ── Init ──────────────────────────────────────────────────────────────────

onMounted(() => loadOrders())
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
