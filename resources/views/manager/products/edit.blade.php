@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Edit Produk</h1>
            <p class="text-slate-400">Perbarui informasi produk dan stok minimum</p>
        </div>

        <!-- Form Card -->
        <div class="rounded-lg border border-slate-600 overflow-hidden bg-slate-900">
            <div class="p-6">
                <form action="{{ route('manager.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Produk (Nama bisa diedit) -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Nama Produk</label>
                            <input type="text" name="name" value="{{ $product->name }}" required
                                class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">SKU</label>
                            <input type="text" value="{{ $product->sku }}" disabled
                                class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-300 cursor-not-allowed">
                            <p class="text-xs text-slate-500 mt-1">Read-only</p>
                        </div>
                    </div>

                    <!-- Stok Information -->
                    <div class="grid md:grid-cols-3 gap-6 p-4 rounded-lg bg-slate-800 border border-slate-700">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Stok Saat Ini</label>
                            <div class="text-2xl font-bold text-emerald-400">{{ $product->current_stock }}</div>
                            <p class="text-xs text-slate-500 mt-1">Read-only</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Stok Minimum</label>
                            <input type="number" name="min_stock" value="{{ $product->min_stock }}" required
                                class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Status</label>
                            @php
                                $isCritical = $product->current_stock <= $product->min_stock;
                            @endphp
                            <div class="text-sm font-semibold {{ $isCritical ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ $isCritical ? '⚠️ Kritis' : '✓ Aman' }}
                            </div>
                        </div>
                    </div>

                    <!-- Kategori dan Supplier -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Kategori</label>
                            <select name="category_id" required
                                class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Supplier</label>
                            <select name="supplier_id" required
                                class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $product->description }}</textarea>
                    </div>

                    <!-- Gambar Produk -->
                    <div class="border-t border-slate-700 pt-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Gambar Produk</h3>
                        
                        @if($product->image)
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-semibold text-slate-200">Gambar Saat Ini</label>
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg border border-slate-600">
                            </div>
                        @endif

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-200">Upload Gambar Baru</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full px-4 py-3 rounded-lg text-sm border border-slate-600 bg-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, GIF (Max 2MB). Kosongkan jika tidak ingin mengganti</p>
                            @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4 border-t border-slate-700">
                        <button type="submit"
                            class="flex-1 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('manager.stock.monitoring') }}"
                            class="flex-1 text-center text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 p-4 rounded-lg bg-slate-900 border border-slate-600">
            <h3 class="text-sm font-semibold text-slate-200 mb-2">📌 Informasi:</h3>
            <p class="text-sm text-slate-400">
                Anda dapat mengedit <strong>Nama Produk</strong>, <strong>Deskripsi</strong>, <strong>Stok Minimum</strong>, <strong>Kategori</strong>, <strong>Supplier</strong>, dan <strong>Gambar</strong>.
                <br>SKU tetap tidak dapat diubah.
            </p>
        </div>
    </div>
@endsection
