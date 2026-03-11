<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ── Dog ──────────────────────────────────────────────────────────
            [
                'category'       => 'dog',
                'name'           => 'Royal Canin Adult Dog Food 4kg',
                'price'          => 285000,
                'original_price' => 320000,
                'rating'         => 4.8,
                'reviews_count'  => 128,
                'stock'          => 50,
                'sold_count'     => 234,
                'image'          => 'https://placehold.co/400x400/FDE68A/92400E?text=Royal+Canin',
                'images'         => [
                    'https://placehold.co/600x600/FDE68A/92400E?text=Royal+Canin+1',
                    'https://placehold.co/600x600/FDE68A/92400E?text=Royal+Canin+2',
                    'https://placehold.co/600x600/FDE68A/92400E?text=Royal+Canin+3',
                ],
                'description'    => 'Makanan premium untuk anjing dewasa. Diformulasikan khusus untuk memenuhi kebutuhan nutrisi anjing dewasa dengan protein berkualitas tinggi dan vitamin mineral lengkap.',
                'tags'           => ['makanan', 'premium', 'dewasa'],
                'is_new'         => false,
                'is_promo'       => true,
            ],
            [
                'category'       => 'dog',
                'name'           => 'Pedigree Wet Food Chicken 100g (Pack 12)',
                'price'          => 95000,
                'original_price' => null,
                'rating'         => 4.5,
                'reviews_count'  => 87,
                'stock'          => 120,
                'sold_count'     => 456,
                'image'          => 'https://placehold.co/400x400/FCA5A5/7F1D1D?text=Pedigree+Wet',
                'images'         => [
                    'https://placehold.co/600x600/FCA5A5/7F1D1D?text=Pedigree+1',
                    'https://placehold.co/600x600/FCA5A5/7F1D1D?text=Pedigree+2',
                ],
                'description'    => 'Makanan basah Pedigree rasa ayam. Kaya akan protein dan mudah dicerna, cocok sebagai makanan sehari-hari atau camilan spesial untuk anjing kesayangan Anda.',
                'tags'           => ['makanan basah', 'ayam', 'pack'],
                'is_new'         => false,
                'is_promo'       => false,
            ],
            [
                'category'       => 'dog',
                'name'           => 'Kong Classic Dog Toy – Medium',
                'price'          => 185000,
                'original_price' => null,
                'rating'         => 4.9,
                'reviews_count'  => 203,
                'stock'          => 30,
                'sold_count'     => 178,
                'image'          => 'https://placehold.co/400x400/86EFAC/14532D?text=Kong+Toy',
                'images'         => [
                    'https://placehold.co/600x600/86EFAC/14532D?text=Kong+Toy+1',
                ],
                'description'    => 'Mainan interaktif tahan lama untuk anjing. Dapat diisi dengan camilan favorit. Terbuat dari karet alam yang aman dan tahan gigitan kuat.',
                'tags'           => ['mainan', 'interaktif', 'karet'],
                'is_new'         => true,
                'is_promo'       => false,
            ],
            [
                'category'       => 'dog',
                'name'           => 'Kalung & Tali Anjing Tactical Set',
                'price'          => 125000,
                'original_price' => 155000,
                'rating'         => 4.7,
                'reviews_count'  => 64,
                'stock'          => 45,
                'sold_count'     => 92,
                'image'          => 'https://placehold.co/400x400/93C5FD/1E3A8A?text=Tactical+Set',
                'images'         => [
                    'https://placehold.co/600x600/93C5FD/1E3A8A?text=Tali+1',
                    'https://placehold.co/600x600/93C5FD/1E3A8A?text=Tali+2',
                ],
                'description'    => 'Set kalung dan tali jalan untuk anjing dengan desain tactical. Material nylon premium, adjustable, nyaman dan aman untuk anjing ukuran sedang hingga besar.',
                'tags'           => ['aksesoris', 'kalung', 'tali'],
                'is_new'         => false,
                'is_promo'       => true,
            ],
            // ── Cat ──────────────────────────────────────────────────────────
            [
                'category'       => 'cat',
                'name'           => 'Whiskas Adult Cat Food Tuna 1.2kg',
                'price'          => 55000,
                'original_price' => null,
                'rating'         => 4.6,
                'reviews_count'  => 312,
                'stock'          => 200,
                'sold_count'     => 1023,
                'image'          => 'https://placehold.co/400x400/C4B5FD/4C1D95?text=Whiskas+Tuna',
                'images'         => [
                    'https://placehold.co/600x600/C4B5FD/4C1D95?text=Whiskas+1',
                    'https://placehold.co/600x600/C4B5FD/4C1D95?text=Whiskas+2',
                ],
                'description'    => 'Makanan kering untuk kucing dewasa dengan rasa tuna lezat. Mengandung omega-3 dan omega-6 untuk bulu sehat mengkilap, serta taurin untuk kesehatan mata dan jantung.',
                'tags'           => ['makanan', 'tuna', 'dewasa'],
                'is_new'         => false,
                'is_promo'       => false,
            ],
            [
                'category'       => 'cat',
                'name'           => 'Royal Canin Kitten 2kg',
                'price'          => 195000,
                'original_price' => 220000,
                'rating'         => 4.9,
                'reviews_count'  => 178,
                'stock'          => 75,
                'sold_count'     => 389,
                'image'          => 'https://placehold.co/400x400/FDE68A/78350F?text=RC+Kitten',
                'images'         => [
                    'https://placehold.co/600x600/FDE68A/78350F?text=Kitten+1',
                ],
                'description'    => 'Makanan premium untuk anak kucing usia 4-12 bulan. Formula khusus mendukung pertumbuhan optimal, perkembangan sistem imun, dan kesehatan saluran pencernaan.',
                'tags'           => ['makanan', 'kitten', 'premium'],
                'is_new'         => false,
                'is_promo'       => true,
            ],
            [
                'category'       => 'cat',
                'name'           => 'Pasir Kucing Cat Litter Clumping 10L',
                'price'          => 75000,
                'original_price' => null,
                'rating'         => 4.4,
                'reviews_count'  => 95,
                'stock'          => 150,
                'sold_count'     => 567,
                'image'          => 'https://placehold.co/400x400/D9F99D/365314?text=Cat+Litter',
                'images'         => [
                    'https://placehold.co/600x600/D9F99D/365314?text=Litter+1',
                ],
                'description'    => 'Pasir kucing bentonit clumping 10 liter. Menyerap bau maksimal, mudah menggumpal untuk pembersihan praktis. Bebas debu, aman untuk kucing dan manusia.',
                'tags'           => ['pasir', 'clumping', 'toilet'],
                'is_new'         => false,
                'is_promo'       => false,
            ],
            [
                'category'       => 'cat',
                'name'           => 'Tempat Makan & Minum Kucing Set Premium',
                'price'          => 89000,
                'original_price' => null,
                'rating'         => 4.7,
                'reviews_count'  => 56,
                'stock'          => 80,
                'sold_count'     => 143,
                'image'          => 'https://placehold.co/400x400/FDA4AF/881337?text=Bowl+Set',
                'images'         => [
                    'https://placehold.co/600x600/FDA4AF/881337?text=Bowl+1',
                ],
                'description'    => 'Set tempat makan dan minum stainless steel untuk kucing. Desain anti-slip, mudah dibersihkan, higienis. Cocok untuk semua ras kucing.',
                'tags'           => ['aksesoris', 'tempat makan', 'stainless'],
                'is_new'         => true,
                'is_promo'       => false,
            ],
            // ── Bird ─────────────────────────────────────────────────────────
            [
                'category'       => 'bird',
                'name'           => 'Pakan Burung Kenari Ebod Joss 500g',
                'price'          => 35000,
                'original_price' => null,
                'rating'         => 4.8,
                'reviews_count'  => 145,
                'stock'          => 300,
                'sold_count'     => 892,
                'image'          => 'https://placehold.co/400x400/FEF08A/713F12?text=Pakan+Kenari',
                'images'         => [
                    'https://placehold.co/600x600/FEF08A/713F12?text=Kenari+1',
                ],
                'description'    => 'Pakan premium untuk burung kenari. Campuran biji-bijian pilihan yang kaya nutrisi untuk mendukung kesehatan dan kicauan optimal burung kenari.',
                'tags'           => ['pakan', 'kenari', 'biji'],
                'is_new'         => false,
                'is_promo'       => false,
            ],
            [
                'category'       => 'bird',
                'name'           => 'Sangkar Burung Minimalis Kotak',
                'price'          => 245000,
                'original_price' => 280000,
                'rating'         => 4.6,
                'reviews_count'  => 72,
                'stock'          => 25,
                'sold_count'     => 88,
                'image'          => 'https://placehold.co/400x400/A3E635/1A2E05?text=Sangkar',
                'images'         => [
                    'https://placehold.co/600x600/A3E635/1A2E05?text=Sangkar+1',
                    'https://placehold.co/600x600/A3E635/1A2E05?text=Sangkar+2',
                ],
                'description'    => 'Sangkar burung minimalis desain modern. Bahan kawat baja anti karat, mudah dibersihkan. Dilengkapi tempat makan, minum, dan tenggeran kayu alami.',
                'tags'           => ['sangkar', 'minimalis', 'kawat'],
                'is_new'         => false,
                'is_promo'       => true,
            ],
            // ── Fish ─────────────────────────────────────────────────────────
            [
                'category'       => 'fish',
                'name'           => 'Pakan Ikan Hias TetraMin Tropical Flakes 100g',
                'price'          => 65000,
                'original_price' => null,
                'rating'         => 4.7,
                'reviews_count'  => 203,
                'stock'          => 500,
                'sold_count'     => 1200,
                'image'          => 'https://placehold.co/400x400/67E8F9/0C4A6E?text=TetraMin',
                'images'         => [
                    'https://placehold.co/600x600/67E8F9/0C4A6E?text=Tetra+1',
                ],
                'description'    => 'Pakan flakes premium untuk ikan hias tropis. Formula khusus dengan multi-vitamin untuk memperkuat warna ikan dan menjaga kesehatan optimal.',
                'tags'           => ['pakan', 'ikan hias', 'flakes'],
                'is_new'         => false,
                'is_promo'       => false,
            ],
            [
                'category'       => 'fish',
                'name'           => 'Aquarium Mini LED 30cm Set Lengkap',
                'price'          => 385000,
                'original_price' => 450000,
                'rating'         => 4.5,
                'reviews_count'  => 89,
                'stock'          => 20,
                'sold_count'     => 67,
                'image'          => 'https://placehold.co/400x400/BAE6FD/0C4A6E?text=Aquarium',
                'images'         => [
                    'https://placehold.co/600x600/BAE6FD/0C4A6E?text=Aquarium+1',
                    'https://placehold.co/600x600/BAE6FD/0C4A6E?text=Aquarium+2',
                ],
                'description'    => 'Aquarium mini lengkap dengan lampu LED, filter, pompa udara, dan substrat. Cocok untuk pemula yang ingin memulai hobi memelihara ikan hias.',
                'tags'           => ['aquarium', 'LED', 'set lengkap'],
                'is_new'         => true,
                'is_promo'       => true,
            ],
            // ── Rabbit ───────────────────────────────────────────────────────
            [
                'category'       => 'rabbit',
                'name'           => 'Pellet Kelinci Merk Supreme 1.5kg',
                'price'          => 85000,
                'original_price' => null,
                'rating'         => 4.8,
                'reviews_count'  => 56,
                'stock'          => 80,
                'sold_count'     => 145,
                'image'          => 'https://placehold.co/400x400/FBD38D/7B341E?text=Rabbit+Food',
                'images'         => [
                    'https://placehold.co/600x600/FBD38D/7B341E?text=Rabbit+1',
                ],
                'description'    => 'Pakan pellet seimbang untuk kelinci semua usia. Kaya serat, vitamin, dan mineral penting untuk kesehatan pencernaan dan bulu kelinci yang sehat.',
                'tags'           => ['pakan', 'pellet', 'kelinci'],
                'is_new'         => false,
                'is_promo'       => false,
            ],
            [
                'category'       => 'rabbit',
                'name'           => 'Kandang Kelinci Lipat Stainless 80cm',
                'price'          => 325000,
                'original_price' => 380000,
                'rating'         => 4.6,
                'reviews_count'  => 34,
                'stock'          => 15,
                'sold_count'     => 47,
                'image'          => 'https://placehold.co/400x400/D1D5DB/374151?text=Kandang+Kelinci',
                'images'         => [
                    'https://placehold.co/600x600/D1D5DB/374151?text=Kandang+1',
                ],
                'description'    => 'Kandang kelinci stainless steel dapat dilipat. Mudah dibersihkan dan dipindahkan. Dilengkapi tempat makan, minum otomatis, dan alas plastik anti bocor.',
                'tags'           => ['kandang', 'stainless', 'lipat'],
                'is_new'         => false,
                'is_promo'       => true,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->first();
            if (!$category) continue;

            $images = $data['images'];
            unset($data['images'], $data['category']);

            $data['category_id'] = $category->id;
            $data['slug']        = Str::slug($data['name']) . '-' . Str::lower(Str::random(4));
            $data['tags']        = json_encode($data['tags']);

            $product = Product::create($data);

            foreach ($images as $i => $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $url,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
