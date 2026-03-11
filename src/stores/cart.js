import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { createOrder } from '@/services/api'

export const useCartStore = defineStore('cart', () => {
  const items = ref([])

  const totalItems = computed(() => items.value.reduce((sum, i) => sum + i.qty, 0))

  const subtotal = computed(() => items.value.reduce((sum, i) => sum + i.price * i.qty, 0))

  const shippingCost = computed(() => subtotal.value >= 200000 ? 0 : 25000)

  const total = computed(() => subtotal.value + shippingCost.value)

  function addItem(product, qty = 1) {
    const existing = items.value.find(i => i.id === product.id)
    if (existing) {
      existing.qty = Math.min(existing.qty + qty, product.stock)
    } else {
      items.value.push({ ...product, qty })
    }
  }

  function removeItem(productId) {
    items.value = items.value.filter(i => i.id !== productId)
  }

  function updateQty(productId, qty) {
    const item = items.value.find(i => i.id === productId)
    if (item) {
      if (qty <= 0) removeItem(productId)
      else item.qty = qty
    }
  }

  function clearCart() {
    items.value = []
  }

  /**
   * Submit the cart as an order to the Laravel backend.
   * @param {object} shippingData – form fields from CheckoutPage
   * @returns {{ orderNumber: string }} on success
   */
  async function placeOrder(shippingData) {
    const payload = {
      ...shippingData,
      items: items.value.map(i => ({
        product_id: i.id,
        qty       : i.qty,
      })),
    }
    const res = await createOrder(payload)
    clearCart()
    return res.data
  }

  return { items, totalItems, subtotal, shippingCost, total, addItem, removeItem, updateQty, clearCart, placeOrder }
})
