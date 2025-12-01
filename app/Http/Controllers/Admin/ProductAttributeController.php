<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Services\ProductAttributeService;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    protected $attributeService;

    public function __construct(ProductAttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    /**
     * Tampilkan halaman manajemen atribut kategori
     */
    public function index($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $attributes = $this->attributeService->getAttributesByCategory($categoryId);

        return view('admin.product-attributes.index', compact('category', 'attributes'));
    }

    /**
     * Return attributes as JSON (used by product forms)
     */
    public function json($categoryId)
    {
        $attributes = $this->attributeService->getAttributesByCategory($categoryId);
        return response()->json($attributes);
    }

    /**
     * Return single attribute as JSON (for edit modal)
     */
    public function show($categoryId, $attributeId)
    {
        $attribute = ProductAttribute::where('category_id', $categoryId)->findOrFail($attributeId);
        return response()->json($attribute);
    }

    /**
     * Simpan atribut baru
     */
    public function store(Request $request, $categoryId)
    {
        try {
            $data = $request->all();

            // Convert options_text to array if type is select
            if ($data['type'] === 'select' && !empty($data['options_text'])) {
                $options = array_map('trim', explode(',', $data['options_text']));
                $data['options'] = $options;
            }
            unset($data['options_text']);

            $this->attributeService->createAttribute($categoryId, $data);

            return redirect()->route('admin.product-attributes.index', $categoryId)
                ->with('success', 'Atribut produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update atribut
     */
    public function update(Request $request, $categoryId, $attributeId)
    {
        try {
            $data = $request->all();
            // Convert options_text to array if type is select
            if (isset($data['type']) && $data['type'] === 'select' && !empty($data['options_text'])) {
                $options = array_map('trim', explode(',', $data['options_text']));
                $data['options'] = $options;
            }
            unset($data['options_text']);

            $this->attributeService->updateAttribute($attributeId, $data);

            return redirect()->route('admin.product-attributes.index', $categoryId)
                ->with('success', 'Atribut produk berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus atribut
     */
    public function destroy($categoryId, $attributeId)
    {
        try {
            $this->attributeService->deleteAttribute($attributeId);

            return redirect()->route('admin.product-attributes.index', $categoryId)
                ->with('success', 'Atribut produk berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reorder atribut
     */
    public function reorder(Request $request, $categoryId)
    {
        try {
            $this->attributeService->reorderAttributes($categoryId, $request->orders);

            return response()->json(['success' => true, 'message' => 'Urutan atribut berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
