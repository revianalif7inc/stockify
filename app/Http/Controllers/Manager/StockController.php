<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use App\Services\ProductService; // Untuk dropdown produk
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;


class StockController extends Controller
{
    protected $stockService;
    protected $productService;
    protected $supplierService;

    public function __construct(
        StockService $stockService,
        ProductService $productService,
        SupplierService $supplierService
    ) {
        $this->stockService = $stockService;
        $this->productService = $productService;
        $this->supplierService = $supplierService;
    }

    // Monitoring Stok
    public function monitoring(Request $request)
    {
        $query = Product::with('category', 'supplier');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'critical') {
                $query->whereRaw('current_stock <= min_stock');
            } elseif ($request->status === 'safe') {
                $query->whereRaw('current_stock > min_stock');
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();
        return view('manager.stock.monitoring', compact('products'));
    }

    // Tampilan Form Barang Masuk
    public function indexIn()
    {
        $products = $this->productService->getAllProducts();
        $suppliers = $this->supplierService->getAllSuppliers();
        return view('manager.stock.in', compact('products', 'suppliers'));
    }

    // Proses Penyimpanan Barang Masuk
    public function storeIn(Request $request)
    {
        try {
            $data = $request->only(['product_id', 'quantity', 'notes']);
            $data['user_id'] = Auth::id(); // Ambil ID user yang login

            $this->stockService->processStockIn($data);
            return redirect()->route('manager.stock.in')->with('success', 'Barang Masuk berhasil dicatat dan stok diupdate.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal mencatat Barang Masuk: ' . $e->getMessage());
        }
    }

    // Tampilan Form Barang Keluar
    public function indexOut()
    {
        $products = $this->productService->getAllProducts();
        return view('manager.stock.out', compact('products'));
    }

    // Proses Penyimpanan Barang Keluar
    public function storeOut(Request $request)
    {
        try {
            $data = $request->only(['product_id', 'quantity', 'notes']);
            $data['user_id'] = Auth::id();

            $this->stockService->processStockOut($data);
            return redirect()->route('manager.stock.out')->with('success', 'Barang Keluar berhasil dicatat dan stok diupdate.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal mencatat Barang Keluar: ' . $e->getMessage());
        }
    }

    // Edit Barang Masuk
    public function editIn($id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'in')->firstOrFail();

            // Always return JSON for this endpoint
            return response()->json($movement);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    // Update Barang Masuk
    public function updateIn(Request $request, $id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'in')->firstOrFail();

            $data = $request->only(['product_id', 'quantity', 'notes']);
            $data['user_id'] = Auth::id();

            $this->stockService->updateMovement($id, $data);
            return redirect()->route('manager.stock.in')->with('success', 'Barang Masuk berhasil diupdate.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupdate Barang Masuk: ' . $e->getMessage());
        }
    }

    // Delete Barang Masuk
    public function destroyIn($id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'in')->firstOrFail();

            $this->stockService->deleteMovement($id);
            return redirect()->route('manager.stock.in')->with('success', 'Barang Masuk berhasil dihapus dan stok direvert.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus Barang Masuk: ' . $e->getMessage());
        }
    }

    // Edit Barang Keluar
    public function editOut($id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'out')->firstOrFail();

            // Always return JSON for this endpoint
            return response()->json($movement);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    // Update Barang Keluar
    public function updateOut(Request $request, $id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'out')->firstOrFail();

            $data = $request->only(['product_id', 'quantity', 'notes']);
            $data['user_id'] = Auth::id();

            $this->stockService->updateMovement($id, $data);
            return redirect()->route('manager.stock.out')->with('success', 'Barang Keluar berhasil diupdate.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupdate Barang Keluar: ' . $e->getMessage());
        }
    }

    // Delete Barang Keluar
    public function destroyOut($id)
    {
        try {
            $movement = StockMovement::where('id', $id)->where('type', 'out')->firstOrFail();

            $this->stockService->deleteMovement($id);
            return redirect()->route('manager.stock.out')->with('success', 'Barang Keluar berhasil dihapus dan stok direvert.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus Barang Keluar: ' . $e->getMessage());
        }
    }

    // Stock Opname (placeholder)
    public function opnameIndex()
    {
        // For now, show a simple view where manager can perform opname
        return view('manager.stock.opname');
    }
}