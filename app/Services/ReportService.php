<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Mengambil Laporan Stok Barang saat ini, dikelompokkan berdasarkan kategori.
     */
    public function getStockReport()
    {
        return Product::with('category', 'supplier')
            ->select('products.*')
            ->orderBy('category_id')
            ->get();
    }

    /**
     * Mengambil Laporan Pergerakan Stok (Barang Masuk/Keluar) dalam periode tertentu.
     */
    public function getMovementReport($startDate, $endDate, $type = null)
    {
        $query = StockMovement::with(['product', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($type && in_array($type, ['in', 'out'])) {
            $query->where('type', $type);
        }

        return $query->latest()->get();
    }
}