@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-4xl">🏢</span>
                <h1 class="text-4xl font-bold text-white">Manajemen Supplier</h1>
            </div>
            <p class="text-slate-400 text-sm">Kelola supplier dan penyedia barang Anda</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert>{{ session('error') }}</x-alert>
        @endif

        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700">
            <div class="text-slate-300 text-sm">
                Total: <span class="font-bold text-white">{{ count($suppliers) }}</span> supplier
            </div>
            <button type="button" data-modal-target="createSupplierModal" data-modal-toggle="createSupplierModal"
                class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex gap-2 items-center">
                ➕ Tambah Supplier
            </button>
        </div>

        <!-- Table Container -->
        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-bold bg-slate-800/50 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-slate-300">Nama Supplier</th>
                        <th class="px-6 py-4 text-slate-300">Kontak</th>
                        <th class="px-6 py-4 text-slate-300">Email</th>
                        <th class="px-6 py-4 text-slate-300">Alamat</th>
                        <th class="px-6 py-4 text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 font-semibold text-white">{{ $supplier->name }}</td>
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $supplier->address ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <button type="button" data-modal-target="editSupplierModal-{{ $supplier->id }}"
                                        data-modal-toggle="editSupplierModal-{{ $supplier->id }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 transition font-medium text-sm">✏️
                                        Edit</button>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition font-medium text-sm">🗑️
                                            Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-slate-400">
                                    <p class="text-2xl mb-2">📭</p>
                                    <p>Belum ada data supplier</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </table>
        </x-table-card>

        <!-- Create Modal -->
        <div id="createSupplierModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
            <div class="relative w-full max-w-md max-h-full mx-auto">
                <div class="relative bg-slate-800 rounded-lg shadow-xl border border-slate-700">
                    <div class="flex items-start justify-between p-6 border-b border-slate-700">
                        <h3 class="text-xl font-bold text-white">➕ Tambah Supplier Baru</h3>
                        <button type="button" class="text-slate-400 hover:text-white transition"
                            data-modal-hide="createSupplierModal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form action="{{ route('admin.suppliers.store') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-semibold text-white">Nama Supplier</label>
                                <input type="text" name="name" id="name"
                                    class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition"
                                    required>
                            </div>
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-semibold text-white">Kontak</label>
                                <input type="text" name="phone" id="phone"
                                    class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition">
                            </div>
                            <div>
                                <label for="email" class="block mb-2 text-sm font-semibold text-white">Email</label>
                                <input type="email" name="email" id="email"
                                    class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition">
                            </div>
                            <div>
                                <label for="address" class="block mb-2 text-sm font-semibold text-white">Alamat</label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-6 border-t border-slate-700">
                            <button type="submit"
                                class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Simpan
                                Supplier</button>
                            <button data-modal-hide="createSupplierModal" type="button"
                                class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-2.5 rounded-lg transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modals -->
        @foreach ($suppliers as $supplier)
            <div id="editSupplierModal-{{ $supplier->id }}" tabindex="-1" aria-hidden="true"
                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
                <div class="relative w-full max-w-md max-h-full mx-auto">
                    <div class="relative bg-slate-800 rounded-lg shadow-xl border border-slate-700">
                        <div class="flex items-start justify-between p-6 border-b border-slate-700">
                            <h3 class="text-xl font-bold text-white">✏️ Edit Supplier</h3>
                            <button type="button" class="text-slate-400 hover:text-white transition"
                                data-modal-hide="editSupplierModal-{{ $supplier->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="p-6 space-y-4">
                                <div>
                                    <label for="name-{{ $supplier->id }}"
                                        class="block mb-2 text-sm font-semibold text-white">Nama Supplier</label>
                                    <input type="text" name="name" id="name-{{ $supplier->id }}" value="{{ $supplier->name }}"
                                        class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition"
                                        required>
                                </div>
                                <div>
                                    <label for="phone-{{ $supplier->id }}"
                                        class="block mb-2 text-sm font-semibold text-white">Kontak</label>
                                    <input type="text" name="phone" id="phone-{{ $supplier->id }}"
                                        value="{{ $supplier->phone }}"
                                        class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition">
                                </div>
                                <div>
                                    <label for="email-{{ $supplier->id }}"
                                        class="block mb-2 text-sm font-semibold text-white">Email</label>
                                    <input type="email" name="email" id="email-{{ $supplier->id }}"
                                        value="{{ $supplier->email }}"
                                        class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition">
                                </div>
                                <div>
                                    <label for="address-{{ $supplier->id }}"
                                        class="block mb-2 text-sm font-semibold text-white">Alamat</label>
                                    <textarea name="address" id="address-{{ $supplier->id }}" rows="3"
                                        class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 transition">{{ $supplier->address }}</textarea>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-6 border-t border-slate-700">
                                <button type="submit"
                                    class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg transition">Update
                                    Supplier</button>
                                <button data-modal-hide="editSupplierModal-{{ $supplier->id }}" type="button"
                                    class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-2.5 rounded-lg transition">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

@endsection