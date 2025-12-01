<?php

namespace App\Repositories;

use App\Interfaces\SupplierRepositoryInterface;
use App\Models\Supplier;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all() { return Supplier::all(); }
    public function find($id) { return Supplier::findOrFail($id); }
    public function create(array $data) { return Supplier::create($data); }
    public function update($id, array $data) {
        $supplier = $this->find($id);
        $supplier->update($data);
        return $supplier;
    }
    public function delete($id) { $this->find($id)->delete(); }
}