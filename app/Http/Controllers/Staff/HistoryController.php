<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HistoryController extends Controller
{
    /**
     * Display confirmed stock movements history
     */
    public function index(Request $request)
    {
        $query = StockMovement::with('product', 'user')
            ->orderBy('created_at', 'desc');

        // Filter by type if provided
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Only show confirmed items if status column exists
        if (Schema::hasColumn('stock_movements', 'status')) {
            $query->where('status', 'confirmed');
        }

        $confirmed = $query->paginate(15);

        return view('staff.history', compact('confirmed'));
    }
}
