<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Kelola Kategori</h1>
      <button @click="openAdd" class="btn-primary flex items-center gap-2 text-sm px-4 py-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
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

      <div v-else-if="!categories.length" class="p-8 text-center text-gray-400">
        Belum ada kategori.
      </div>

      <table v-else class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr class="text-left text-xs text-gray-500 font-medium uppercase tracking-wide">
            <th class="px-6 py-3">Kategori</th>
            <th class="px-6 py-3">Slug</th>
            <th class="px-6 py-3 text-center">Jumlah Produk</th>
            <th class="px-6 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <span class="text-2xl">{{ cat.icon || '📦' }}</span>
                <span class="font-medium text-gray-800">{{ cat.name }}</span>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ cat.slug }}</td>
            <td class="px-6 py-4 text-center">
              <span class="px-2.5 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full">
                {{ cat.products_count }} produk
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-center gap-1">
                <button
                  @click="openEdit(cat)"
                  class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                  title="Edit"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click="confirmDelete(cat)"
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

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-sm shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 class="text-lg font-bold text-gray-800">{{ isEditing ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>
              <button @click="closeModal" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <form @submit.prevent="handleSubmit" class="px-6 py-4 flex flex-col gap-4">
              <!-- Icon -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Ikon <span class="text-xs text-gray-400">(emoji, contoh: 🐕)</span>
                </label>
                <div class="flex items-center gap-3">
                  <span class="text-3xl">{{ form.icon || '📦' }}</span>
                  <input
                    v-model="form.icon"
                    type="text"
                    maxlength="4"
                    placeholder="🐕"
                    class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  />
                </div>
              </div>

              <!-- Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.name }"
                />
                <p v-if="formErrors.name" class="text-red-500 text-xs mt-1">{{ formErrors.name[0] }}</p>
              </div>

              <!-- General error -->
              <div v-if="formError" class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600">
                {{ formError }}
              </div>

              <!-- Actions -->
              <div class="flex gap-3 pt-1">
                <button type="button" @click="closeModal" class="flex-1 btn-secondary py-2.5 text-sm">Batal</button>
                <button type="submit" :disabled="submitting" class="flex-1 btn-primary py-2.5 text-sm disabled:opacity-50">
                  <span v-if="submitting">Menyimpan...</span>
                  <span v-else>{{ isEditing ? 'Simpan' : 'Tambah' }}</span>
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
            <h3 class="text-lg font-bold text-gray-800 text-center mb-2">Hapus Kategori?</h3>
            <p class="text-sm text-gray-500 text-center mb-2">
              Kategori <strong>{{ deleteTarget?.name }}</strong> akan dihapus.
            </p>
            <p v-if="deleteTarget?.products_count > 0" class="text-sm text-red-600 text-center mb-4">
              ⚠️ Kategori ini masih memiliki {{ deleteTarget.products_count }} produk.
            </p>
            <div class="flex gap-3">
              <button @click="deleteTarget = null" class="flex-1 btn-secondary py-2.5 text-sm">Batal</button>
              <button
                @click="handleDelete"
                :disabled="submitting || deleteTarget?.products_count > 0"
                class="flex-1 bg-red-600 text-white rounded-xl py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
              >
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
  fetchCategories,
  adminCreateCategory,
  adminUpdateCategory,
  adminDeleteCategory,
} from '@/services/api'

// ── State ─────────────────────────────────────────────────────────────────

const categories = ref([])
const loading    = ref(true)
const showModal  = ref(false)
const isEditing  = ref(false)
const submitting = ref(false)
const formError  = ref('')
const formErrors = ref({})
const deleteTarget = ref(null)
let   editingId  = null

const form = reactive({ name: '', icon: '' })

// ── Load ──────────────────────────────────────────────────────────────────

async function loadCategories() {
  loading.value = true
  try {
    const res      = await fetchCategories()
    categories.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

// ── Modal ─────────────────────────────────────────────────────────────────

function resetForm() {
  form.name        = ''
  form.icon        = ''
  formError.value  = ''
  formErrors.value = {}
  editingId        = null
}

function openAdd() {
  resetForm()
  isEditing.value = false
  showModal.value = true
}

function openEdit(cat) {
  resetForm()
  isEditing.value = true
  editingId       = cat.id
  form.name       = cat.name
  form.icon       = cat.icon ?? ''
  showModal.value = true
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
    if (isEditing.value) {
      await adminUpdateCategory(editingId, { ...form })
    } else {
      await adminCreateCategory({ ...form })
    }
    closeModal()
    loadCategories()
  } catch (err) {
    const data = err?.response?.data
    if (data?.errors) formErrors.value = data.errors
    else formError.value = data?.message ?? 'Terjadi kesalahan.'
  } finally {
    submitting.value = false
  }
}

function confirmDelete(cat) {
  deleteTarget.value = cat
}

async function handleDelete() {
  submitting.value = true
  try {
    await adminDeleteCategory(deleteTarget.value.id)
    deleteTarget.value = null
    loadCategories()
  } catch (err) {
    alert(err?.response?.data?.message ?? 'Gagal menghapus kategori.')
    deleteTarget.value = null
  } finally {
    submitting.value = false
  }
}

// ── Init ──────────────────────────────────────────────────────────────────

onMounted(loadCategories)
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
