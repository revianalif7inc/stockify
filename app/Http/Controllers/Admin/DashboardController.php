<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\StockMovement;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $userService;
    protected $productService;
    protected $categoryService;

    public function __construct(
        UserService $userService,
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->userService = $userService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Tampilkan dashboard Admin dengan statistik utama.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            // Get statistics
            $totalUsers = User::count();
            $totalProducts = Product::count();
            $totalCategories = Category::count();

            // Get low stock products (stok <= min_stock)
            $lowStockProducts = Product::whereRaw('current_stock <= min_stock')->count();

            // Get recent products (last 5)
            $recentProducts = Product::with('category')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Prepare category sales/stock chart (all categories ranked by sales, top 8 shown)
            // Use 30-day period to ensure sufficient data
            $periodStart = Carbon::now()->subDays(30)->startOfDay();
            $periodEnd = Carbon::now()->endOfDay();

            // Get all categories with their aggregated sales and stock
            $allCategories = Category::get();
            $categoryStats = [];

            foreach ($allCategories as $cat) {
                // Total sales for this category in 30-day period
                $sales = StockMovement::join('products', 'stock_movements.product_id', '=', 'products.id')
                    ->where('stock_movements.type', 'out')
                    ->where('products.category_id', $cat->id)
                    ->whereBetween('stock_movements.created_at', [$periodStart, $periodEnd])
                    ->selectRaw('SUM(stock_movements.quantity * products.selling_price) as total')
                    ->value('total') ?: 0;

                // Total stock for this category
                $stock = Product::where('category_id', $cat->id)->sum('current_stock');

                if ((float) $sales > 0 || (int) $stock > 0) {
                    $categoryStats[] = (object) [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'sales' => (float) $sales,
                        'stock' => (int) $stock,
                    ];
                }
            }

            // Sort by sales descending
            usort($categoryStats, function ($a, $b) {
                return $b->sales <=> $a->sales;
            });

            // Take top 8
            $topCategories = array_slice($categoryStats, 0, 8);

            // If still empty, show all categories regardless of sales/stock
            if (empty($topCategories)) {
                $topCategories = [];
                foreach ($allCategories as $cat) {
                    $topCategories[] = (object) [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'sales' => 0,
                        'stock' => (int) Product::where('category_id', $cat->id)->sum('current_stock'),
                    ];
                }
                $topCategories = array_slice($topCategories, 0, 8);
            }

            // Build chart arrays
            $productChartLabels = array_map(function ($c) {
                return strlen($c->name) > 24 ? substr($c->name, 0, 21) . '...' : $c->name;
            }, $topCategories);

            $productChartData = array_map(function ($c) {
                return $c->stock;
            }, $topCategories);

            $productChartSales = array_map(function ($c) {
                return $c->sales;
            }, $topCategories);

            // Transactions in/out counts for the last 7 days
            $periodStart = Carbon::now()->subDays(7)->startOfDay();
            $periodEnd = Carbon::now()->endOfDay();

            // productChartSales already computed from category aggregation above

            // Total sales amount in the period (all products)
            $totalSales = StockMovement::join('products', 'stock_movements.product_id', '=', 'products.id')
                ->where('stock_movements.type', 'out')
                ->whereBetween('stock_movements.created_at', [$periodStart, $periodEnd])
                ->selectRaw('SUM(stock_movements.quantity * products.selling_price) as total')
                ->value('total') ?: 0;

            // Top-selling products by quantity in the period (7 days)
            $topSellingRaw = StockMovement::where('type', 'out')
                ->whereBetween('created_at', [$periodStart, $periodEnd])
                ->selectRaw('product_id, SUM(quantity) as total_qty')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();

            // Build array mapping product_id to total quantity sold
            $topSelling = [];
            foreach ($topSellingRaw as $item) {
                $topSelling[$item->product_id] = $item->total_qty;
            }

            // Get product details for top-selling products
            $topSellingProductIds = array_keys($topSelling);
            $topSellingProducts = Product::whereIn('id', $topSellingProductIds)
                ->with('category')
                ->get()
                ->keyBy('id');

            // Reorder to match sales ranking
            $topSellingProductsOrdered = [];
            foreach ($topSellingProductIds as $id) {
                if (isset($topSellingProducts[$id])) {
                    $topSellingProductsOrdered[] = $topSellingProducts[$id];
                }
            }
            $topSellingProducts = $topSellingProductsOrdered;

            // Transactions in/out counts for the last 7 days
            $periodStart = Carbon::now()->subDays(7)->startOfDay();
            $periodEnd = Carbon::now()->endOfDay();
            $totalIn = StockMovement::where('type', 'in')
                ->whereBetween('created_at', [$periodStart, $periodEnd])->count();
            $totalOut = StockMovement::where('type', 'out')
                ->whereBetween('created_at', [$periodStart, $periodEnd])->count();

            // Daily sales data for last 30 days
            $dailySalesLabels = [];
            $dailySalesData = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->startOfDay();
                $dailySalesLabels[] = $date->format('d M');
                $sales = StockMovement::join('products', 'stock_movements.product_id', '=', 'products.id')
                    ->where('stock_movements.type', 'out')
                    ->whereBetween('stock_movements.created_at', [$date, $date->copy()->endOfDay()])
                    ->selectRaw('SUM(stock_movements.quantity * products.selling_price) as total')
                    ->value('total') ?: 0;
                $dailySalesData[] = (float) $sales;
            }

            // Monthly sales data for last 12 months
            $monthlySalesLabels = [];
            $monthlySalesData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i)->startOfMonth();
                $monthlySalesLabels[] = $date->format('M Y');
                $sales = StockMovement::join('products', 'stock_movements.product_id', '=', 'products.id')
                    ->where('stock_movements.type', 'out')
                    ->whereBetween('stock_movements.created_at', [$date, $date->copy()->endOfMonth()])
                    ->selectRaw('SUM(stock_movements.quantity * products.selling_price) as total')
                    ->value('total') ?: 0;
                $monthlySalesData[] = (float) $sales;
            }

            // Recent user activity from audit logs
            $recentActivitiesRaw = AuditLog::orderBy('created_at', 'desc')->limit(10)->get();
            $userIds = $recentActivitiesRaw->pluck('user_id')->filter()->unique()->toArray();
            $users = User::whereIn('id', $userIds)->get()->keyBy('id');
            $recentActivities = $recentActivitiesRaw->map(function ($a) use ($users) {
                return (object) [
                    'id' => $a->id,
                    'user_name' => $a->user_id && isset($users[$a->user_id]) ? $users[$a->user_id]->name : 'System',
                    'action' => $a->action,
                    'auditable' => $a->auditable_type ? class_basename($a->auditable_type) . "#{$a->auditable_id}" : '',
                    'created_at' => $a->created_at,
                ];
            })->toArray();

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalProducts',
                'totalCategories',
                'lowStockProducts',
                'recentProducts',
                'productChartLabels',
                'productChartData',
                'productChartSales',
                'totalSales',
                'totalIn',
                'totalOut',
                'periodStart',
                'periodEnd',
                'topSellingProducts',
                'topSelling',
                'recentActivities',
                'dailySalesLabels',
                'dailySalesData',
                'monthlySalesLabels',
                'monthlySalesData'
            ));

        } catch (\Exception $e) {
            // Log error
            \Log::error('Gagal memuat Admin Dashboard: ' . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat memuat dashboard. Silakan coba lagi.');
        }
    }
}