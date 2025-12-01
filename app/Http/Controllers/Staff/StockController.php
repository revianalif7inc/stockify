<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    // Overview page listing products + quick actions
    public function index()
    {
        $products = Product::with('category', 'supplier')->get();
        return view('staff.stock.index', compact('products'));
    }

    // Show Barang Masuk page (Pending confirmations)
    public function indexIn()
    {
        $query = StockMovement::with('product', 'user')
            ->where('type', 'in')
            ->orderBy('created_at', 'desc');

        // Only show unconfirmed items if status column exists
        if (Schema::hasColumn('stock_movements', 'status')) {
            $query->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'confirmed');
            });
        }

        $products = $query->get();
        return view('staff.stock.in', compact('products'));
    }

    // Store Barang Masuk - DISABLED (staff tidak berhak mencatat)
    public function storeIn(Request $request)
    {
        return redirect()->route('staff.dashboard')->with('error', 'Staff tidak memiliki akses untuk mencatat barang masuk.');
    }

    // Show Barang Keluar page (Pending confirmations)
    public function indexOut()
    {
        $query = StockMovement::with('product', 'user')
            ->where('type', 'out')
            ->orderBy('created_at', 'desc');

        // Only show unconfirmed items if status column exists
        if (Schema::hasColumn('stock_movements', 'status')) {
            $query->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'confirmed');
            });
        }

        $products = $query->get();
        return view('staff.stock.out', compact('products'));
    }

    // Store Barang Keluar - DISABLED (staff tidak berhak mencatat)
    public function storeOut(Request $request)
    {
        return redirect()->route('staff.dashboard')->with('error', 'Staff tidak memiliki akses untuk mencatat barang keluar.');
    }

    // Konfirmasi Barang Masuk (GET)
    public function confirmIn($id)
    {
        $movement = StockMovement::with('product.supplier')->where('id', $id)->where('type', 'in')->firstOrFail();
        return view('staff.stock.in_confirm', compact('movement'));
    }

    // Konfirmasi Barang Masuk (POST)
    public function doConfirmIn($id)
    {
        $movement = StockMovement::where('id', $id)->where('type', 'in')->firstOrFail();
        if (Schema::hasColumn('stock_movements', 'status')) {
            $movement->status = 'confirmed';
        }
        $movement->save();

        // create audit log
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'confirm_in',
                'auditable_type' => StockMovement::class,
                'auditable_id' => $movement->id,
                'data' => [
                    'product_id' => $movement->product_id,
                    'quantity' => $movement->quantity,
                    'notes' => $movement->notes,
                ],
            ]);
        } catch (\Exception $e) {
            // Don't block user flow if audit logging fails; optionally log the error
            \Log::warning('Failed to write audit log for confirmIn: ' . $e->getMessage());
        }

        return redirect()->route('staff.dashboard')->with('success', 'Barang masuk telah dikonfirmasi.');
    }

    // Konfirmasi Barang Keluar (GET)
    public function confirmOut($id)
    {
        $movement = StockMovement::with('product.supplier')->where('id', $id)->where('type', 'out')->firstOrFail();
        return view('staff.stock.out_confirm', compact('movement'));
    }

    // Konfirmasi Barang Keluar (POST)
    public function doConfirmOut($id)
    {
        $movement = StockMovement::where('id', $id)->where('type', 'out')->firstOrFail();
        if (Schema::hasColumn('stock_movements', 'status')) {
            $movement->status = 'confirmed';
        }
        $movement->save();

        // create audit log
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'confirm_out',
                'auditable_type' => StockMovement::class,
                'auditable_id' => $movement->id,
                'data' => [
                    'product_id' => $movement->product_id,
                    'quantity' => $movement->quantity,
                    'notes' => $movement->notes,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to write audit log for confirmOut: ' . $e->getMessage());
        }

        return redirect()->route('staff.dashboard')->with('success', 'Barang keluar telah dikonfirmasi.');
    }
}
