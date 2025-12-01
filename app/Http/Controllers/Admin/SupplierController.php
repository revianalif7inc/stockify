<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Exception;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $this->supplierService->createSupplier($request->all());
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $this->supplierService->updateSupplier($id, $request->all());
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui supplier: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $this->supplierService->deleteSupplier($id);
            return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }
}