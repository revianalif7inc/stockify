@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="p-3 mb-4 rounded bg-emerald-700 text-white text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 rounded bg-rose-700 text-white text-sm">{{ session('error') }}</div>
        @endif

        {{-- Header Section --}}
        <div class="mb-12">
            <h1 class="text-5xl font-bold text-white mb-3">Dashboard Staff Gudang</h1>
            <p class="text-base text-gray-300">Selamat datang kembali, <span
                    class="font-semibold">{{ Auth::user()->name ?? 'Warehouse Staff' }}</span></p>
        </div>

        {{-- Statistics Cards (4 columns) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Total Products --}}
            <div class="dashboard-stat-card">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Total Produk</p>
                        <p class="text-4xl font-bold text-white">{{ $totalProducts ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Produk aktif</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">📦</div>
                </div>
            </div>

            {{-- Total Stock --}}
            <div class="dashboard-stat-card stat-info">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Total Stok</p>
                        <p class="text-4xl font-bold text-sky-300">{{ $totalStock ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Unit keseluruhan</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">📊</div>
                </div>
            </div>

            {{-- Barang Masuk Pending --}}
            <div class="dashboard-stat-card stat-success">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Barang Masuk</p>
                        <p class="text-4xl font-bold text-emerald-300">{{ $incomingPending ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Pending hari ini</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">📥</div>
                </div>
            </div>

            {{-- Stok Kritis --}}
            <div class="dashboard-stat-card stat-warning">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Stok Kritis</p>
                        <p class="text-4xl font-bold text-red-300">{{ $lowStockProducts ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Perlu diperhatikan</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">⚠️</div>
                </div>
            </div>
        </div>

        {{-- Quick Actions Section --}}
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-white mb-5 flex items-center"><span class="mr-2">⚡</span> Akses Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Barang Masuk --}}
                <a href="{{ route('staff.stock.in') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📥</div>
                        <span
                            class="text-xs font-semibold bg-emerald-500/20 text-emerald-300 px-2.5 py-1 rounded-full">Transaksi</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Barang Masuk</h3>
                    <p class="text-sm text-slate-400">Catat penerimaan stok</p>
                </a>

                {{-- Barang Keluar --}}
                <a href="{{ route('staff.stock.out') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📤</div>
                        <span
                            class="text-xs font-semibold bg-rose-500/20 text-rose-300 px-2.5 py-1 rounded-full">Transaksi</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Barang Keluar</h3>
                    <p class="text-sm text-slate-400">Catat pengeluaran stok</p>
                </a>

                {{-- Lihat Tugas --}}
                <a href="{{ route('staff.tasks') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📋</div>
                        <span
                            class="text-xs font-semibold bg-blue-500/20 text-blue-300 px-2.5 py-1 rounded-full">Tugas</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Daftar Tugas</h3>
                    <p class="text-sm text-slate-400">Lihat semua tugas harian</p>
                </a>

                {{-- History Konfirmasi --}}
                <a href="{{ route('staff.history') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">✅</div>
                        <span
                            class="text-xs font-semibold bg-purple-500/20 text-purple-300 px-2.5 py-1 rounded-full">Laporan</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">History Konfirmasi</h3>
                    <p class="text-sm text-slate-400">Lihat riwayat konfirmasi</p>
                </a>
            </div>
        </div>

        {{-- Tasks Summary Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Barang Masuk Perlu Diperiksa --}}
            <div class="content-card">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white flex items-center"><span class="mr-2">📥</span> Barang Masuk
                        Perlu Diperiksa</h2>
                    <a href="{{ route('staff.stock.in') }}"
                        class="text-sky-400 hover:text-sky-300 text-xs font-semibold transition">Lihat Semua →</a>
                </div>
                <div class="space-y-3">
                    @if(isset($incoming) && $incoming->count() > 0)
                        @foreach($incoming->take(5) as $item)
                            <div
                                class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition">
                                <img src="{{ $item->product->imageUrl ?? asset('images/no-image.svg') }}"
                                    alt="{{ $item->product->name }}" class="w-10 h-10 object-cover rounded mr-3">
                                <div class="flex-1">
                                    <p class="text-white text-sm font-semibold">{{ $item->product->name ?? 'Produk' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Qty: {{ $item->quantity }}</p>
                                </div>
                                <a href="{{ route('staff.stock.in.confirm', $item->id) }}"
                                    class="text-xs font-semibold bg-emerald-500/20 text-emerald-300 px-2.5 py-1 rounded ml-3 hover:bg-emerald-500/30 transition">Periksa</a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <p class="text-slate-500 text-sm">Tidak ada barang masuk yang perlu diperiksa</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Barang Keluar Perlu Disiapkan --}}
            <div class="content-card">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white flex items-center"><span class="mr-2">📤</span> Barang Keluar
                        Perlu Disiapkan</h2>
                    <a href="{{ route('staff.stock.out') }}"
                        class="text-sky-400 hover:text-sky-300 text-xs font-semibold transition">Lihat Semua →</a>
                </div>
                <div class="space-y-3">
                    @if(isset($outgoing) && $outgoing->count() > 0)
                        @foreach($outgoing->take(5) as $item)
                            <div
                                class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition">
                                <img src="{{ $item->product->imageUrl ?? asset('images/no-image.svg') }}"
                                    alt="{{ $item->product->name }}" class="w-10 h-10 object-cover rounded mr-3">
                                <div class="flex-1">
                                    <p class="text-white text-sm font-semibold">{{ $item->product->name ?? 'Produk' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Qty: {{ $item->quantity }}</p>
                                </div>
                                <a href="{{ route('staff.stock.out.confirm', $item->id) }}"
                                    class="text-xs font-semibold bg-rose-500/20 text-rose-300 px-2.5 py-1 rounded ml-3 hover:bg-rose-500/30 transition">Siapkan</a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <p class="text-slate-500 text-sm">Tidak ada barang keluar yang perlu disiapkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection