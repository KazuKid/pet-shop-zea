<template>
  <div class="min-h-screen flex items-center justify-center px-4 py-20 text-center">
    <div>
      <div class="text-8xl mb-6">😿</div>
      <h1 class="text-5xl font-display font-bold text-gray-900 mb-3">404</h1>
      <h2 class="text-2xl font-display font-semibold text-gray-700 mb-3">Halaman Tidak Ditemukan</h2>
      <p class="text-gray-500 text-sm mb-8 max-w-sm mx-auto">
        Ups! Sepertinya hewan peliharaanmu memakan halaman ini. Halaman yang kamu cari tidak tersedia.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <RouterLink to="/" class="btn-primary">Kembali ke Beranda</RouterLink>
        <RouterLink to="/products" class="btn-secondary">Lihat Produk</RouterLink>
      </div>
    </div>
  </div>
</template>
