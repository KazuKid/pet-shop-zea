<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Kelola User</h1>
      <div class="text-sm text-gray-500">
        Total: <strong class="text-gray-800">{{ meta.total ?? 0 }}</strong> user
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-4 flex flex-wrap gap-3">
      <input
        v-model="search"
        @input="debouncedLoad"
        type="text"
        placeholder="Cari nama atau email..."
        class="flex-1 min-w-48 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
      />
      <select
        v-model="filterRole"
        @change="loadUsers(1)"
        class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
      >
        <option value="">Semua Role</option>
        <option value="customer">Customer</option>
        <option value="admin">Admin</option>
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

      <div v-else-if="!users.length" class="p-8 text-center text-gray-400">
        Tidak ada user ditemukan.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-left text-xs text-gray-500 font-medium uppercase tracking-wide">
              <th class="px-4 py-3">User</th>
              <th class="px-4 py-3">No. HP</th>
              <th class="px-4 py-3 text-center">Role</th>
              <th class="px-4 py-3 text-center">Total Pesanan</th>
              <th class="px-4 py-3">Terdaftar</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                    :class="user.role === 'admin' ? 'bg-primary-500' : 'bg-gray-400'"
                  >
                    {{ user.name?.[0]?.toUpperCase() }}
                  </div>
                  <div>
                    <p class="font-medium text-gray-800">{{ user.name }}</p>
                    <p class="text-xs text-gray-400">{{ user.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 text-gray-600">{{ user.phone ?? '—' }}</td>
              <td class="px-4 py-3 text-center">
                <span
                  :class="user.role === 'admin'
                    ? 'bg-primary-100 text-primary-700'
                    : 'bg-gray-100 text-gray-600'"
                  class="px-2.5 py-1 rounded-full text-xs font-semibold"
                >
                  {{ user.role === 'admin' ? 'Admin' : 'Customer' }}
                </span>
              </td>
              <td class="px-4 py-3 text-center font-semibold text-gray-800">
                {{ user.orders_count }}
              </td>
              <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-1">
                  <button
                    @click="openEdit(user)"
                    class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                    title="Edit"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button
                    @click="confirmDelete(user)"
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
          <button @click="changePage(meta.current_page - 1)" :disabled="meta.current_page === 1"
            class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40">Sebelumnya</button>
          <button @click="changePage(meta.current_page + 1)" :disabled="meta.current_page === meta.last_page"
            class="px-3 py-1 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-40">Berikutnya</button>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/50" @click="closeModal"></div>
          <div class="relative bg-white rounded-2xl w-full max-w-md shadow-xl">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h2 class="text-lg font-bold text-gray-800">Edit User</h2>
              <button @click="closeModal" class="p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <form @submit.prevent="handleSubmit" class="px-6 py-4 flex flex-col gap-4">
              <!-- Avatar preview -->
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center font-bold text-lg">
                  {{ form.name?.[0]?.toUpperCase() }}
                </div>
                <div>
                  <p class="font-semibold text-gray-800">{{ editingUser?.email }}</p>
                  <p class="text-xs text-gray-400">ID: {{ editingUser?.id }}</p>
                </div>
              </div>

              <!-- Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input v-model="form.name" type="text" required
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.name }"
                />
                <p v-if="formErrors.name" class="text-red-500 text-xs mt-1">{{ formErrors.name[0] }}</p>
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input v-model="form.email" type="email" required
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                  :class="{ 'border-red-400': formErrors.email }"
                />
                <p v-if="formErrors.email" class="text-red-500 text-xs mt-1">{{ formErrors.email[0] }}</p>
              </div>

              <!-- Phone -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <input v-model="form.phone" type="text"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                />
              </div>

              <!-- Role -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select v-model="form.role"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                >
                  <option value="customer">Customer</option>
                  <option value="admin">Admin</option>
                </select>
              </div>

              <!-- New Password (optional) -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Password Baru
                  <span class="text-xs text-gray-400 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                </label>
                <div class="relative">
                  <input
                    v-model="form.password"
                    :type="showPass ? 'text' : 'password'"
                    placeholder="Min. 8 karakter"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                    :class="{ 'border-red-400': formErrors.password }"
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
                <p v-if="formErrors.password" class="text-red-500 text-xs mt-1">{{ formErrors.password[0] }}</p>
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
                  <span v-else>Simpan Perubahan</span>
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
            <h3 class="text-lg font-bold text-gray-800 text-center mb-2">Hapus User?</h3>
            <p class="text-sm text-gray-500 text-center mb-6">
              User <strong>{{ deleteTarget?.name }}</strong> ({{ deleteTarget?.email }}) akan dihapus secara permanen beserta semua token sesinya.
            </p>
            <div class="flex gap-3">
              <button @click="deleteTarget = null" class="flex-1 btn-secondary py-2.5 text-sm">Batal</button>
              <button @click="handleDelete" :disabled="submitting"
                class="flex-1 bg-red-600 text-white rounded-xl py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors disabled:opacity-50">
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
import { fetchAdminUsers, adminUpdateUser, adminDeleteUser } from '@/services/api'

// ── State ─────────────────────────────────────────────────────────────────

const users      = ref([])
const loading    = ref(true)
const search     = ref('')
const filterRole = ref('')
const meta       = ref({ current_page: 1, last_page: 1, total: 0 })
let   currentPage = 1

const showModal   = ref(false)
const submitting  = ref(false)
const formError   = ref('')
const formErrors  = ref({})
const showPass    = ref(false)
const editingUser = ref(null)
const deleteTarget = ref(null)

const form = reactive({
  name: '', email: '', phone: '', role: 'customer', password: '',
})

// ── Load ──────────────────────────────────────────────────────────────────

async function loadUsers(page = 1) {
  loading.value = true
  currentPage   = page
  try {
    const params = { page, per_page: 20 }
    if (search.value)     params.search = search.value
    if (filterRole.value) params.role   = filterRole.value
    const res  = await fetchAdminUsers(params)
    users.value = res.data.data
    meta.value  = {
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

let debounceTimer
function debouncedLoad() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => loadUsers(1), 400)
}

function changePage(page) {
  if (page < 1 || page > meta.value.last_page) return
  loadUsers(page)
}

// ── Helpers ───────────────────────────────────────────────────────────────

function formatDate(iso) {
  return new Date(iso).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}

// ── Modal ─────────────────────────────────────────────────────────────────

function openEdit(user) {
  editingUser.value = user
  form.name     = user.name
  form.email    = user.email
  form.phone    = user.phone ?? ''
  form.role     = user.role
  form.password = ''
  formError.value  = ''
  formErrors.value = {}
  showPass.value   = false
  showModal.value  = true
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
    const payload = { ...form }
    if (!payload.password) delete payload.password
    await adminUpdateUser(editingUser.value.id, payload)
    closeModal()
    loadUsers(currentPage)
  } catch (err) {
    const data = err?.response?.data
    if (data?.errors) formErrors.value = data.errors
    else formError.value = data?.message ?? 'Terjadi kesalahan.'
  } finally {
    submitting.value = false
  }
}

function confirmDelete(user) {
  deleteTarget.value = user
}

async function handleDelete() {
  submitting.value = true
  try {
    await adminDeleteUser(deleteTarget.value.id)
    deleteTarget.value = null
    loadUsers(currentPage)
  } catch (err) {
    alert(err?.response?.data?.message ?? 'Gagal menghapus user.')
    deleteTarget.value = null
  } finally {
    submitting.value = false
  }
}

// ── Init ──────────────────────────────────────────────────────────────────

onMounted(() => loadUsers())
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
