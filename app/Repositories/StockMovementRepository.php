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
     * Mencatat Barang Masuk dengan status pending (menunggu konfirmasi staff).
     */
    public function recordStockIn(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'];

            // 1. Catat pergerakan stok dengan status PENDING
            // Stok belum diupdate sampai staff mengkonfirmasi
            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $data['user_id'], // ID Manager yang mencatat
                'type' => 'in',
                'quantity' => $quantity,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending', // Status pending menunggu approval staff
            ]);

            return $movement;
        });
    }

    /**
     * Mencatat Barang Keluar dengan status pending (menunggu konfirmasi staff).
     */
    public function recordStockOut(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            $quantity = $data['quantity'];

            // 1. Catat pergerakan stok dengan status PENDING
            // Stok belum diupdate sampai staff mengkonfirmasi
            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $data['user_id'],
                'type' => 'out',
                'quantity' => $quantity,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending', // Status pending menunggu approval staff
            ]);

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

            // Hanya bisa edit jika pending, tidak boleh edit yang sudah approved/rejected
            if ($movement->status !== 'pending') {
                throw new Exception('Hanya bisa mengedit item dengan status pending. Item ini sudah ' . $movement->status . '.');
            }

            $product = Product::findOrFail($movement->product_id);

            $oldQty = $movement->quantity;
            $newQty = $data['quantity'];

            // Jika pending, stok belum berubah, jadi tidak perlu check stok
            // Stok akan berubah hanya saat approved

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

            // Hanya revert stock jika sudah approved
            // Jika pending/rejected, stok tidak terpengaruh
            if ($movement->status === 'approved') {
                if ($movement->type === 'in') {
                    $product->decrement('current_stock', $movement->quantity);
                } else {
                    $product->increment('current_stock', $movement->quantity);
                }
            }

            $movement->delete();
            return true;
        });
    }

    /**
     * Approve pending stock movement - update stock sekarang
     */
    public function approveMovement($id)
    {
        return DB::transaction(function () use ($id) {
            $movement = StockMovement::findOrFail($id);

            if ($movement->status !== 'pending') {
                throw new Exception('Hanya movement dengan status pending yang bisa diapprove');
            }

            $product = Product::findOrFail($movement->product_id);

            // Update stok berdasarkan tipe
            if ($movement->type === 'in') {
                $product->increment('current_stock', $movement->quantity);
            } else { // out
                if ($product->current_stock < $movement->quantity) {
                    throw new Exception('Stok tidak mencukupi untuk mengeluarkan barang');
                }
                $product->decrement('current_stock', $movement->quantity);
            }

            // Set status approved
            $movement->update(['status' => 'approved']);

            return $movement;
        });
    }

    /**
     * Reject pending stock movement
     */
    public function rejectMovement($id)
    {
        return DB::transaction(function () use ($id) {
            $movement = StockMovement::findOrFail($id);

            if ($movement->status !== 'pending') {
                throw new Exception('Hanya movement dengan status pending yang bisa ditolak');
            }

            // Set status rejected - stok tidak berubah
            $movement->update(['status' => 'rejected']);

            return $movement;
        });
    }
}
