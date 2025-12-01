@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $product->name }}</h1>
                <div class="text-sm text-slate-400">SKU: {{ $product->sku }} • Kategori:
                    {{ $product->category->name ?? '-' }}</div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('manager.products.edit', $product->id) }}"
                    class="px-3 py-2 rounded bg-sky-600 text-white">✏️ Edit</a>
                <a href="{{ route('manager.products') }}" class="px-3 py-2 rounded bg-slate-700 text-white">← Kembali</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="bg-slate-800 p-4 rounded border border-slate-700">
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
                <div class="bg-slate-800 p-6 rounded border border-slate-700">
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
                                                    @if($av->attribute->is_required) <span class="text-rose-400">*</span> @endif</div>
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
@endsection