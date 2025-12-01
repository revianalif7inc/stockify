@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-white">Stok - Staff</h1>
            <p class="text-slate-400">Daftar produk dan aksi cepat untuk staff</p>
        </div>

        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('staff.stock.in') }}" class="quick-action-card">Barang Masuk</a>
            <a href="{{ route('staff.stock.out') }}" class="quick-action-card">Barang Keluar</a>
            <a href="{{ route('staff.dashboard') }}" class="quick-action-card">Kembali ke Dashboard</a>
        </div>

        <div class="rounded-lg border border-slate-600 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Gambar</th>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $i => $p)
                            <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                                <td class="px-6 py-4 text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ $p->imageUrl }}" alt="{{ $p->name }}" class="w-12 h-12 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $p->name }}</div>
                                    <div class="text-xs text-slate-500">SKU: {{ $p->sku }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-300">{{ $p->category->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center text-slate-300">{{ $p->current_stock }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('staff.stock.in') }}?product={{ $p->id }}"
                                        class="text-emerald-400">Masuk</a>
                                    <span class="mx-2">|</span>
                                    <a href="{{ route('staff.stock.out') }}?product={{ $p->id }}"
                                        class="text-rose-400">Keluar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-slate-400">Tidak ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection