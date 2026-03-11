<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-500 to-amber-400 py-14">
      <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="text-5xl mb-3">📞</div>
        <h1 class="text-4xl font-display font-bold text-white mb-2">Hubungi Kami</h1>
        <p class="text-white/85 text-base">Punya pertanyaan? Tim kami siap membantu kamu!</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Contact info -->
        <div class="space-y-5">
          <h2 class="text-2xl font-display font-bold text-gray-900">Info Kontak</h2>

          <div v-for="info in contactInfo" :key="info.label" class="card p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl shrink-0" :class="info.bg">
              {{ info.icon }}
            </div>
            <div>
              <div class="font-semibold text-gray-800 text-sm mb-0.5">{{ info.label }}</div>
              <div class="text-gray-600 text-sm leading-relaxed">{{ info.value }}</div>
              <div v-if="info.sub" class="text-gray-400 text-xs mt-0.5">{{ info.sub }}</div>
            </div>
          </div>

          <!-- Social Media -->
          <div class="card p-5">
            <div class="font-semibold text-gray-800 text-sm mb-3">Ikuti Kami</div>
            <div class="flex gap-3">
              <a v-for="s in socials" :key="s.name" href="#"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-lg hover:bg-primary-50 transition-colors"
                :class="s.bg"
              >{{ s.icon }}</a>
            </div>
          </div>
        </div>

        <!-- Contact form -->
        <div class="lg:col-span-2">
          <div class="card p-8">
            <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">Kirim Pesan</h2>

            <div v-if="sent" class="text-center py-12">
              <div class="text-6xl mb-4">✉️</div>
              <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Pesan Terkirim!</h3>
              <p class="text-gray-500 text-sm mb-4">Kami akan membalas dalam 1x24 jam kerja</p>
              <button @click="sent = false" class="btn-secondary text-sm px-5 py-2">Kirim Pesan Lain</button>
            </div>

            <form v-else @submit.prevent="sendMessage" class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama *</label>
                  <input v-model="form.name" type="text" class="input-field" placeholder="Nama kamu" required />
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                  <input v-model="form.email" type="email" class="input-field" placeholder="email@kamu.com" required />
                </div>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Subjek *</label>
                <select v-model="form.subject" class="input-field">
                  <option value="">Pilih Subjek</option>
                  <option value="Pertanyaan Produk">Pertanyaan Produk</option>
                  <option value="Status Pesanan">Status Pesanan</option>
                  <option value="Retur & Refund">Retur & Refund</option>
                  <option value="Pengiriman">Pengiriman</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pesan *</label>
                <textarea
                  v-model="form.message"
                  class="input-field resize-none"
                  rows="6"
                  placeholder="Tuliskan pertanyaan atau masukan kamu di sini..."
                  required
                ></textarea>
              </div>
              <div class="flex items-start gap-3">
                <input type="checkbox" id="agree" v-model="form.agree" class="mt-1 accent-primary-500" required />
                <label for="agree" class="text-sm text-gray-600">
                  Saya setuju dengan <a href="#" class="text-primary-600 hover:underline">Kebijakan Privasi</a> dan pemrosesan data pribadi saya.
                </label>
              </div>
              <button type="submit" class="btn-primary w-full">
                Kirim Pesan ✉️
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- FAQ -->
      <div class="mt-16">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Pertanyaan Umum (FAQ)</h2>
          <p class="text-gray-500 text-sm">Jawaban atas pertanyaan yang sering ditanyakan</p>
        </div>
        <div class="max-w-3xl mx-auto space-y-3">
          <div
            v-for="(faq, i) in faqs"
            :key="i"
            class="card overflow-hidden"
          >
            <button
              @click="openFaq = openFaq === i ? null : i"
              class="w-full p-5 flex items-center justify-between text-left font-semibold text-gray-800 hover:bg-gray-50 transition-colors text-sm"
            >
              {{ faq.q }}
              <svg class="w-4 h-4 shrink-0 ml-3 transition-transform" :class="openFaq === i ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div v-show="openFaq === i" class="px-5 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
              {{ faq.a }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const sent = ref(false)
const openFaq = ref(null)

const form = reactive({ name: '', email: '', subject: '', message: '', agree: false })

function sendMessage() {
  sent.value = true
  Object.assign(form, { name: '', email: '', subject: '', message: '', agree: false })
}

const contactInfo = [
  { icon: '📍', bg: 'bg-red-50',    label: 'Alamat', value: 'Jl. Hewan Peliharaan No. 123', sub: 'Kota Bandung, Jawa Barat 40123' },
  { icon: '📱', bg: 'bg-green-50',  label: 'WhatsApp', value: '0812-3456-7890', sub: 'Senin–Sabtu 08.00–20.00 WIB' },
  { icon: '✉️', bg: 'bg-blue-50',   label: 'Email', value: 'hello@zeapetshop.id', sub: 'Balasan dalam 1x24 jam' },
  { icon: '🕐', bg: 'bg-amber-50',  label: 'Jam Operasional', value: 'Senin – Sabtu', sub: '08.00 – 20.00 WIB' },
]

const socials = [
  { name: 'Facebook',  icon: '📘', bg: 'bg-blue-50'  },
  { name: 'Instagram', icon: '📸', bg: 'bg-pink-50'  },
  { name: 'WhatsApp',  icon: '💬', bg: 'bg-green-50' },
  { name: 'TikTok',    icon: '🎵', bg: 'bg-gray-50'  },
]

const faqs = [
  { q: 'Bagaimana cara memesan produk di Zea Pet Shop?', a: 'Kamu bisa pilih produk yang diinginkan, tambahkan ke keranjang, lalu ikuti proses checkout. Isi data pengiriman dan pilih metode pembayaran. Pesanan akan segera diproses setelah pembayaran dikonfirmasi.' },
  { q: 'Berapa lama pengiriman ke seluruh Indonesia?', a: 'Lama pengiriman tergantung lokasi tujuan. Untuk Jawa: 1-3 hari kerja, Sumatera: 2-5 hari, Kalimantan & Sulawesi: 3-7 hari, Indonesia Timur: 5-10 hari. Kami bekerja sama dengan JNE, J&T, SiCepat, dan GoSend.' },
  { q: 'Apakah ada minimum pembelian untuk gratis ongkir?', a: 'Ya, gratis ongkir untuk pembelian minimum Rp200.000. Khusus wilayah Bandung, pesanan di atas Rp150.000 sudah gratis ongkir menggunakan layanan GoSend atau GrabExpress.' },
  { q: 'Bagaimana jika produk yang saya terima rusak atau tidak sesuai?', a: 'Hubungi kami dalam 7 hari setelah menerima pesanan melalui WhatsApp dengan menyertakan foto/video kondisi produk. Kami akan memproses retur atau penggantian produk dengan cepat.' },
  { q: 'Apakah produk yang dijual 100% original?', a: 'Ya, semua produk yang kami jual adalah produk original dari distributor resmi. Kami tidak menjual produk palsu atau bajakan. Jika ada keraguan, silahkan hubungi kami untuk verifikasi.' },
]
</script>
