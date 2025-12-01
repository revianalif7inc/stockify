<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function allWithRelations()
    {
        // Also eager-load attribute values with their attribute definition to avoid N+1 queries
        return Product::with(['category', 'supplier', 'attributeValues.attribute'])->get();
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $this->find($id)->delete();
    }
}