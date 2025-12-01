@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Produk Stok Kritis</h1>
            <p class="text-slate-400">Produk yang perlu segera di-reorder</p>
        </div>

        <!-- Critical Stock Products Table -->
        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-4">No.</th>
                        <th scope="col" class="px-6 py-4">Gambar</th>
                        <th scope="col" class="px-6 py-4">Produk</th>
                        <th scope="col" class="px-6 py-4">Kategori</th>
                        <th scope="col" class="px-6 py-4 text-center">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-4 text-center">Stok Min</th>
                        <th scope="col" class="px-6 py-4">Supplier</th>
                        <th scope="col" class="px-6 py-4">Harga Beli</th>
                        <th scope="col" class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                        <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4 text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                    class="w-12 h-12 object-cover rounded border border-slate-700">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-100">{{ $product->name }}</div>
                                <div class="text-xs text-slate-500">SKU: {{ $product->sku }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-rose-950/50 text-rose-300 font-bold text-sm">{{ $product->current_stock }}</span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-300">{{ $product->min_stock }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-300">Rp{{ number_format($product->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openReorderModal({{ $product->id }}, '{{ $product->name }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 transition font-medium text-sm">📦
                                    Pesan</button>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t border-slate-700">
                            <td colspan="9" class="px-6 py-8 text-center text-slate-400">
                                <p class="text-sm">Tidak ada produk dengan stok kritis. Semua produk dalam kondisi aman! ✅</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-table-card>

        <!-- Reorder Modal -->
        <div id="reorderModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-slate-900 rounded-lg p-6 max-w-md w-full mx-4 border border-slate-700">
                <h3 class="text-xl font-bold text-white mb-4">Pesan Ulang Stok</h3>
                <form id="reorderForm" method="POST" action="{{ route('manager.reorder.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" id="productIdInput">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Produk</label>
                        <input type="text" id="productInput" disabled
                            class="w-full px-4 py-2 rounded-lg text-sm border focus:outline-none focus:ring-2 bg-slate-800 text-slate-400">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Jumlah Pesanan</label>
                        <input type="number" id="quantityInput" name="quantity" min="1" placeholder="Masukkan jumlah"
                            required
                            class="w-full px-4 py-2 rounded-lg text-sm border border-slate-700 focus:outline-none focus:ring-2 bg-slate-800 text-white">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Supplier</label>
                        <select id="supplierInput" name="supplier_id" required
                            class="w-full px-4 py-2 rounded-lg text-sm border border-slate-700 focus:outline-none focus:ring-2 bg-slate-800 text-white">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Catatan</label>
                        <textarea id="notesInput" name="notes" placeholder="Catatan pesanan..." rows="3"
                            class="w-full px-4 py-2 rounded-lg text-sm border border-slate-700 focus:outline-none focus:ring-2 bg-slate-800 text-white"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 text-white bg-emerald-600 hover:bg-emerald-700 font-medium rounded-lg text-sm px-4 py-2.5 transition">Kirim
                            Pesanan</button>
                        <button type="button" onclick="closeReorderModal()"
                            class="flex-1 text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-4 py-2.5 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentProductId = null;
        let currentProductName = null;

        function openReorderModal(productId, productName) {
            currentProductId = productId;
            currentProductName = productName;
            document.getElementById('productIdInput').value = productId;
            document.getElementById('productInput').value = productName;
            document.getElementById('quantityInput').value = '';
            document.getElementById('supplierInput').value = '';
            document.getElementById('notesInput').value = '';
            document.getElementById('reorderModal').classList.remove('hidden');
        }

        function closeReorderModal() {
            document.getElementById('reorderModal').classList.add('hidden');
            currentProductId = null;
            currentProductName = null;
        }
    </script>
@endsection