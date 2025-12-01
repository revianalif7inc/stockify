<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;
    protected $categoryService;

    public function __construct(ReportService $reportService, CategoryService $categoryService)
    {
        $this->reportService = $reportService;
        $this->categoryService = $categoryService;
    }

    /**
     * Menampilkan Laporan Stok Saat Ini
     */
    public function stockReport(Request $request)
    {
        $products = $this->reportService->getStockReport();
        $categories = $this->categoryService->getAllCategories();
        
        // Logika sederhana untuk filtering berdasarkan category_id
        if ($request->filled('category_id')) {
            $products = $products->where('category_id', $request->category_id);
        }

        return view('admin.reports.stock', compact('products', 'categories'));
    }

    /**
     * Menampilkan Laporan Barang Masuk dan Keluar (Movement)
     */
    public function movementReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $type = $request->input('type'); // 'in', 'out', atau null

        $movements = $this->reportService->getMovementReport(
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59',
            $type
        );

        return view('admin.reports.movement', compact('movements', 'startDate', 'endDate', 'type'));
    }
}