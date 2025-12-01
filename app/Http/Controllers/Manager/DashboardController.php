<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Manager
     */
    public function index()
    {
        // Total produk aktif
        $totalProducts = Product::count();

        // Total stok keseluruhan
        $totalStock = Product::sum('current_stock');

        // Barang masuk bulan ini
        $stockInThisMonth = StockMovement::where('type', 'in')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        // Barang keluar bulan ini
        $stockOutThisMonth = StockMovement::where('type', 'out')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        // Produk dengan stok kritis (stok <= min_stock)
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')->count();

        // Daftar produk dengan stok kritis (untuk ditampilkan)
        $lowStockProductsList = Product::whereColumn('current_stock', '<=', 'min_stock')
            ->orderBy('current_stock')
            ->limit(5)
            ->get();

        // Produk dengan stok aman
        $safeStockProducts = Product::whereRaw('current_stock > min_stock')->count();

        // Total kategori
        $totalCategories = Category::count();

        return view('manager.dashboard', compact(
            'totalProducts',
            'totalStock',
            'stockInThisMonth',
            'stockOutThisMonth',
            'lowStockProducts',
            'lowStockProductsList',
            'safeStockProducts',
            'totalCategories'
        ));
    }
}
