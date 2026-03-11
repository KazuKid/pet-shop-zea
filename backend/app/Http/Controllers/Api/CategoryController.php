<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('id')->get();
        return response()->json($categories);
    }

    /**
     * GET /api/categories/{slug}/products
     */
    public function products(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->with(['category', 'images'])->paginate(12);
        return response()->json($products);
    }

    /**
     * POST /api/admin/categories  (auth:sanctum)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:10',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $category = Category::create($data);

        return response()->json($category->loadCount('products'), 201);
    }

    /**
     * PUT /api/admin/categories/{id}  (auth:sanctum)
     */
    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:100|unique:categories,name,' . $id,
            'icon' => 'nullable|string|max:10',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return response()->json($category->loadCount('products'));
    }

    /**
     * DELETE /api/admin/categories/{id}  (auth:sanctum)
     */
    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Kategori ini masih memiliki produk. Pindahkan produk terlebih dahulu.',
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
