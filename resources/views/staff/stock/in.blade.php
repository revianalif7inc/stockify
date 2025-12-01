@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-white">Barang Masuk - Konfirmasi</h1>
            <p class="text-slate-400">Daftar barang masuk yang perlu dikonfirmasi</p>
        </div>

        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert>{{ session('error') }}</x-alert>
        @endif

        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Gambar</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $movement)
                        <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4">
                                <img src="{{ $movement->product->imageUrl ?? asset('images/no-image.svg') }}"
                                    alt="{{ $movement->product->name }}" class="w-12 h-12 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $movement->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i') }} WIB
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-100">{{ $movement->product->name }}</div>
                                <div class="text-xs text-slate-500">SKU: {{ $movement->product->sku }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-300 font-semibold">{{ $movement->quantity }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $movement->notes ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('staff.stock.in.confirm', $movement->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 transition font-medium text-sm">📥
                                    Konfirmasi</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-slate-400">Tidak ada barang masuk yang perlu
                                dikonfirmasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-table-card>

        <div class="mt-6">
            <a href="{{ route('staff.dashboard') }}"
                class="inline-block px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection