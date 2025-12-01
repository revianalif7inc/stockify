<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Show stock report for manager
    public function stock(Request $request)
    {
        $categories = Category::all();

        $products = Product::with(['category', 'supplier'])
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->get();

        return view('manager.reports.stock', compact('products', 'categories'));
    }

    // Show movement report for manager
    public function movement(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = StockMovement::with('product')
            ->whereBetween('created_at', [$start, $end]);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->orderBy('created_at', 'desc')->get();

        $summaryIn = $movements->where('type', 'in')->sum('quantity');
        $summaryOut = $movements->where('type', 'out')->sum('quantity');

        return view('manager.reports.movement', compact('movements', 'summaryIn', 'summaryOut', 'start', 'end'));
    }
}
