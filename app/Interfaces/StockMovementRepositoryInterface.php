<?php

namespace App\Interfaces;

interface StockMovementRepositoryInterface
{
    public function recordStockIn(array $data);
    public function recordStockOut(array $data);
    public function updateMovement($id, array $data);
    public function deleteMovement($id);
}