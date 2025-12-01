<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display staff dashboard
     */
    public function index()
    {
        $today = now()->toDateString();

        // Count pending barang masuk (today)
        $incomingPendingQuery = StockMovement::where('type', 'in')
            ->where('status', 'pending') // Hanya pending
            ->whereDate('created_at', $today);

        // Count pending barang keluar (today)
        $outgoingPendingQuery = StockMovement::where('type', 'out')
            ->where('status', 'pending') // Hanya pending
            ->whereDate('created_at', $today);

        $incomingPending = $incomingPendingQuery->count();
        $outgoingPending = $outgoingPendingQuery->count();

        // Count approved items (today)
        $confirmedQuery = StockMovement::whereDate('created_at', $today)
            ->where('status', 'approved'); // Approved items
        $confirmedToday = $confirmedQuery->count();

        // Staff task list: barang masuk/keluar hari ini yang pending
        $incomingQuery = StockMovement::with('product', 'user')
            ->where('type', 'in')
            ->where('status', 'pending') // Hanya pending
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc');
        $outgoingQuery = StockMovement::with('product', 'user')
            ->where('type', 'out')
            ->where('status', 'pending') // Hanya pending
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc');

        $incoming = $incomingQuery->get();
        $outgoing = $outgoingQuery->get();
        $tasks = [];
        foreach ($incoming as $in) {
            $tasks[] = [
                'id' => $in->id,
                'title' => 'Periksa barang masuk: ' . ($in->product->name ?? 'Produk'),
                'status' => $in->status ?? 'pending',
                'type' => 'in',
                'movement' => $in,
            ];
        }
        foreach ($outgoing as $out) {
            $tasks[] = [
                'id' => $out->id,
                'title' => 'Siapkan barang keluar: ' . ($out->product->name ?? 'Produk'),
                'status' => $out->status ?? 'pending',
                'type' => 'out',
                'movement' => $out,
            ];
        }
        $tasks = array_slice($tasks, 0, 5); // Show up to 5 tasks on dashboard

        // Additional metrics for consistency with manager dashboard
        $totalProducts = Product::count();
        $totalStock = Product::sum('current_stock');
        // low stock products (where min_stock column exists)
        $lowStockProducts = 0;
        $lowStockProductsList = [];
        if (Schema::hasColumn('products', 'min_stock')) {
            $lowStockProductsList = Product::whereColumn('current_stock', '<=', 'min_stock')->get();
            $lowStockProducts = $lowStockProductsList->count();
        }

        return view('staff.dashboard', compact(
            'incomingPending',
            'outgoingPending',
            'confirmedToday',
            'tasks',
            'incoming',
            'outgoing',
            'totalProducts',
            'totalStock',
            'lowStockProducts',
            'lowStockProductsList'
        ));
    }
}
