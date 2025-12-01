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
        // Barang masuk hari ini yang perlu diperiksa (pending)
        $incomingQuery = \App\Models\StockMovement::with('product', 'user')
            ->where('type', 'in')
            ->where('status', 'pending') // Hanya pending
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc');

        // Barang keluar hari ini yang perlu disiapkan (pending)
        $outgoingQuery = \App\Models\StockMovement::with('product', 'user')
            ->where('type', 'out')
            ->where('status', 'pending') // Hanya pending
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc');

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
