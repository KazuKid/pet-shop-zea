<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * GET /api/admin/orders  (auth:sanctum)
     */
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(20));
    }

    /**
     * POST /api/orders  (guest checkout)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',
            'city'            => 'required|string|max:100',
            'province'        => 'required|string|max:100',
            'postal_code'     => 'required|string|max:10',
            'shipping_method' => 'required|in:jne,jnt,sicepat,gosend',
            'payment_method'  => 'required|in:transfer,gopay,ovo,dana,cod',
            'notes'           => 'nullable|string|max:500',
            'items'           => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        // Calculate totals & validate stock
        $orderItems = [];
        $subtotal   = 0;

        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['qty']) {
                return response()->json([
                    'message' => "Stok {$product->name} tidak mencukupi.",
                    'errors'  => ['items' => ["Stok {$product->name} hanya tersisa {$product->stock}."]],
                ], 422);
            }

            $lineTotal    = $product->price * $item['qty'];
            $subtotal    += $lineTotal;

            $orderItems[] = [
                'product_id'    => $product->id,
                'product_name'  => $product->name,
                'product_image' => $product->image,
                'price'         => $product->price,
                'qty'           => $item['qty'],
                'total'         => $lineTotal,
            ];
        }

        $shippingCost = $subtotal >= 200000 ? 0 : 25000;
        $total        = $subtotal + $shippingCost;

        $order = Order::create([
            'user_id'         => auth()->id(),
            'order_number'    => 'ZEA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'status'          => 'pending',
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'address'         => $data['address'],
            'city'            => $data['city'],
            'province'        => $data['province'],
            'postal_code'     => $data['postal_code'],
            'shipping_method' => $data['shipping_method'],
            'payment_method'  => $data['payment_method'],
            'notes'           => $data['notes'] ?? null,
            'subtotal'        => $subtotal,
            'shipping_cost'   => $shippingCost,
            'total'           => $total,
        ]);

        $order->items()->createMany($orderItems);

        // Decrement stock
        foreach ($data['items'] as $item) {
            Product::where('id', $item['product_id'])
                   ->decrement('stock', $item['qty']);
            Product::where('id', $item['product_id'])
                   ->increment('sold_count', $item['qty']);
        }

        return response()->json([
            'message'      => 'Pesanan berhasil dibuat!',
            'order_number' => $order->order_number,
            'order'        => $order->load('items'),
        ], 201);
    }

    /**
     * GET /api/orders/{orderNumber}  (order tracking)
     */
    public function show(string $orderNumber)
    {
        $order = Order::with('items')
                      ->where('order_number', $orderNumber)
                      ->firstOrFail();

        return response()->json($order);
    }

    /**
     * PUT /api/admin/orders/{id}/status  (auth:sanctum)
     */
    public function updateStatus(Request $request, int $id)
    {
        $data  = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update($data);

        return response()->json(['message' => 'Status pesanan diperbarui.', 'order' => $order]);
    }
}
