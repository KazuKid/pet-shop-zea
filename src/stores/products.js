import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { products as allProducts } from '@/data/products'
import { fetchProducts, fetchProduct } from '@/services/api'

export const useProductStore = defineStore('products', () => {
  const products = ref([])
  const loading  = ref(false)
  const error    = ref(null)

  // Pagination meta returned by Laravel
  const meta = ref({ current_page: 1, last_page: 1, total: 0 })

  const searchQuery      = ref('')
  const selectedCategory = ref('all')
  const sortBy           = ref('default')
  const wishlist         = ref([])

  /** Load products from API; falls back to static data when offline */
  async function loadProducts(params = {}) {
    loading.value = true
    error.value   = null
    try {
      const res = await fetchProducts({
        category : selectedCategory.value !== 'all' ? selectedCategory.value : undefined,
        search   : searchQuery.value || undefined,
        sort     : sortBy.value !== 'default' ? sortBy.value : undefined,
        per_page : params.per_page ?? 20,
        page     : params.page    ?? 1,
      })
      products.value = res.data.data ?? res.data
      if (res.data.meta)   meta.value = res.data.meta
      if (res.data.last_page) {
        meta.value = {
          current_page: res.data.current_page,
          last_page   : res.data.last_page,
          total       : res.data.total,
        }
      }
    } catch {
      // Fallback: use static data so the UI still works without a backend
      let list = [...allProducts]
      if (selectedCategory.value !== 'all') {
        list = list.filter(p => p.category === selectedCategory.value)
      }
      if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase()
        list = list.filter(p =>
          p.name.toLowerCase().includes(q) ||
          p.description.toLowerCase().includes(q) ||
          (p.tags ?? []).some(t => t.toLowerCase().includes(q))
        )
      }
      switch (sortBy.value) {
        case 'price-asc':  list.sort((a, b) => a.price - b.price);  break
        case 'price-desc': list.sort((a, b) => b.price - a.price);  break
        case 'rating':     list.sort((a, b) => b.rating - a.rating); break
        case 'popular':    list.sort((a, b) => b.sold - a.sold);     break
      }
      products.value = list
    } finally {
      loading.value = false
    }
  }

  /** Fetch a single product by id (API first, static fallback) */
  async function loadProduct(id) {
    try {
      const res = await fetchProduct(id)
      // Normalise API field names to match the frontend conventions
      const p = res.data
      return {
        ...p,
        categoryLabel: p.category_label ?? p.categoryLabel,
        originalPrice: p.original_price ?? p.originalPrice,
        reviewsCount : p.reviews_count  ?? p.reviews,
        soldCount    : p.sold_count     ?? p.sold,
        isNew        : p.is_new         ?? p.isNew,
        isPromo      : p.is_promo       ?? p.isPromo,
      }
    } catch {
      return allProducts.find(p => p.id === Number(id)) ?? null
    }
  }

  // ── Computed helpers over the already-loaded products list ────────────────

  const filtered = computed(() => products.value)

  const featured = computed(() =>
    [...allProducts].filter(p => p.rating >= 4.8).slice(0, 4)
  )

  const promoProducts = computed(() =>
    allProducts.filter(p => p.isPromo)
  )

  const newProducts = computed(() =>
    allProducts.filter(p => p.isNew)
  )

  function getById(id) {
    return products.value.find(p => p.id === Number(id))
          ?? allProducts.find(p => p.id === Number(id))
  }

  function toggleWishlist(id) {
    const idx = wishlist.value.indexOf(id)
    if (idx >= 0) wishlist.value.splice(idx, 1)
    else wishlist.value.push(id)
  }

  function isWishlisted(id) {
    return wishlist.value.includes(id)
  }

  return {
    products, loading, error, meta,
    searchQuery, selectedCategory, sortBy, wishlist,
    filtered, featured, promoProducts, newProducts,
    loadProducts, loadProduct, getById, toggleWishlist, isWishlisted,
  }
})
