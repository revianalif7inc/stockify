<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->allWithRelations();
    }
    public function findProduct($id)
    {
        return $this->productRepository->find($id);
    }

    protected function validateProduct(array $data, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            // Kritis: Harga jual harus >= harga beli
            'selling_price' => 'required|numeric|min:0|gte:purchase_price',
            'current_stock' => 'nullable|integer|min:0',
            // Image validation: nullable, and if provided must be image type
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }

    protected function handleImageUpload($file)
    {
        if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
            try {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('products', $filename, 'public');
                return $filename;
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                throw new \Exception('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }
        return null;
    }

    public function createProduct(array $data)
    {
        // Validate (image validation will be skipped if no file)
        $this->validateProduct($data);
        $data['current_stock'] = $data['current_stock'] ?? 0;

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $this->handleImageUpload($data['image']);
        } else {
            unset($data['image']);
        }

        // Create product
        $product = $this->productRepository->create($data);

        // Handle attribute values if provided: expect $data['attributes'] = [attribute_id => value]
        if (!empty($data['attributes']) && is_array($data['attributes'])) {
            foreach ($data['attributes'] as $attributeId => $value) {
                if ($value === null || $value === '')
                    continue;
                \App\Models\ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attributeId,
                    'value' => is_array($value) ? json_encode($value) : (string) $value,
                ]);
            }
        }

        return $product;
    }

    public function updateProduct($id, array $data)
    {
        // Validate only fields that should be updated (exclude image from validation temporarily)
        $imageData = $data['image'] ?? null;
        unset($data['image']);

        $this->validateProduct($data, $id);

        // Handle image upload separately
        if ($imageData && is_object($imageData) && get_class($imageData) === 'Illuminate\Http\UploadedFile') {
            $product = $this->productRepository->find($id);
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            $data['image'] = $this->handleImageUpload($imageData);
        }

        $product = $this->productRepository->update($id, $data);

        // Update attribute values: replace existing values for provided attributes
        if (isset($data['attributes']) && is_array($data['attributes'])) {
            foreach ($data['attributes'] as $attributeId => $value) {
                // delete existing
                \App\Models\ProductAttributeValue::where('product_id', $product->id)
                    ->where('attribute_id', $attributeId)
                    ->delete();

                if ($value === null || $value === '')
                    continue;

                \App\Models\ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attributeId,
                    'value' => is_array($value) ? json_encode($value) : (string) $value,
                ]);
            }
        }

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->find($id);
        // Delete image jika ada
        if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
            Storage::disk('public')->delete('products/' . $product->image);
        }
        $this->productRepository->delete($id);
    }
}