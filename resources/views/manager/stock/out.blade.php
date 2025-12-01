@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Barang Keluar</h1>
            <p class="text-slate-400">Catat pengeluaran stok barang</p>
        </div>

        <!-- Add Button -->
        <div class="mb-6">
            <button onclick="openAddModal()"
                class="text-white bg-rose-600 hover:bg-rose-700 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                <i class="fas fa-plus mr-2"></i> Tambah Barang Keluar
            </button>
        </div>

        <!-- Transactions Table -->
        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                        <tr>
                            <th scope="col" class="px-6 py-4">No.</th>
                            <th scope="col" class="px-6 py-4">Gambar</th>
                            <th scope="col" class="px-6 py-4">Produk</th>
                            <th scope="col" class="px-6 py-4 text-center">Kuantitas</th>
                            <th scope="col" class="px-6 py-4">User</th>
                            <th scope="col" class="px-6 py-4">Tanggal</th>
                            <th scope="col" class="px-6 py-4">Catatan</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $movements = isset($products) ? \App\Models\StockMovement::where('type', 'out')->with('product', 'user')->latest('created_at')->get() : collect([]);
                        @endphp
                        @forelse($movements as $index => $movement)
                            <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                                <td class="px-6 py-4 text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ $movement->product->imageUrl }}" alt="{{ $movement->product->name }}" class="w-12 h-12 object-cover rounded border border-slate-700">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $movement->product->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-rose-950/50 text-rose-300 font-bold text-sm">{{ $movement->quantity }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $movement->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $movement->user->username ?? $movement->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    <div>{{ $movement->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $movement->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 text-slate-400">{{ $movement->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex gap-2 justify-end">
                                        @if($movement->status === 'pending')
                                            <button onclick="openEditModal({{ $movement->id }})" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 transition font-medium text-sm">✏️ Edit</button>
                                            <button onclick="deleteItem({{ $movement->id }})" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition font-medium text-sm">🗑️ Hapus</button>
                                        @elseif($movement->status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-sm">✓ Approved</span>
                                            <button onclick="deleteItem({{ $movement->id }})" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition font-medium text-sm" title="Akan revert stok">🗑️ Hapus</button>
                                        @elseif($movement->status === 'rejected')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-500/10 text-red-400 text-sm">✗ Rejected</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t border-slate-700">
                                <td colspan="8" class="px-6 py-4 text-center text-slate-400">Tidak ada data barang keluar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        </x-table-card>

        <!-- Add/Edit Modal -->
        <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-slate-900 rounded-lg p-6 max-w-md w-full mx-4 border border-slate-700">
                <h3 id="modalTitle" class="text-xl font-bold text-white mb-4">Tambah Barang Keluar</h3>
                <form id="stockForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="movementId" name="movement_id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Produk</label>
                        <select name="product_id" id="productSelect" class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Produk</option>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Kuantitas</label>
                        <input type="number" name="quantity" id="quantityInput" min="1" placeholder="Masukkan kuantitas"
                            class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-200">Catatan</label>
                        <textarea name="notes" id="notesInput" placeholder="Catatan tambahan..." rows="3"
                            class="w-full px-4 py-2 rounded-lg text-sm border border-slate-600 bg-slate-800 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2.5 transition">Simpan</button>
                        <button type="button" onclick="closeAddModal()"
                            class="flex-1 text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-4 py-2.5 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-slate-900 rounded-lg p-6 max-w-sm w-full mx-4 border border-slate-700">
                <h3 class="text-lg font-bold text-white mb-4">Konfirmasi Hapus</h3>
                <p class="text-slate-300 mb-6">Apakah Anda yakin ingin menghapus transaksi ini? Stok akan direvert otomatis.</p>
                <form id="deleteForm" method="POST" class="flex gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex-1 text-white bg-rose-600 hover:bg-rose-700 font-medium rounded-lg text-sm px-4 py-2.5 transition">Hapus</button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-4 py-2.5 transition">Batal</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentEditId = null;

        function openAddModal() {
            document.getElementById('movementId').value = '';
            document.getElementById('modalTitle').textContent = 'Tambah Barang Keluar';
            document.getElementById('stockForm').reset();
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('stockForm').action = "{{ route('manager.stock.out.store') }}";
            
            document.getElementById('addModal').classList.remove('hidden');
            currentEditId = null;
        }

        function openEditModal(movementId) {
            currentEditId = movementId;
            const editUrl = `/manager/stock/out/${movementId}/edit`;
            console.log('Fetching from:', editUrl);
            
            fetch(editUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                document.getElementById('modalTitle').textContent = 'Edit Barang Keluar';
                document.getElementById('movementId').value = movementId;
                document.getElementById('productSelect').value = data.product_id;
                document.getElementById('quantityInput').value = data.quantity;
                document.getElementById('notesInput').value = data.notes || '';
                
                // Set form method to PUT
                document.getElementById('formMethod').value = 'PUT';
                
                // Set form action to update route
                document.getElementById('stockForm').action = `/manager/stock/out/${movementId}`;
                
                document.getElementById('addModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Full error object:', error);
                console.error('Error message:', error.message);
                alert('Gagal memuat data. Error: ' + error.message + '\n\nSilakan cek browser console untuk detail.');
            });
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function deleteItem(movementId) {
            if (confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan direvert otomatis.')) {
                document.getElementById('deleteForm').action = `/manager/stock/out/${movementId}`;
                document.getElementById('deleteModal').classList.remove('hidden');
            }
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Form submission handler
        document.getElementById('stockForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const action = this.action;
            const method = document.getElementById('formMethod').value;
            
            // Submit form using fetch for better control
            fetch(action, {
                method: method === 'PUT' ? 'POST' : method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Reload page on success
                    window.location.reload();
                } else {
                    return response.text().then(text => {
                        throw new Error('Server error: ' + response.status);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menyimpan data. Error: ' + error.message);
            });
        });
    </script>
@endsection