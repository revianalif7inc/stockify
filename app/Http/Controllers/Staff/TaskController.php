<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        // Barang masuk hari ini yang perlu diperiksa
        $incomingQuery = \App\Models\StockMovement::with('product', 'user')
            ->where('type', 'in')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc');

        // Barang keluar hari ini yang perlu disiapkan
        $outgoingQuery = \App\Models\StockMovement::with('product', 'user')
            ->where('type', 'out')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc');

        if (Schema::hasColumn('stock_movements', 'status')) {
            $incomingQuery->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'confirmed'); });
            $outgoingQuery->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'confirmed'); });
        }

        $incoming = $incomingQuery->get();
        $outgoing = $outgoingQuery->get();

        $tasks = [];
        foreach ($incoming as $in) {
            $tasks[] = [
                'id' => $in->id,
                'title' => 'Periksa barang masuk: ' . ($in->product->name ?? 'Produk'),
                'status' => 'pending',
                'type' => 'in',
                'movement' => $in,
            ];
        }
        foreach ($outgoing as $out) {
            $tasks[] = [
                'id' => $out->id,
                'title' => 'Siapkan barang keluar: ' . ($out->product->name ?? 'Produk'),
                'status' => 'pending',
                'type' => 'out',
                'movement' => $out,
            ];
        }

        return view('staff.tasks', compact('tasks'));
    }
}
