<?php

namespace App\Services;

use App\Interfaces\StockMovementRepositoryInterface;
use App\Repositories\ProductRepository; // Untuk mengambil data stok
use Illuminate\Support\Facades\Validator;
use Exception;

class StockService
{
    protected $movementRepository;
    protected $productRepository;

    public function __construct(
        StockMovementRepositoryInterface $movementRepository,
        ProductRepository $productRepository
    ) {
        $this->movementRepository = $movementRepository;
        $this->productRepository = $productRepository;
    }

    public function processStockIn(array $data)
    {
        $this->validateMovement($data, 'in');
        return $this->movementRepository->recordStockIn($data);
    }

    public function processStockOut(array $data)
    {
        $this->validateMovement($data, 'out');

        // Logic Kritis: Cek ketersediaan stok sebelum pengeluaran
        /** @var \App\Models\Product $product */
        $product = $this->productRepository->find($data['product_id']);
        if ((int) $product->current_stock < (int) $data['quantity']) {
            throw new Exception("Stok tidak mencukupi. Stok saat ini: " . $product->current_stock);
        }

        return $this->movementRepository->recordStockOut($data);
    }

    protected function validateMovement(array $data, $type)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }

    /**
     * Update a stock movement entry
     */
    public function updateMovement($id, array $data)
    {
        $this->validateMovement($data, 'update');
        return $this->movementRepository->updateMovement($id, $data);
    }

    /**
     * Delete a stock movement and revert stock
     */
    public function deleteMovement($id)
    {
        return $this->movementRepository->deleteMovement($id);
    }
}