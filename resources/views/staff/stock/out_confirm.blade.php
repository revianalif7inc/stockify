@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-white">Konfirmasi Barang Keluar</h1>
            <p class="text-slate-400">Periksa dan konfirmasi detail barang keluar berikut.</p>
        </div>

        <div class="rounded-lg border border-slate-600 overflow-hidden p-6 bg-slate-900">
            <div class="flex items-center gap-6 mb-6">
                <img src="{{ $movement->product->imageUrl ?? asset('images/no-image.svg') }}"
                    alt="{{ $movement->product->name ?? 'Produk' }}"
                    class="w-24 h-24 object-cover rounded border border-slate-700">
                <div>
                    <div class="text-xl font-bold text-white mb-1">{{ $movement->product->name ?? 'Produk' }}</div>
                    <div class="text-slate-400 text-sm mb-1">SKU: {{ $movement->product->sku ?? '-' }}</div>
                    <div class="text-slate-400 text-sm">Supplier: {{ $movement->product->supplier->name ?? '-' }}</div>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-slate-300 mb-1">Jumlah: <span class="font-bold">{{ $movement->quantity }}</span></div>
                <div class="text-slate-300 mb-1">Catatan: <span class="font-normal">{{ $movement->notes ?? '-' }}</span>
                </div>
                <div class="text-slate-300 mb-1">Tanggal:
                    {{ $movement->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i') }} WIB</div>
            </div>
            <form method="POST" action="{{ route('staff.stock.out.do_confirm', $movement->id) }}">
                @csrf
                <button class="bg-rose-600 px-4 py-2 rounded text-white">Konfirmasi</button>
                <a href="{{ route('staff.stock.out') }}" class="ml-4 text-slate-200">Kembali</a>
            </form>
        </div>
    </div>
@endsection