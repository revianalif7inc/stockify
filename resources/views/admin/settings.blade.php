@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-4xl">⚙️</span>
                <h1 class="text-4xl font-bold text-white">Pengaturan Aplikasi</h1>
            </div>
            <p class="text-slate-400 text-sm">Kelola konfigurasi dan preferensi aplikasi Stockify Anda</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="p-4 mb-4 rounded-lg bg-emerald-500/20 border-l-4 border-emerald-500 text-emerald-100 flex gap-3 items-start"
                role="alert">
                <span class="text-xl mt-0.5">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 rounded-lg bg-rose-500/20 border-l-4 border-rose-500 text-rose-100 flex gap-3 items-start"
                role="alert">
                <span class="text-xl mt-0.5">!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Settings Form Container -->
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section: Identitas Aplikasi -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                    <span class="text-2xl">📱</span>
                    <h2 class="text-lg font-bold text-white">Identitas Aplikasi</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Aplikasi -->
                    <div class="md:col-span-2">
                        <label for="app_name" class="block text-sm font-medium text-slate-300 mb-2">
                            Nama Aplikasi
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <input type="text" id="app_name" name="app_name"
                            value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}"
                            placeholder="Masukkan nama aplikasi..."
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            required>
                        <p class="text-xs text-slate-500 mt-2">Nama ini akan ditampilkan di sidebar dan berbagai bagian
                            aplikasi</p>
                    </div>

                    <!-- Logo Aplikasi -->
                    <div class="md:col-span-2">
                        <label for="app_logo" class="block text-sm font-medium text-slate-300 mb-3">
                            Logo Aplikasi
                        </label>

                        <!-- Logo Preview -->
                        @if(!empty($settings['app_logo']))
                            <div class="mb-4 p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-3 font-medium">Logo Saat Ini:</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-16 h-16 bg-slate-700 rounded-lg p-2 flex items-center justify-center border border-slate-600">
                                            <img src="{{ asset('storage/' . $settings['app_logo']) }}"
                                                class="max-w-full max-h-full object-contain" alt="Current Logo">
                                        </div>
                                        <div>
                                            <p class="text-sm text-white font-medium mb-1">Logo Aktif</p>
                                            <p class="text-xs text-slate-400">Format: PNG, JPG, WebP</p>
                                        </div>
                                    </div>
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-rose-500/20 border border-rose-500/30 hover:bg-rose-500/30 cursor-pointer transition text-rose-300 text-sm font-medium">
                                        <input type="checkbox" name="remove_logo" value="1" class="w-4 h-4">
                                        Hapus Logo
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- File Input -->
                        <div class="border-2 border-dashed border-slate-700 rounded-lg p-6 text-center hover:border-slate-600 hover:bg-slate-800/30 transition cursor-pointer"
                            onclick="document.getElementById('logoInput').click()">
                            <input type="file" id="logoInput" name="app_logo" accept="image/*" class="hidden"
                                onchange="updateLogoPreview(this)">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-3xl">🖼️</span>
                                <p class="text-sm text-slate-300 font-medium">Klik untuk unggah logo baru</p>
                                <p class="text-xs text-slate-500">PNG, JPG, WebP (Maks. 2MB)</p>
                            </div>
                        </div>
                        <p id="logoFileName" class="text-xs text-slate-400 mt-2"></p>
                    </div>
                </div>
            </div>

            <!-- Section: Konfigurasi Stok -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700">
                    <span class="text-2xl">📊</span>
                    <h2 class="text-lg font-bold text-white">Konfigurasi Stok</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Default Min Stock -->
                    <div>
                        <label for="default_min_stock" class="block text-sm font-medium text-slate-300 mb-2">
                            Stok Minimum Default
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="default_min_stock" name="default_min_stock" min="1"
                                value="{{ old('default_min_stock', $settings['default_min_stock'] ?? 1) }}"
                                class="flex-1 px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                                required>
                            <span class="text-slate-400 text-sm font-medium">unit</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Nilai default stok minimum untuk produk baru</p>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-slate-300 mb-2">
                            Timezone
                            <span class="text-rose-400 ml-1">*</span>
                        </label>
                        <input type="text" id="timezone" name="timezone"
                            value="{{ old('timezone', $settings['timezone'] ?? config('app.timezone')) }}"
                            placeholder="Cth: Asia/Jakarta"
                            class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white border border-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 transition"
                            required>
                        <p class="text-xs text-slate-500 mt-2">Format: Benua/Kota (Cth: Asia/Jakarta)</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-6 py-3 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition flex items-center gap-2">
                    <span>←</span> Kembali
                </a>
                <button type="submit"
                    class="px-8 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition flex items-center gap-2 shadow-lg">
                    <span>💾</span> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateLogoPreview(input) {
            const fileName = input.files[0]?.name || '';
            const fileNameEl = document.getElementById('logoFileName');
            if (fileName) {
                fileNameEl.textContent = '✓ File dipilih: ' + fileName;
                fileNameEl.classList.add('text-emerald-400');
                fileNameEl.classList.remove('text-slate-400');
            } else {
                fileNameEl.textContent = '';
            }
        }
    </script>
@endsection