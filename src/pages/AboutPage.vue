<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-primary-500 to-amber-400 py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="text-6xl mb-4">🐾</div>
        <h1 class="text-4xl font-display font-bold text-white mb-3">Tentang Zea Pet Shop</h1>
        <p class="text-white/90 text-lg max-w-2xl mx-auto">
          Kami hadir untuk memastikan hewan peliharaan kesayanganmu mendapatkan yang terbaik, dengan produk berkualitas dan pelayanan yang tulus dari hati.
        </p>
      </div>
    </section>

    <!-- Story -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-3xl font-display font-bold text-gray-900 mb-4">Cerita Kami</h2>
          <p class="text-gray-600 leading-relaxed mb-4">
            Zea Pet Shop didirikan pada tahun 2020 oleh Zea Rahmadhani, seorang pecinta hewan yang frustrasi karena sulitnya menemukan produk hewan peliharaan berkualitas dengan harga terjangkau di Indonesia.
          </p>
          <p class="text-gray-600 leading-relaxed mb-4">
            Dimulai dari sebuah toko kecil di Bandung, kini Zea Pet Shop telah melayani lebih dari 10.000 pelanggan di seluruh Indonesia. Kami berkomitmen untuk menyediakan produk terbaik untuk anjing, kucing, burung, ikan, kelinci, dan hewan peliharaan lainnya.
          </p>
          <p class="text-gray-600 leading-relaxed">
            Setiap produk yang kami jual telah melalui seleksi ketat untuk memastikan kualitas dan keamanannya bagi hewan peliharaan kesayanganmu.
          </p>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div v-for="stat in stats" :key="stat.label" class="card p-6 text-center">
            <div class="text-4xl mb-2">{{ stat.icon }}</div>
            <div class="text-3xl font-display font-bold text-primary-600 mb-1">{{ stat.value }}</div>
            <div class="text-sm text-gray-500">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
          <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Nilai-Nilai Kami</h2>
          <p class="text-gray-500">Yang membuat kami berbeda dari yang lain</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="v in values" :key="v.title" class="card p-6">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl mb-4" :class="v.bg">{{ v.icon }}</div>
            <h3 class="font-display font-bold text-gray-900 mb-2">{{ v.title }}</h3>
            <p class="text-gray-600 text-sm leading-relaxed">{{ v.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Team -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">Tim Kami</h2>
        <p class="text-gray-500">Orang-orang berdedikasi di balik Zea Pet Shop</p>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
        <div v-for="member in team" :key="member.name" class="card p-5 text-center">
          <div class="w-16 h-16 rounded-2xl mx-auto mb-3 flex items-center justify-center text-3xl" :class="member.bg">{{ member.avatar }}</div>
          <div class="font-semibold text-gray-800 text-sm">{{ member.name }}</div>
          <div class="text-xs text-primary-600 font-medium">{{ member.role }}</div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="bg-primary-500 py-14 text-center">
      <h2 class="text-3xl font-display font-bold text-white mb-3">Siap Berbelanja?</h2>
      <p class="text-white/80 mb-6 text-sm">Temukan ribuan produk terbaik untuk hewan peliharaanmu</p>
      <RouterLink to="/products" class="btn-primary bg-white text-primary-600 hover:bg-yellow-50">
        Lihat Semua Produk
      </RouterLink>
    </section>
  </div>
</template>

<script setup>
const stats = [
  { icon: '🛍️', value: '500+', label: 'Produk Tersedia' },
  { icon: '😊', value: '10K+', label: 'Pelanggan Puas' },
  { icon: '⭐', value: '4.9/5', label: 'Rating Rata-rata' },
  { icon: '📦', value: '50K+', label: 'Pesanan Terkirim' },
]

const values = [
  {
    icon: '✅', bg: 'bg-green-100',
    title: 'Kualitas Terjamin',
    desc: 'Setiap produk kami seleksi dengan ketat. Kami hanya menjual produk yang aman dan berkualitas untuk hewan kesayanganmu.',
  },
  {
    icon: '💰', bg: 'bg-yellow-100',
    title: 'Harga Terjangkau',
    desc: 'Kami berkomitmen memberikan harga terbaik tanpa mengorbankan kualitas. Berbagai promo dan diskon tersedia setiap harinya.',
  },
  {
    icon: '🚚', bg: 'bg-blue-100',
    title: 'Pengiriman Cepat',
    desc: 'Bekerjasama dengan kurir terpercaya untuk memastikan pesananmu sampai dengan cepat dan aman di seluruh Indonesia.',
  },
  {
    icon: '💬', bg: 'bg-purple-100',
    title: 'Konsultasi Gratis',
    desc: 'Tim kami siap membantu konsultasi pemilihan produk yang tepat untuk jenis dan usia hewan peliharaan kamu.',
  },
  {
    icon: '🌱', bg: 'bg-emerald-100',
    title: 'Ramah Lingkungan',
    desc: 'Kami menggunakan packaging ramah lingkungan dan mendukung produk-produk yang diproduksi secara berkelanjutan.',
  },
  {
    icon: '🔄', bg: 'bg-orange-100',
    title: 'Retur Mudah',
    desc: 'Jika ada masalah dengan produk, kami memberikan kemudahan retur dan pengembalian uang dalam 7 hari kerja.',
  },
]

const team = [
  { name: 'Zea Rahmadhani', role: 'Founder & CEO', avatar: '👩', bg: 'bg-pink-100' },
  { name: 'Arif Budiman',    role: 'Head of Operations', avatar: '👨', bg: 'bg-blue-100' },
  { name: 'Sari Dewi',       role: 'Product Manager', avatar: '👩', bg: 'bg-purple-100' },
  { name: 'Denny Pratama',   role: 'Customer Support', avatar: '🧑', bg: 'bg-green-100' },
]
</script>
