<?php

namespace App\Services;

use App\Models\ProductAttribute;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductAttributeService
{
    /**
     * Ambil semua atribut dari kategori tertentu
     */
    public function getAttributesByCategory($categoryId)
    {
        return ProductAttribute::where('category_id', $categoryId)
            ->orderBy('order')
            ->get();
    }

    /**
     * Buat atribut baru untuk kategori
     */
    public function createAttribute($categoryId, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:text,select,number',
            'options' => 'nullable|array',
            'is_required' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        // Proses options untuk tipe select
        $options = null;
        if ($data['type'] === 'select' && !empty($data['options'])) {
            $options = $data['options'];
        }

        return ProductAttribute::create([
            'category_id' => $categoryId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'options' => $options,
            'is_required' => $data['is_required'] ?? false,
            'order' => $data['order'] ?? 0
        ]);
    }

    /**
     * Update atribut
     */
    public function updateAttribute($attributeId, array $data)
    {
        $attribute = ProductAttribute::findOrFail($attributeId);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:text,select,number',
            'options' => 'nullable|array',
            'is_required' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $options = null;
        if ($data['type'] === 'select' && !empty($data['options'])) {
            $options = $data['options'];
        }

        $attribute->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? $attribute->description,
            'type' => $data['type'],
            'options' => $options,
            'is_required' => $data['is_required'] ?? false,
            'order' => $data['order'] ?? $attribute->order
        ]);

        return $attribute;
    }

    /**
     * Hapus atribut
     */
    public function deleteAttribute($attributeId)
    {
        $attribute = ProductAttribute::findOrFail($attributeId);
        $attribute->delete();
    }

    /**
     * Reorder atribut
     */
    public function reorderAttributes($categoryId, array $orders)
    {
        foreach ($orders as $index => $attributeId) {
            ProductAttribute::where('id', $attributeId)
                ->where('category_id', $categoryId)
                ->update(['order' => $index]);
        }
    }
}
