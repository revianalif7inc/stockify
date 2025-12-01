@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-4xl">📂</span>
                <h1 class="text-4xl font-bold text-white">Manajemen Kategori</h1>
            </div>
            <p class="text-slate-400 text-sm">Kelola kategori produk dalam sistem Stockify</p>
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
                Total: <span class="font-bold text-white">{{ count($categories) }}</span> kategori
            </div>
            <button type="button" data-modal-target="createCategoryModal" data-modal-toggle="createCategoryModal"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex gap-2 items-center">
                ➕ Tambah Kategori
            </button>
        </div>

        <!-- Table Container -->
        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-bold bg-slate-800/50 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-slate-300">Nama Kategori</th>
                        <th class="px-6 py-4 text-slate-300">Slug</th>
                        <th class="px-6 py-4 text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 font-semibold text-white">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('admin.product-attributes.index', $category->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 transition font-medium text-sm">🏷️
                                        Atribut</a>
                                    <button type="button" data-modal-target="editCategoryModal-{{ $category->id }}"
                                        data-modal-toggle="editCategoryModal-{{ $category->id }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 transition font-medium text-sm">✏️
                                        Edit</button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="text-slate-400">
                                    <p class="text-2xl mb-2">📭</p>
                                    <p>Belum ada data kategori</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </table>
        </x-table-card>

        <!-- Create Modal -->
        <div id="createCategoryModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
            <div class="relative w-full max-w-md max-h-full mx-auto">
                <div class="relative bg-slate-800 rounded-lg shadow-xl border border-slate-700">
                    <div class="flex items-start justify-between p-6 border-b border-slate-700">
                        <h3 class="text-xl font-bold text-white">➕ Tambah Kategori Baru</h3>
                        <button type="button" class="text-slate-400 hover:text-white transition"
                            data-modal-hide="createCategoryModal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-semibold text-white">Nama Kategori</label>
                                <input type="text" name="name" id="name" placeholder="Contoh: Elektronik"
                                    class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition"
                                    required>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-6 border-t border-slate-700">
                            <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg transition">Simpan
                                Kategori</button>
                            <button data-modal-hide="createCategoryModal" type="button"
                                class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-2.5 rounded-lg transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modals -->
        @foreach ($categories as $category)
            <div id="editCategoryModal-{{ $category->id }}" tabindex="-1" aria-hidden="true"
                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
                <div class="relative w-full max-w-md max-h-full mx-auto">
                    <div class="relative bg-slate-800 rounded-lg shadow-xl border border-slate-700">
                        <div class="flex items-start justify-between p-6 border-b border-slate-700">
                            <h3 class="text-xl font-bold text-white">✏️ Edit Kategori</h3>
                            <button type="button" class="text-slate-400 hover:text-white transition"
                                data-modal-hide="editCategoryModal-{{ $category->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="p-6 space-y-4">
                                <div>
                                    <label for="edit_name_{{ $category->id }}"
                                        class="block mb-2 text-sm font-semibold text-white">Nama Kategori</label>
                                    <input type="text" name="name" id="edit_name_{{ $category->id }}"
                                        value="{{ $category->name }}"
                                        class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                                        required>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-6 border-t border-slate-700">
                                <button type="submit"
                                    class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2.5 rounded-lg transition">Perbarui</button>
                                <button data-modal-hide="editCategoryModal-{{ $category->id }}" type="button"
                                    class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-2.5 rounded-lg transition">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

@endsection