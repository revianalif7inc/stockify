<?php

namespace App\Services;

use App\Interfaces\SupplierRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Exception;

class SupplierService
{
    protected $supplierRepository;

    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function getAllSuppliers() { return $this->supplierRepository->all(); }
    public function findSupplier($id) { return $this->supplierRepository->find($id); }
    
    public function createSupplier(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:suppliers,name', 
            'phone' => 'nullable|string|max:20',
        ]);
        if ($validator->fails()) { throw new Exception($validator->errors()->first()); }
        return $this->supplierRepository->create($data);
    }
    
    public function updateSupplier($id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:suppliers,name,'.$id,
            'phone' => 'nullable|string|max:20',
        ]);
        if ($validator->fails()) { throw new Exception($validator->errors()->first()); }
        return $this->supplierRepository->update($id, $data);
    }

    public function deleteSupplier($id) { $this->supplierRepository->delete($id); }
}