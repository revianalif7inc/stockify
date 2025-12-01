<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request, CategoryService $categoryService, SupplierService $supplierService)
    {
        // Build query with filters
        $query = \App\Models\Product::with(['category', 'supplier', 'attributeValues.attribute']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Search by name or SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('sku', 'like', "%$search%");
            });
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'critical') {
                $query->whereRaw('current_stock <= min_stock');
            } elseif ($request->stock_status === 'safe') {
                $query->whereRaw('current_stock > min_stock');
            }
        }

        // Paginate product list
        $products = $query->orderBy('name')->paginate(15);

        // Get categories and suppliers for filter dropdowns
        $categories = $categoryService->getAllCategories();
        $suppliers = $supplierService->getAllSuppliers();

        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Show product detail (including attribute values)
     */
    public function show($id)
    {
        // load product with relations including attribute values and attribute definitions
        $product = \App\Models\Product::with(['category', 'supplier', 'attributeValues.attribute'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function create(CategoryService $categoryService, SupplierService $supplierService)
    {
        $categories = $categoryService->getAllCategories();
        $suppliers = $supplierService->getAllSuppliers();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            // Explicitly include file if present
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            $this->productService->createProduct($data);
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit($id, CategoryService $categoryService, SupplierService $supplierService)
    {
        $product = $this->productService->findProduct($id);
        $categories = $categoryService->getAllCategories();
        $suppliers = $supplierService->getAllSuppliers();
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            // Explicitly include file if present
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            $this->productService->updateProduct($id, $data);
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}