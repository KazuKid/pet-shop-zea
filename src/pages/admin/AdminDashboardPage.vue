<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- Stats cards -->
    <div v-if="loading" class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div v-for="n in 4" :key="n" class="bg-white rounded-2xl p-5 animate-pulse h-28"></div>
    </div>

    <div v-else class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500 font-medium">Total Produk</span>
          <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ stats.total_products }}</p>
      </div>

      <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500 font-medium">Kategori</span>
          <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ stats.total_categories }}</p>
      </div>

      <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500 font-medium">Total Pesanan</span>
          <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ stats.total_orders }}</p>
      </div>

      <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500 font-medium">Total Pendapatan</span>
          <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ formatRupiah(stats.total_revenue) }}</p>
      </div>
    </div>

    <!-- Order Status + Recent orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Order statuses -->
      <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Status Pesanan</h2>
        <div v-if="loading" class="space-y-3">
          <div v-for="n in 5" :key="n" class="h-8 bg-gray-100 rounded-lg animate-pulse"></div>
        </div>
        <div v-else class="space-y-3">
          <div v-for="(item, status) in statusList" :key="status" class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span :class="item.dot" class="w-2.5 h-2.5 rounded-full shrink-0"></span>
              <span class="text-sm text-gray-600">{{ item.label }}</span>
            </div>
            <span class="text-sm font-semibold text-gray-800">{{ stats.orders_by_status?.[status] ?? 0 }}</span>
          </div>
        </div>
      </div>

      <!-- Recent orders -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-base font-semibold text-gray-800">Pesanan Terbaru</h2>
          <RouterLink to="/admin/orders" class="text-sm text-primary-600 hover:underline">Lihat semua</RouterLink>
        </div>
        <div v-if="loading" class="space-y-3">
          <div v-for="n in 5" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse"></div>
        </div>
        <div v-else-if="!stats.recent_orders?.length" class="text-center text-gray-400 text-sm py-6">
          Belum ada pesanan
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-xs text-gray-400 border-b border-gray-100">
                <th class="pb-2 font-medium">No. Pesanan</th>
                <th class="pb-2 font-medium">Pelanggan</th>
                <th class="pb-2 font-medium text-right">Total</th>
                <th class="pb-2 font-medium text-center">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="order in stats.recent_orders" :key="order.id">
                <td class="py-2.5 font-mono text-xs text-gray-700">{{ order.order_number }}</td>
                <td class="py-2.5 text-gray-700">{{ order.first_name }} {{ order.last_name }}</td>
                <td class="py-2.5 text-right font-semibold text-gray-800">{{ formatRupiah(order.total) }}</td>
                <td class="py-2.5 text-center">
                  <span :class="statusBadge(order.status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                    {{ statusLabel(order.status) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchAdminStats } from '@/services/api'

const loading = ref(true)
const stats   = ref({
  total_products: 0,
  total_categories: 0,
  total_orders: 0,
  total_revenue: 0,
  orders_by_status: {},
  recent_orders: [],
})

const statusList = {
  pending:    { label: 'Menunggu',   dot: 'bg-yellow-400' },
  processing: { label: 'Diproses',   dot: 'bg-blue-400'   },
  shipped:    { label: 'Dikirim',    dot: 'bg-indigo-400' },
  delivered:  { label: 'Terkirim',   dot: 'bg-green-400'  },
  cancelled:  { label: 'Dibatalkan', dot: 'bg-red-400'    },
}

function formatRupiah(val) {
  return 'Rp ' + Number(val ?? 0).toLocaleString('id-ID')
}

function statusLabel(s) {
  return statusList[s]?.label ?? s
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

onMounted(async () => {
  try {
    const res = await fetchAdminStats()
    stats.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>
