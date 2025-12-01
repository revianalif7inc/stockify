@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Monitoring Stok</h1>
            <p class="text-slate-400">Pantau status stok produk real-time</p>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700">
            <h3 class="text-lg font-semibold mb-4 text-slate-100">Filter Produk</h3>

            <form method="GET" action="{{ route('manager.stock.monitoring') }}" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label for="category" class="block mb-2 text-sm font-semibold text-slate-200">Kategori</label>
                    <select name="category" id="category"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block mb-2 text-sm font-semibold text-slate-200">Status Stok</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="safe" {{ request('status') === 'safe' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="critical" {{ request('status') === 'critical' ? 'selected' : '' }}>Stok Kritis</option>
                    </select>
                </div>

                <div>
                    <label for="search" class="block mb-2 text-sm font-semibold text-slate-200">Cari Produk</label>
                    <input type="text" name="search" id="search" placeholder="Nama produk..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-2 items-end">
                    <button type="submit"
                        class="flex-1 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('manager.stock.monitoring') }}"
                        class="flex-1 text-center text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No.</th>
                            <th scope="col" class="px-6 py-4">Gambar</th>
                            <th scope="col" class="px-6 py-4">Produk</th>
                            <th scope="col" class="px-6 py-4">Kategori</th>
                            <th scope="col" class="px-6 py-4 text-center">Stok Saat Ini</th>
                            <th scope="col" class="px-6 py-4 text-center">Stok Min</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            @php
                                $isCritical = $product->current_stock <= $product->min_stock;
                            @endphp
                            <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                                <td class="px-6 py-4 text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                        class="w-12 h-12 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-500">SKU: {{ $product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-300">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $isCritical ? 'bg-rose-950/50 text-rose-300' : 'bg-emerald-950/50 text-emerald-300' }} font-bold text-sm">
                                        {{ $product->current_stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-300">{{ $product->min_stock }}</td>
                                <td class="px-6 py-4">
                                    @if($isCritical)
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-950/50 border border-rose-500/50 text-rose-300 text-xs font-semibold">
                                            <i class="fas fa-exclamation-circle"></i> Kritis
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-950/50 border border-emerald-500/50 text-emerald-300 text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i> Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('manager.products.edit', $product->id) }}"
                                        class="text-blue-400 hover:text-blue-300 text-sm font-medium">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t border-slate-700">
                                <td colspan="8" class="px-6 py-4 text-center text-slate-400">Tidak ada data produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="mt-4 grid md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-slate-800/30 border border-slate-700">
                <p class="text-slate-400 text-sm">Total Produk</p>
                <p class="text-2xl font-bold text-slate-100 mt-2">{{ $products->count() }}</p>
            </div>
            <div class="p-4 rounded-lg bg-slate-800/30 border border-slate-700">
                <p class="text-slate-400 text-sm">Stok Aman</p>
                <p class="text-2xl font-bold text-emerald-400 mt-2">
                    {{ $products->filter(fn($p) => $p->current_stock > $p->min_stock)->count() }}
                </p>
            </div>
            <div class="p-4 rounded-lg bg-slate-800/30 border border-slate-700">
                <p class="text-slate-400 text-sm">Stok Kritis</p>
                <p class="text-2xl font-bold text-rose-400 mt-2">
                    {{ $products->filter(fn($p) => $p->current_stock <= $p->min_stock)->count() }}
                </p>
            </div>
        </div>
    </div>
@endsection