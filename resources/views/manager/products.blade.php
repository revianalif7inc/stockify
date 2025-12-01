@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-4xl">📦</span>
                <h1 class="text-4xl font-bold text-white">Daftar Produk</h1>
            </div>
            <p class="text-slate-400 text-sm">Kelola semua produk dalam katalog Stockify</p>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 p-6 rounded-lg border border-slate-600 bg-slate-900">
            <h3 class="text-lg font-semibold mb-4 text-slate-100">🔍 Filter Produk</h3>

            <form method="GET" action="{{ route('manager.products') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <!-- Search Box -->
                <div>
                    <label for="search" class="block mb-2 text-sm font-semibold text-slate-200">Cari Nama/SKU</label>
                    <input type="text" name="search" id="search" placeholder="Cari produk..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block mb-2 text-sm font-semibold text-slate-200">Kategori</label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier Filter -->
                <div>
                    <label for="supplier_id" class="block mb-2 text-sm font-semibold text-slate-200">Supplier</label>
                    <select name="supplier_id" id="supplier_id"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div>
                    <label for="stock_status" class="block mb-2 text-sm font-semibold text-slate-200">Status Stok</label>
                    <select name="stock_status" id="stock_status"
                        class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="safe" {{ request('stock_status') == 'safe' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="critical" {{ request('stock_status') == 'critical' ? 'selected' : '' }}>Stok Kritis
                        </option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('manager.products') }}"
                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700">
            <div class="text-slate-300 text-sm">
                Total: <span class="font-bold text-white">{{ $products->count() }}</span> / <span
                    class="text-slate-400">{{ $products->total() }}</span> produk
            </div>
        </div>

        <!-- Products Table -->
        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-bold bg-slate-800/50 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-slate-300">Gambar</th>
                        <th class="px-6 py-4 text-slate-300">Nama Produk</th>
                        <th class="px-6 py-4 text-slate-300">Kategori</th>
                        <th class="px-6 py-4 text-slate-300">Supplier</th>
                        <th class="px-6 py-4 text-right text-slate-300">Harga Beli</th>
                        <th class="px-6 py-4 text-right text-slate-300">Harga Jual</th>
                        <th class="px-6 py-4 text-center text-slate-300">Stok</th>
                        <th class="px-6 py-4 text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4">
                                <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                    class="w-10 h-10 object-cover rounded-lg border border-slate-600">
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                <div>{{ $product->name }}</div>
                                @if($product->attributeValues && $product->attributeValues->count())
                                    @php
                                        $attrs = $product->attributeValues;
                                        $first = $attrs->slice(0, 2);
                                        $rest = $attrs->slice(2);
                                    @endphp
                                    <div class="text-xs text-slate-400 mt-1">
                                        @foreach($first as $av)
                                            @if($av->attribute)
                                                <span class="inline-block mr-3">{{ $av->attribute->name }}: <strong
                                                        class="text-slate-100">{{ $av->value }}</strong>@if(!empty($av->attribute->description))
                                                            <span
                                                        class="text-xs text-slate-400">({{ $av->attribute->description }})</span>@endif</span>
                                            @endif
                                        @endforeach
                                        @if($rest->count() > 0)
                                            <button type="button"
                                                onclick="document.getElementById('moreAttrs-{{ $product->id }}').classList.toggle('hidden')"
                                                class="text-xs text-slate-300 underline">+{{ $rest->count() }} more</button>
                                            <div id="moreAttrs-{{ $product->id }}" class="hidden mt-2">
                                                @foreach($rest as $av)
                                                    @if($av->attribute)
                                                        <div class="inline-block mr-3">{{ $av->attribute->name }}: <strong
                                                                class="text-slate-100">{{ $av->value }}</strong>@if(!empty($av->attribute->description))
                                                                    <span
                                                                class="text-xs text-slate-400">({{ $av->attribute->description }})</span>@endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right text-slate-300">
                                Rp{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-medium text-amber-300">
                                Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full @if($product->current_stock <= $product->min_stock) bg-rose-950/50 text-rose-300 @else bg-emerald-950/50 text-emerald-300 @endif">
                                    {{ $product->current_stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('manager.products.show', $product->id) }}"
                                    class="inline-flex items-center gap-2 text-slate-200 hover:text-slate-100 px-3 py-1 rounded font-medium transition">🔍
                                    Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-slate-400">
                                    <p class="text-2xl mb-2">📭</p>
                                    <p>Belum ada data produk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 mt-4">{{ $products->links() }}</div>
        </x-table-card>
    </div>
@endsection