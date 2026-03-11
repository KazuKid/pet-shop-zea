<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Kelola Produk</h1>
      <button @click="openAdd" class="btn-primary flex items-center gap-2 text-sm px-4 py-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
      </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-4 flex flex-wrap gap-3">
      <input
        v-model="search"
        @input="debouncedLoad"
        type="text"
        placeholder="Cari produk..."
        class="flex-1 min-w-48 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
      />
      <select
        v-model="filterCategory"
        @change="loadProducts"
        class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
      >
        <option value="">Semua Kategori</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
      </select>
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

      <div v-else-if="!products.length" class="p-8 text-center text-gray-400">
        Tidak ada produk ditemukan.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-left text-xs text-gray-500 font-medium uppercase tracking-wide">
              <th class="px-4 py-3">Produk</th>
              <th class="px-4 py-3">Kategori</th>
              <th class="px-4 py-3 text-right">Harga</th>
              <th class="px-4 py-3 text-center">Stok</th>
              <th class="px-4 py-3 text-center">Badge</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <img :src="product.image" :alt="product.name" class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0" />
                  <div>
                    <p class="font-medium text-gray-800 leading-tight line-clamp-1">{{ product.name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">ID: {{ product.id }}</p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 text-gray-600">{{ product.category_label }}</td>
              <td class="px-4 py-3 text-right">
                <p class="font-semibold text-gray-800">{{ formatRupiah(product.price) }}</p>
                <p v-if="product.original_price" class="text-xs text-gray-400 line-through">
                  {{ formatRupiah(product.original_price) }}
                </p>
              </td>
              <td class="px-4 py-3 text-center">
                <span :class="product.stock > 10 ? 'text-green-600' : product.stock > 0 ? 'text-yellow-600' : 'text-red-600'" class="font-semibold">
                  {{ product.stock }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-1 flex-wrap">
                  <span v-if="product.is_new" class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Baru</span>
                  <span v-if="product.is_promo" class="px-1.5 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">Promo</span>
                  <span v-if="!product.is_new && !product.is_promo" class="text-gray-300 text-xs">—</span>
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-1">
                  <button
                    @click="openEdit(product)"
                    class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                    title="Edit"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button
                    @click="confirmDelete(product)"
                    class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                    title="Hapus"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
          <button
            @click="changePage(meta.current_page - 1)"
            :disabled="meta.current_page === 1"
            class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40"
          >Sebelumnya</button>
          <button
            @click="changePage(meta.current_page + 1)"
            :disabled="meta.current_page === meta.last_page"
            class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40"
          >Berikutnya</button>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 class="text-lg font-bold text-gray-800">{{ isEditing ? 'Edit Produk' : 'Tambah Produk' }}</h2>
              <button @click="closeModal" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <form @submit.prevent="handleSubmit" class="px-6 py-4 flex flex-col gap-4">
              <!-- Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input v-model="form.name" type="text" required
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.name }"
                />
                <p v-if="formErrors.name" class="text-red-500 text-xs mt-1">{{ formErrors.name[0] }}</p>
              </div>

              <!-- Category -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select v-model="form.category_id" required
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.category_id }"
                >
                  <option value="">Pilih kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <p v-if="formErrors.category_id" class="text-red-500 text-xs mt-1">{{ formErrors.category_id[0] }}</p>
              </div>

              <!-- Price & Original Price -->
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                  <input v-model.number="form.price" type="number" min="0" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                    :class="{ 'border-red-400': formErrors.price }"
                  />
                  <p v-if="formErrors.price" class="text-red-500 text-xs mt-1">{{ formErrors.price[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Harga Coret</label>
                  <input v-model.number="form.original_price" type="number" min="0"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  />
                </div>
              </div>

              <!-- Stock -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                <input v-model.number="form.stock" type="number" min="0" required
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.stock }"
                />
                <p v-if="formErrors.stock" class="text-red-500 text-xs mt-1">{{ formErrors.stock[0] }}</p>
              </div>

              <!-- Image URL -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar <span class="text-red-500">*</span></label>
                <input v-model="form.image" type="url" required
                  placeholder="https://..."
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.image }"
                />
                <div v-if="form.image" class="mt-2">
                  <img :src="form.image" class="h-16 w-16 object-cover rounded-lg border border-gray-200" @error="(e) => e.target.style.display='none'" />
                </div>
                <p v-if="formErrors.image" class="text-red-500 text-xs mt-1">{{ formErrors.image[0] }}</p>
              </div>

              <!-- Description -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea v-model="form.description" rows="3"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"
                ></textarea>
              </div>

              <!-- Tags -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tags <span class="text-xs text-gray-400">(pisahkan dengan koma)</span></label>
                <input v-model="tagsInput" type="text" placeholder="makanan, anjing, premium"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                />
              </div>

              <!-- Badges -->
              <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="form.is_new" type="checkbox" class="w-4 h-4 rounded accent-primary-500" />
                  <span class="text-sm text-gray-700">Produk Baru</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="form.is_promo" type="checkbox" class="w-4 h-4 rounded accent-primary-500" />
                  <span class="text-sm text-gray-700">Promo</span>
                </label>
              </div>

              <!-- General error -->
              <div v-if="formError" class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600">
                {{ formError }}
              </div>

              <!-- Actions -->
              <div class="flex gap-3 pt-2">
                <button type="button" @click="closeModal" class="flex-1 btn-secondary py-2.5 text-sm">Batal</button>
                <button type="submit" :disabled="submitting" class="flex-1 btn-primary py-2.5 text-sm disabled:opacity-50">
                  <span v-if="submitting">Menyimpan...</span>
                  <span v-else>{{ isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Delete Confirm Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="deleteTarget = null"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 text-center mb-2">Hapus Produk?</h3>
            <p class="text-sm text-gray-500 text-center mb-6">
              Produk <strong>{{ deleteTarget?.name }}</strong> akan dihapus secara permanen.
            </p>
            <div class="flex gap-3">
              <button @click="deleteTarget = null" class="flex-1 btn-secondary py-2.5 text-sm">Batal</button>
              <button @click="handleDelete" :disabled="submitting" class="flex-1 bg-red-600 text-white rounded-xl py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors disabled:opacity-50">
                <span v-if="submitting">Menghapus...</span>
                <span v-else>Hapus</span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import {
  fetchProducts,
  fetchCategories,
  adminCreateProduct,
  adminUpdateProduct,
  adminDeleteProduct,
} from '@/services/api'

// ── State ──────────────────────────────────────────────────────────────────

const products       = ref([])
const categories     = ref([])
const loading        = ref(true)
const search         = ref('')
const filterCategory = ref('')
const meta           = ref({ current_page: 1, last_page: 1 })
let   currentPage    = 1

// Modal state
const showModal  = ref(false)
const isEditing  = ref(false)
const submitting = ref(false)
const formError  = ref('')
const formErrors = ref({})
const deleteTarget = ref(null)
const tagsInput  = ref('')

const form = reactive({
  name: '',
  category_id: '',
  price: '',
  original_price: '',
  stock: '',
  image: '',
  description: '',
  is_new: false,
  is_promo: false,
})
let editingId = null

// ── Load ──────────────────────────────────────────────────────────────────

async function loadProducts(page = 1) {
  loading.value = true
  currentPage   = page
  try {
    const params = { page, per_page: 15 }
    if (search.value)         params.search   = search.value
    if (filterCategory.value) params.category = filterCategory.value
    const res = await fetchProducts(params)
    products.value = res.data.data
    meta.value     = { current_page: res.data.current_page, last_page: res.data.last_page }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

let debounceTimer
function debouncedLoad() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => loadProducts(1), 400)
}

function changePage(page) {
  if (page < 1 || page > meta.value.last_page) return
  loadProducts(page)
}

// ── Helpers ───────────────────────────────────────────────────────────────

function formatRupiah(val) {
  return 'Rp ' + Number(val ?? 0).toLocaleString('id-ID')
}

// ── Modal ─────────────────────────────────────────────────────────────────

function resetForm() {
  Object.assign(form, {
    name: '', category_id: '', price: '', original_price: '',
    stock: '', image: '', description: '', is_new: false, is_promo: false,
  })
  tagsInput.value = ''
  formError.value  = ''
  formErrors.value = {}
  editingId        = null
}

function openAdd() {
  resetForm()
  isEditing.value = false
  showModal.value = true
}

function openEdit(product) {
  resetForm()
  isEditing.value          = true
  editingId                = product.id
  form.name                = product.name
  form.category_id         = product.category_id ?? categories.value.find(c => c.slug === product.category)?.id ?? ''
  form.price               = product.price
  form.original_price      = product.original_price ?? ''
  form.stock               = product.stock
  form.image               = product.image
  form.description         = product.description ?? ''
  form.is_new              = product.is_new
  form.is_promo            = product.is_promo
  tagsInput.value          = (product.tags ?? []).join(', ')
  showModal.value          = true
}

function closeModal() {
  showModal.value = false
}

// ── CRUD ──────────────────────────────────────────────────────────────────

async function handleSubmit() {
  submitting.value = true
  formError.value  = ''
  formErrors.value = {}
  try {
    const payload = {
      ...form,
      tags: tagsInput.value ? tagsInput.value.split(',').map(t => t.trim()).filter(Boolean) : [],
      original_price: form.original_price || null,
    }
    if (isEditing.value) {
      await adminUpdateProduct(editingId, payload)
    } else {
      await adminCreateProduct(payload)
    }
    closeModal()
    loadProducts(currentPage)
  } catch (err) {
    const data = err?.response?.data
    if (data?.errors) formErrors.value = data.errors
    else formError.value = data?.message ?? 'Terjadi kesalahan.'
  } finally {
    submitting.value = false
  }
}

function confirmDelete(product) {
  deleteTarget.value = product
}

async function handleDelete() {
  submitting.value = true
  try {
    await adminDeleteProduct(deleteTarget.value.id)
    deleteTarget.value = null
    loadProducts(currentPage)
  } catch (err) {
    alert(err?.response?.data?.message ?? 'Gagal menghapus produk.')
  } finally {
    submitting.value = false
  }
}

// ── Init ──────────────────────────────────────────────────────────────────

onMounted(async () => {
  const [, catRes] = await Promise.allSettled([
    loadProducts(),
    fetchCategories(),
  ])
  if (catRes.status === 'fulfilled') categories.value = catRes.value.data
})
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .relative, .modal-leave-to .relative { transform: scale(0.95); }
</style>
