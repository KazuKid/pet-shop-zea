<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * GET /api/admin/stats
     */
    public function stats()
    {
        $totalRevenue = (int) Order::whereNotIn('status', ['cancelled'])->sum('total');

        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentOrders = Order::with('items')
            ->latest()
            ->limit(5)
            ->get();

        $revenueByMonth = Order::whereNotIn('status', ['cancelled'])
            ->selectRaw(
                "TO_CHAR(created_at, 'Mon') as month, " .
                "EXTRACT(MONTH FROM created_at) as month_num, " .
                "SUM(total) as revenue"
            )
            ->whereRaw("created_at >= NOW() - INTERVAL '6 months'")
            ->groupByRaw(
                "TO_CHAR(created_at, 'Mon'), " .
                "EXTRACT(MONTH FROM created_at)"
            )
            ->orderByRaw('EXTRACT(MONTH FROM created_at)')
            ->get();

        return response()->json([
            'total_products'   => Product::count(),
            'total_categories' => Category::count(),
            'total_orders'     => Order::count(),
            'total_revenue'    => $totalRevenue,
            'total_customers'  => User::where('role', 'customer')->count(),
            'orders_by_status' => $ordersByStatus,
            'recent_orders'    => $recentOrders,
            'revenue_by_month' => $revenueByMonth,
        ]);
    }
}
