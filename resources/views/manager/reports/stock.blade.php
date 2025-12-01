@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Laporan Stok Barang Saat Ini</h1>

        @if (session('success'))
            <div class="p-4 mb-4 rounded-lg bg-emerald-500/20 border-l-4 border-emerald-500 text-emerald-100 flex gap-3 items-start" role="alert">
                <span class="text-xl mt-0.5">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="mb-6 p-6 rounded-lg border border-slate-600 bg-slate-900">
            <h3 class="text-lg font-semibold mb-4 text-slate-100">Filter Laporan</h3>

            <form method="GET" action="{{ route('manager.reports.stock') }}"
                class="flex gap-4 items-end flex-col md:flex-row">
                <div class="flex-1 w-full md:w-auto">
                    <label for="category_id" class="block mb-2 text-sm font-semibold text-slate-200">Filter
                        Kategori</label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 rounded-lg text-sm border focus:outline-none focus:ring-2">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('manager.reports.stock') }}"
                    class="text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-5 py-2.5 transition text-center">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid md:grid-cols-4 gap-4 mb-6">
            <!-- Total Produk -->
            <div class="p-4 rounded-lg border border-blue-500/30 bg-blue-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-300 font-medium">Total Produk</p>
                        <p class="text-2xl font-bold text-blue-400 mt-2">{{ $products->count() }}</p>
                    </div>
                    <div class="text-3xl text-blue-500/40">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Aman -->
            <div class="p-4 rounded-lg border border-emerald-500/30 bg-emerald-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-300 font-medium">Stok Aman</p>
                        <p class="text-2xl font-bold text-emerald-400 mt-2">
                            {{ $products->filter(fn($p) => $p->current_stock > $p->min_stock)->count() }}
                        </p>
                    </div>
                    <div class="text-3xl text-emerald-500/40">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Stok Kritis -->
            <div class="p-4 rounded-lg border border-rose-500/30 bg-rose-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-rose-300 font-medium">Stok Kritis</p>
                        <p class="text-2xl font-bold text-rose-400 mt-2">
                            {{ $products->filter(fn($p) => $p->current_stock <= $p->min_stock)->count() }}
                        </p>
                    </div>
                    <div class="text-3xl text-rose-500/40">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Total Nilai Stok -->
            <div class="p-4 rounded-lg border border-amber-500/30 bg-amber-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-amber-300 font-medium">Nilai Stok</p>
                        <p class="text-2xl font-bold text-amber-400 mt-2">
                            Rp{{ number_format($products->sum(function ($p) {
        return $p->current_stock * $p->selling_price; }), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-3xl text-amber-500/40">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="rounded-lg border border-slate-600 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No.</th>
                            <th scope="col" class="px-6 py-4">Gambar</th>
                            <th scope="col" class="px-6 py-4">Nama Produk</th>
                            <th scope="col" class="px-6 py-4">Kategori</th>
                            <th scope="col" class="px-6 py-4">Supplier</th>
                            <th scope="col" class="px-6 py-4 text-center">Stok Saat Ini</th>
                            <th scope="col" class="px-6 py-4 text-center">Stok Min.</th>
                            <th scope="col" class="px-6 py-4 text-right">Harga Jual</th>
                            <th scope="col" class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $index => $product)
                            <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                                <td class="px-6 py-4 font-medium text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-300">{{ $product->category->name }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ $product->supplier->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg {{ $product->current_stock <= $product->min_stock ? 'bg-rose-950/50 text-rose-300' : 'bg-emerald-950/50 text-emerald-300' }} font-bold">
                                        {{ $product->current_stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-300 font-medium">{{ $product->min_stock }}</td>
                                <td class="px-6 py-4 text-right font-medium text-slate-100">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($product->current_stock <= $product->min_stock)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-950/50 border border-rose-500/50 text-rose-300 text-xs font-semibold">
                                            <i class="fas fa-exclamation-circle"></i>
                                            Kritis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-950/50 border border-emerald-500/50 text-emerald-300 text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i>
                                            Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-inbox text-2xl text-slate-600"></i>
                                        <p>Tidak ada data produk yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Footer -->
        @if ($products->count() > 0)
            <div class="mt-4 grid md:grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-slate-900 border border-slate-600 text-slate-300">
                    <p class="text-sm">Total Data: <strong class="text-slate-100">{{ $products->count() }} produk</strong>
                    </p>
                </div>
                <div class="p-4 rounded-lg bg-slate-900 border border-slate-600 text-slate-300">
                    <p class="text-sm">Total Nilai: <strong class="text-amber-400">
                            Rp{{ number_format($products->sum(function ($p) {
                return $p->current_stock * $p->selling_price; }), 0, ',', '.') }}
                        </strong></p>
                </div>
            </div>
        @endif
    </div>
@endsection
