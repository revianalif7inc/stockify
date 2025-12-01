<?php

namespace App\Repositories;

use App\Interfaces\StockMovementRepositoryInterface;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    /**
     * Mencatat Barang Masuk dan mengupdate stok produk.
     */
    public function recordStockIn(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'];

            // 1. Catat pergerakan stok
            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $data['user_id'], // ID Staff/Manajer yang mencatat
                'type' => 'in',
                'quantity' => $quantity,
                'notes' => $data['notes'] ?? null,
            ]);

            // 2. Update stok produk
            $product->increment('current_stock', $quantity);

            return $movement;
        });
    }

    /**
     * Mencatat Barang Keluar dan mengupdate stok produk.
     */
    public function recordStockOut(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'];

            // 1. Catat pergerakan stok
            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $data['user_id'],
                'type' => 'out',
                'quantity' => $quantity,
                'notes' => $data['notes'] ?? null,
            ]);

            // 2. Update stok produk
            $product->decrement('current_stock', $quantity);

            return $movement;
        });
    }

    /**
     * Update a movement and adjust product stock accordingly
     */
    public function updateMovement($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $movement = StockMovement::findOrFail($id);
            $product = Product::findOrFail($movement->product_id);

            $oldQty = $movement->quantity;
            $newQty = $data['quantity'];

            // If movement type is 'out', ensure enough stock if increasing quantity
            if ($movement->type === 'out' && $newQty > $oldQty) {
                $diffCheck = $newQty - $oldQty;
                if ($product->current_stock < $diffCheck) {
                    throw new Exception('Stok tidak mencukupi untuk mengurangi lebih banyak lagi.');
                }
            }

            // Cegah penghapusan notes: jika notes sudah ada, tidak boleh diubah menjadi kosong/null
            $newNotes = $data['notes'] ?? $movement->notes;
            if (!empty($movement->notes) && (empty($newNotes) || $newNotes === null)) {
                $newNotes = $movement->notes;
            }

            // Update movement
            $movement->update([
                'quantity' => $newQty,
                'notes' => $newNotes,
            ]);

            // Adjust product stock by difference
            $diff = $newQty - $oldQty; // can be negative
            if ($diff != 0) {
                if ($movement->type === 'in') {
                    $product->increment('current_stock', $diff);
                } else { // out
                    $product->decrement('current_stock', $diff);
                }
            }

            return $movement;
        });
    }

    /**
     * Delete a movement and revert its stock effect
     */
    public function deleteMovement($id)
    {
        return DB::transaction(function () use ($id) {
            $movement = StockMovement::findOrFail($id);
            $product = Product::findOrFail($movement->product_id);

            // Revert stock change
            if ($movement->type === 'in') {
                $product->decrement('current_stock', $movement->quantity);
            } else {
                $product->increment('current_stock', $movement->quantity);
            }

            $movement->delete();
            return true;
        });
    }
}
