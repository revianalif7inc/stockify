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
            ->where('status', 'pending') // Hanya tampilkan yang pending
            ->orderBy('created_at', 'desc');

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
            ->where('status', 'pending') // Hanya tampilkan yang pending
            ->orderBy('created_at', 'desc');

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
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'in')->firstOrFail();
            
            // Gunakan stockService untuk approve movement
            $this->stockService->approveMovement($id);

            // create audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'approve_in',
                'auditable_type' => StockMovement::class,
                'auditable_id' => $movement->id,
                'data' => [
                    'product_id' => $movement->product_id,
                    'quantity' => $movement->quantity,
                    'notes' => $movement->notes,
                ],
            ]);

            return redirect()->route('staff.stock.in')->with('success', 'Barang masuk telah disetujui dan stok diupdate.');
        } catch (\Exception $e) {
            return redirect()->route('staff.stock.in')->with('error', 'Error: ' . $e->getMessage());
        }
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
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'out')->firstOrFail();
            
            // Gunakan stockService untuk approve movement
            $this->stockService->approveMovement($id);

            // create audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'approve_out',
                'auditable_type' => StockMovement::class,
                'auditable_id' => $movement->id,
                'data' => [
                    'product_id' => $movement->product_id,
                    'quantity' => $movement->quantity,
                    'notes' => $movement->notes,
                ],
            ]);

            return redirect()->route('staff.stock.out')->with('success', 'Barang keluar telah disetujui dan stok diupdate.');
        } catch (\Exception $e) {
            return redirect()->route('staff.stock.out')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Tolak Barang Masuk
    public function rejectIn($id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'in')->firstOrFail();
            
            // Gunakan stockService untuk reject movement
            $this->stockService->rejectMovement($id);

            // create audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'reject_in',
                'auditable_type' => StockMovement::class,
                'auditable_id' => $movement->id,
                'data' => [
                    'product_id' => $movement->product_id,
                    'quantity' => $movement->quantity,
                    'notes' => $movement->notes,
                ],
            ]);

            return redirect()->route('staff.stock.in')->with('success', 'Barang masuk telah ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('staff.stock.in')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Tolak Barang Keluar
    public function rejectOut($id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'out')->firstOrFail();
            
            // Gunakan stockService untuk reject movement
            $this->stockService->rejectMovement($id);

            // create audit log
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'reject_out',
                'auditable_type' => StockMovement::class,
                'auditable_id' => $movement->id,
                'data' => [
                    'product_id' => $movement->product_id,
                    'quantity' => $movement->quantity,
                    'notes' => $movement->notes,
                ],
            ]);

            return redirect()->route('staff.stock.out')->with('success', 'Barang keluar telah ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('staff.stock.out')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
