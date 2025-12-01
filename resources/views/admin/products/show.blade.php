@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <div class="flex gap-3 items-start mb-6">
            <span class="text-4xl">📦</span>
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $product->name }}</h1>
                <p class="text-slate-400 text-sm mt-1">SKU: {{ $product->sku }} • Kategori:
                    {{ $product->category->name ?? '-' }}</p>
            </div>
        </div>

        <div class="flex justify-end mb-6 gap-2">
            <a href="{{ route('admin.products.edit', $product->id) }}"
                class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-4 rounded-lg">
                ✏️ Edit
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center gap-2 bg-transparent border border-slate-700 text-slate-200 py-2 px-4 rounded-lg hover:bg-slate-700/30">
                ← Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-4">
                    <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="w-full h-auto rounded mb-4">
                    <div class="text-sm text-slate-300 mb-2">Supplier: {{ $product->supplier->name ?? '-' }}</div>
                    <div class="text-sm text-slate-300">Stok Saat Ini: <strong
                            class="text-white">{{ $product->current_stock }}</strong></div>
                    <div class="text-sm text-slate-300">Stok Minimum: <strong
                            class="text-white">{{ $product->min_stock }}</strong></div>
                    <div class="mt-4 text-lg font-semibold text-amber-300">
                        Rp{{ number_format($product->selling_price, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                    <h3 class="text-lg font-semibold text-white mb-3">Deskripsi</h3>
                    <p class="text-slate-300 mb-6">{{ $product->description ?? '-' }}</p>

                    <h3 class="text-lg font-semibold text-white mb-3">Atribut Produk</h3>
                    @if($product->attributeValues && $product->attributeValues->count())
                        <div class="space-y-3">
                            @foreach($product->attributeValues as $av)
                                @if($av->attribute)
                                    <div class="p-4 bg-slate-700/30 rounded border border-slate-700">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-sm text-slate-300">{{ $av->attribute->name }}
                                                    @if($av->attribute->is_required) <span class="text-rose-400">*</span> @endif
                                                </div>
                                                <div class="text-white font-semibold">{{ $av->value }}</div>
                                            </div>
                                            @if(!empty($av->attribute->description))
                                                <div class="text-xs text-slate-400 max-w-xs">{{ $av->attribute->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400">Produk ini tidak memiliki atribut yang terdefinisi.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection