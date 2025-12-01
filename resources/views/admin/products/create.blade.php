@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-4xl">📦</span>
                <h1 class="text-4xl font-bold text-white">Tambah Produk Baru</h1>
            </div>
            <p class="text-slate-400 text-sm">Tambahkan produk baru ke inventaris Stockify Anda</p>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <x-alert>{{ session('error') }}</x-alert>
        @endif

        <!-- Product Form -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Dasar -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                    <span class="text-2xl">📋</span>
                    <h2 class="text-lg font-bold text-white">Informasi Dasar</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gambar Produk -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-slate-300 mb-2">
                            Gambar Produk
                        </label>
                        <input type="file" name="image" id="image"
                            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition" />
                        <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG, GIF, WebP (Max 2MB)</p>
                        @error('image')
                            <p class="text-xs text-rose-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Nama Produk -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Nama Produk
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            placeholder="Masukkan nama produk" required>
                    </div>
                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-300 mb-2">
                            Kategori
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <select name="category_id" id="category_id"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Dynamic attributes will be injected here -->
                        <div id="attributesContainer" class="mt-4"></div>
                    </div>
                </div>
                <script>
                    (function () {
                        const attrsContainer = document.getElementById('attributesContainer');
                        const categorySelect = document.getElementById('category_id');
                        const oldAttrs = {!! json_encode(old('attributes', [])) !!};

                        function renderAttributes(attributes, values = {}) {
                            if (!attributes || !attributes.length) {
                                attrsContainer.innerHTML = '';
                                return;
                            }
                            let html = '';
                            attributes.forEach(attr => {
                                const id = attr.id;
                                const name = attr.name;
                                const type = attr.type;
                                const required = attr.is_required ? 'required' : '';
                                const val = (values && values[id]) ? values[id] : '';

                                html += '<div class="mb-4">';
                                html += `<label class="block text-sm font-medium text-slate-300 mb-2">${name} ${attr.is_required ? '<span class="text-rose-400">*</span>' : ''}</label>`;

                                if (type === 'text') {
                                    html += `<input type="text" name="attributes[${id}]" value="${val}" class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition" ${required}>`;
                                } else if (type === 'number') {
                                    html += `<input type="number" name="attributes[${id}]" value="${val}" class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition" ${required}>`;
                                } else if (type === 'select') {
                                    html += `<select name="attributes[${id}]" class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition" ${required}>`;
                                    html += `<option value="">Pilih ${name}</option>`;
                                    if (Array.isArray(attr.options)) {
                                        attr.options.forEach(opt => {
                                            const selected = (val == opt) ? 'selected' : '';
                                            html += `<option value="${opt}" ${selected}>${opt}</option>`;
                                        });
                                    }
                                    html += `</select>`;
                                }

                                html += '</div>';
                            });
                            attrsContainer.innerHTML = html;
                        }

                        async function loadAttributesForCategory(categoryId) {
                            if (!categoryId) { attrsContainer.innerHTML = ''; return; }
                            try {
                                const res = await fetch(`/admin/categories/${categoryId}/attributes/json`);
                                if (!res.ok) return;
                                const json = await res.json();
                                renderAttributes(json, oldAttrs);
                            } catch (e) {
                                console.error(e);
                            }
                        }

                        categorySelect.addEventListener('change', function () {
                            loadAttributesForCategory(this.value);
                        });

                        // On load, if category selected (old input), load attributes
                        if (categorySelect.value) { loadAttributesForCategory(categorySelect.value); }
                    })();
                </script>
            </div>

            <!-- Section 2: Harga & Stok -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                    <span class="text-2xl">💰</span>
                    <h2 class="text-lg font-bold text-white">Harga & Stok</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Harga Beli -->
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-slate-300 mb-2">
                            Harga Beli (Rp)
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                            value="{{ old('purchase_price') }}"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            placeholder="0" required>
                    </div>
                    <!-- Harga Jual -->
                    <div>
                        <label for="selling_price" class="block text-sm font-medium text-slate-300 mb-2">
                            Harga Jual (Rp)
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price"
                            value="{{ old('selling_price') }}"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            placeholder="0" required>
                    </div>
                    <!-- Stok Awal -->
                    <div>
                        <label for="current_stock" class="block text-sm font-medium text-slate-300 mb-2">
                            Stok Awal
                        </label>
                        <input type="number" name="current_stock" id="current_stock" value="{{ old('current_stock', 0) }}"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Section 3: Pengaturan Produk -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                    <span class="text-2xl">⚙️</span>
                    <h2 class="text-lg font-bold text-white">Pengaturan Produk</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Supplier -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-slate-300 mb-2">
                            Supplier (Opsional)
                        </label>
                        <select name="supplier_id" id="supplier_id"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition">
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Stok Minimum -->
                    <div>
                        <label for="min_stock" class="block text-sm font-medium text-slate-300 mb-2">
                            Stok Minimum
                        </label>
                        <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 10) }}"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            placeholder="10">
                    </div>
                </div>
            </div>

            <!-- Section 4: Deskripsi -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                    <span class="text-2xl">📝</span>
                    <h2 class="text-lg font-bold text-white">Deskripsi Produk</h2>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="6"
                        class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition resize-none"
                        placeholder="Tambahkan deskripsi produk...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.products.index') }}"
                    class="px-8 py-3 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition duration-200 flex items-center gap-2">
                    ← Kembali
                </a>
                <button type="submit"
                    class="px-8 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition duration-200 flex items-center gap-2 shadow-lg">
                    💾 Simpan Produk
                </button>
            </div>
        </form>
    </div>
@endsection