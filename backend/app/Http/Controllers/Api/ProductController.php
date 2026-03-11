<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * GET /api/products
     * Query params: category, search, sort, per_page, page
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Filter by category slug
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', fn ($q) =>
                $q->where('slug', $request->category)
            );
        }

        // Full-text search across name, description, tags
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'ilike', "%{$term}%")
                  ->orWhere('description', 'ilike', "%{$term}%")
                  ->orWhereRaw("tags::text ilike ?", ["%{$term}%"]);
            });
        }

        // Filter promos / new arrivals
        if ($request->boolean('promo')) {
            $query->where('is_promo', true);
        }
        if ($request->boolean('new')) {
            $query->where('is_new', true);
        }

        // Sorting
        match ($request->sort) {
            'price-asc'  => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            'rating'     => $query->orderByDesc('rating'),
            'popular'    => $query->orderByDesc('sold_count'),
            default      => $query->orderBy('id'),
        };

        $perPage  = min((int) $request->input('per_page', 12), 50);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * GET /api/products/{id}
     */
    public function show(int $id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        return response()->json($product);
    }

    /**
     * POST /api/admin/products  (auth:sanctum)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'price'          => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'stock'          => 'required|integer|min:0',
            'image'          => 'required|string',
            'description'    => 'nullable|string',
            'tags'           => 'nullable|array',
            'is_new'         => 'boolean',
            'is_promo'       => 'boolean',
            'images'         => 'nullable|array',
            'images.*'       => 'string',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        $product = Product::create($data);

        if (!empty($data['images'])) {
            foreach ($data['images'] as $i => $url) {
                $product->images()->create(['image_url' => $url, 'sort_order' => $i]);
            }
        }

        return response()->json($product->load(['category', 'images']), 201);
    }

    /**
     * PUT /api/admin/products/{id}  (auth:sanctum)
     */
    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'category_id'    => 'sometimes|exists:categories,id',
            'name'           => 'sometimes|string|max:255',
            'price'          => 'sometimes|integer|min:0',
            'original_price' => 'nullable|integer|min:0',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'stock'          => 'sometimes|integer|min:0',
            'image'          => 'sometimes|string',
            'description'    => 'nullable|string',
            'tags'           => 'nullable|array',
            'is_new'         => 'boolean',
            'is_promo'       => 'boolean',
            'images'         => 'nullable|array',
            'images.*'       => 'string',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        }

        $product->update($data);

        if (array_key_exists('images', $data)) {
            $product->images()->delete();
            foreach ($data['images'] as $i => $url) {
                $product->images()->create(['image_url' => $url, 'sort_order' => $i]);
            }
        }

        return response()->json($product->fresh(['category', 'images']));
    }

    /**
     * DELETE /api/admin/products/{id}  (auth:sanctum)
     */
    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
