@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-4xl">🏷️</span>
                <div>
                    <h1 class="text-4xl font-bold text-white">Atribut Produk - {{ $category->name }}</h1>
                    <p class="text-slate-400 text-sm">Kelola atribut produk seperti ukuran, warna, berat, dll</p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="p-4 mb-4 rounded-lg bg-emerald-500/20 border-l-4 border-emerald-500 text-emerald-100 flex gap-3 items-start" role="alert">
                <span class="text-xl mt-0.5">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 rounded-lg bg-rose-500/20 border-l-4 border-rose-500 text-rose-100 flex gap-3 items-start" role="alert">
                <span class="text-xl mt-0.5">!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Form untuk tambah/edit atribut -->
            <div class="lg:col-span-1">
                <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                        <span class="text-2xl">➕</span>
                        <h2 class="text-lg font-bold text-white">Tambah Atribut Baru</h2>
                    </div>

                    <form action="{{ route('admin.product-attributes.store', $category->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Nama Atribut -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                                Nama Atribut <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" name="name" id="name" placeholder="Contoh: Ukuran, Warna, Berat"
                                class="w-full px-4 py-2 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                                required>
                        </div>

                        <!-- Deskripsi Atribut -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
                                Deskripsi (Opsional)
                            </label>
                            <textarea name="description" id="description" placeholder="Contoh: Pilihan warna yang muncul pada pelanggan"
                                class="w-full px-4 py-2 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition resize-none"
                                rows="2"></textarea>
                        </div>

                        <!-- Tipe Atribut -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-300 mb-2">
                                Tipe Atribut <span class="text-rose-400">*</span>
                            </label>
                            <select name="type" id="type"
                                class="w-full px-4 py-2 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                                onchange="toggleOptionsField()"
                                required>
                                <option value="">Pilih Tipe</option>
                                <option value="text">Text (Input Teks)</option>
                                <option value="number">Number (Input Angka)</option>
                                <option value="select">Select (Pilihan Dropdown)</option>
                            </select>
                        </div>

                        <!-- Options (hanya untuk select) -->
                        <div id="optionsContainer" style="display: none;">
                            <label for="options" class="block text-sm font-medium text-slate-300 mb-2">
                                Pilihan (Pisahkan dengan koma)
                            </label>
                            <textarea name="options_text" id="options" placeholder="Contoh: S, M, L, XL"
                                class="w-full px-4 py-2 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition resize-none"
                                rows="3"></textarea>
                            <p class="text-xs text-slate-400 mt-1">Masukkan setiap pilihan dipisahkan dengan koma</p>
                        </div>

                        <!-- Required -->
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_required" id="is_required" value="1"
                                class="w-4 h-4 rounded bg-slate-700 border border-slate-600 focus:ring-sky-500 cursor-pointer">
                            <label for="is_required" class="text-sm font-medium text-slate-300 cursor-pointer">
                                Atribut Wajib Diisi
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition flex items-center justify-center gap-2">
                            <span>➕</span> Tambah Atribut
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Daftar atribut yang sudah ada -->
            <div class="lg:col-span-2">
                <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                        <span class="text-2xl">📋</span>
                        <h2 class="text-lg font-bold text-white">Daftar Atribut ({{ count($attributes) }})</h2>
                    </div>

                    @if ($attributes->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-slate-400 text-lg">Belum ada atribut untuk kategori ini</p>
                            <p class="text-slate-500 text-sm mt-2">Buat atribut pertama Anda dengan form di sebelah kiri</p>
                        </div>
                    @else
                        <div class="space-y-3" id="attributesList">
                            @foreach ($attributes as $attribute)
                                <div class="p-4 bg-slate-700/30 rounded-lg border border-slate-700 hover:bg-slate-700/50 transition group"
                                    data-attribute-id="{{ $attribute->id }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="drag-handle cursor-move text-slate-500 hover:text-slate-300">⋮⋮</span>
                                                <h3 class="font-semibold text-white text-lg">{{ $attribute->name }}</h3>
                                                @if ($attribute->is_required)
                                                    <span class="inline-block px-2 py-1 rounded text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                        Wajib
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 text-sm text-slate-400">
                                                <span class="px-2 py-1 rounded bg-slate-600/50">
                                                    @switch($attribute->type)
                                                        @case('text')
                                                            🔤 Text
                                                            @break
                                                        @case('number')
                                                            🔢 Number
                                                            @break
                                                        @case('select')
                                                            ✓ Select
                                                            @break
                                                    @endswitch
                                                </span>
                                                @if ($attribute->type === 'select' && $attribute->options)
                                                    <span>{{ count($attribute->options) }} pilihan</span>
                                                @endif
                                            </div>
                                            @if(!empty($attribute->description))
                                                <p class="text-xs text-slate-500 mt-2">{{ $attribute->description }}</p>
                                            @endif
                                            @if ($attribute->type === 'select' && $attribute->options)
                                                <p class="text-xs text-slate-500 mt-2">
                                                    <strong>Pilihan:</strong> {{ implode(', ', $attribute->options) }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                            <button type="button"
                                                class="px-3 py-1 rounded bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 font-medium transition text-sm"
                                                onclick="editAttribute({{ $attribute->id }})">
                                                ✏️ Edit
                                            </button>
                                            <form action="{{ route('admin.product-attributes.destroy', [$category->id, $attribute->id]) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Hapus atribut ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 rounded bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 font-medium transition text-sm">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <a href="{{ route('admin.categories.index') }}"
                            class="inline-block px-6 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition">
                            ← Kembali ke Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleOptionsField() {
            const type = document.getElementById('type').value;
            const optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.style.display = type === 'select' ? 'block' : 'none';
        }
        function editAttribute(attributeId) {
            const categoryId = {{ $category->id }};
            fetch(`/admin/categories/${categoryId}/attributes/${attributeId}/json`)
                .then(res => res.json())
                .then(attr => {
                    // populate modal fields
                    document.getElementById('edit_attribute_id').value = attr.id;
                    document.getElementById('edit_name').value = attr.name || '';
                    document.getElementById('edit_description').value = attr.description || '';
                    document.getElementById('edit_type').value = attr.type || '';
                    document.getElementById('edit_is_required').checked = !!attr.is_required;
                    document.getElementById('edit_order').value = attr.order || 0;
                    if (attr.options && Array.isArray(attr.options)) {
                        document.getElementById('edit_options').value = attr.options.join(', ');
                    } else {
                        document.getElementById('edit_options').value = '';
                    }

                    // show/hide options container
                    document.getElementById('edit_options_container').style.display = attr.type === 'select' ? 'block' : 'none';

                    // set form action
                    const form = document.getElementById('editAttributeForm');
                    form.action = `/admin/categories/${categoryId}/attributes/${attributeId}`;
                    // show modal
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal mengambil data atribut');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

    <!-- Edit Attribute Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-lg bg-slate-900 rounded-lg p-6 border border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Edit Atribut</h3>
                <button type="button" onclick="closeEditModal()" class="text-slate-400">✖</button>
            </div>
            <form id="editAttributeForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_attribute_id" name="attribute_id" />

                <div class="mb-3">
                    <label class="block text-sm text-slate-300 mb-1">Nama Atribut</label>
                    <input id="edit_name" name="name" class="w-full px-3 py-2 rounded bg-slate-800 text-white border border-slate-700" required />
                </div>
                <div class="mb-3">
                    <label class="block text-sm text-slate-300 mb-1">Deskripsi (opsional)</label>
                    <textarea id="edit_description" name="description" class="w-full px-3 py-2 rounded bg-slate-800 text-white border border-slate-700" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block text-sm text-slate-300 mb-1">Tipe</label>
                    <select id="edit_type" name="type" onchange="document.getElementById('edit_options_container').style.display = this.value === 'select' ? 'block' : 'none'" class="w-full px-3 py-2 rounded bg-slate-800 text-white border border-slate-700" required>
                        <option value="">Pilih tipe</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="select">Select</option>
                    </select>
                </div>
                <div id="edit_options_container" class="mb-3" style="display:none;">
                    <label class="block text-sm text-slate-300 mb-1">Pilihan (pisahkan dengan koma)</label>
                    <textarea id="edit_options" name="options_text" class="w-full px-3 py-2 rounded bg-slate-800 text-white border border-slate-700" rows="3"></textarea>
                </div>
                <div class="mb-3 flex items-center gap-3">
                    <input type="checkbox" id="edit_is_required" name="is_required" value="1" class="w-4 h-4" />
                    <label class="text-sm text-slate-300">Wajib diisi</label>
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-slate-300 mb-1">Urutan</label>
                    <input id="edit_order" name="order" type="number" class="w-24 px-3 py-2 rounded bg-slate-800 text-white border border-slate-700" />
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded bg-slate-700 text-white">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
