<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk untuk manager
     */
    public function index(Request $request)
    {
        // Get categories and suppliers for filter dropdowns
        $categories = \App\Models\Category::orderBy('name')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        // Include attribute values and their attribute definitions to display attributes in lists
        $query = Product::with('category', 'supplier', 'attributeValues.attribute');

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

        $products = $query->orderBy('name')->paginate(15);
        return view('manager.products', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Menampilkan produk dengan stok kritis
     */
    public function lowStock()
    {
        $products = Product::whereColumn('current_stock', '<=', 'min_stock')
            ->with('category', 'supplier', 'attributeValues.attribute')
            ->orderBy('current_stock')
            ->get();

        return view('manager.low_stock', compact('products'));
    }

    /**
     * Menampilkan halaman edit produk untuk manager
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();

        return view('manager.products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Show product detail for manager
     */
    public function show($id)
    {
        $product = Product::with(['category', 'supplier', 'attributeValues.attribute'])->findOrFail($id);
        return view('manager.products.show', compact('product'));
    }

    /**
     * Update produk (min_stock, supplier, category, image)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_stock' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            // Accept common image types; use file+mimes to avoid strict image mime checks on some systems
            'image' => 'nullable|sometimes|file|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->only(['name', 'description', 'min_stock', 'category_id', 'supplier_id']);

        // Handle image upload (defensive checks)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                Storage::disk('public')->delete('products/' . $product->image);
            }

            // Build filename and store
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
            $file->storeAs('products', $filename, 'public');
            $data['image'] = $filename;
        }

        $product->update($data);

        return redirect()->route('manager.stock.monitoring')
            ->with('success', 'Produk berhasil diupdate');
    }
}
