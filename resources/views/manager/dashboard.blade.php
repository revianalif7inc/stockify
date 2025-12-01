@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-12">
            <h1 class="text-5xl font-bold text-white mb-3">Dashboard Manager</h1>
            <p class="text-base text-gray-300">Selamat datang kembali, <span
                    class="font-semibold">{{ Auth::user()->name ?? 'Manajer' }}</span></p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total Products -->
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

            <!-- Total Stock -->
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

            <!-- Stock In This Month -->
            <div class="dashboard-stat-card stat-success">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Barang Masuk</p>
                        <p class="text-4xl font-bold text-emerald-300">{{ $stockInThisMonth ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Bulan ini</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">📥</div>
                </div>
            </div>

            <!-- Low Stock Products -->
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

        <!-- Quick Actions Section -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-white mb-5 flex items-center"><span class="mr-2">⚡</span> Akses Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Monitoring Stok -->
                <a href="{{ route('manager.stock.monitoring') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📊</div>
                        <span
                            class="text-xs font-semibold bg-blue-500/20 text-blue-300 px-2.5 py-1 rounded-full">Pantau</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Monitoring Stok</h3>
                    <p class="text-sm text-slate-400">Pantau status stok real-time</p>
                </a>

                <!-- Barang Masuk -->
                <a href="{{ route('manager.stock.in') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📥</div>
                        <span
                            class="text-xs font-semibold bg-emerald-500/20 text-emerald-300 px-2.5 py-1 rounded-full">Transaksi</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Barang Masuk</h3>
                    <p class="text-sm text-slate-400">Catat penerimaan stok</p>
                </a>

                <!-- Barang Keluar -->
                <a href="{{ route('manager.stock.out') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📤</div>
                        <span
                            class="text-xs font-semibold bg-rose-500/20 text-rose-300 px-2.5 py-1 rounded-full">Transaksi</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Barang Keluar</h3>
                    <p class="text-sm text-slate-400">Catat pengeluaran stok</p>
                </a>

                <!-- Produk Kritis -->
                <a href="{{ route('manager.low_stock') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">🚨</div>
                        <span
                            class="text-xs font-semibold bg-red-500/20 text-red-300 px-2.5 py-1 rounded-full">Perhatian</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Stok Kritis</h3>
                    <p class="text-sm text-slate-400">Produk yang perlu di-reorder</p>
                </a>

                <!-- Laporan Stok -->
                <a href="{{ route('manager.reports.stock') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📋</div>
                        <span
                            class="text-xs font-semibold bg-amber-500/20 text-amber-300 px-2.5 py-1 rounded-full">Laporan</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Laporan Stok</h3>
                    <p class="text-sm text-slate-400">Lihat laporan stok barang</p>
                </a>

                <!-- Laporan Pergerakan -->
                <a href="{{ route('manager.reports.movement') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">🔄</div>
                        <span
                            class="text-xs font-semibold bg-purple-500/20 text-purple-300 px-2.5 py-1 rounded-full">Laporan</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Laporan Pergerakan</h3>
                    <p class="text-sm text-slate-400">Lihat riwayat pergerakan stok</p>
                </a>
            </div>
        </div>

        <!-- Recent Activity & System Info Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Low Stock Products -->
            <div class="content-card">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white flex items-center"><span class="mr-2">🚨</span> Produk Stok
                        Minimum
                    </h2>
                    <a href="{{ route('manager.low_stock') }}"
                        class="text-sky-400 hover:text-sky-300 text-xs font-semibold transition">Lihat Semua →</a>
                </div>
                <div class="space-y-3">
                    @if(isset($lowStockProductsList) && count($lowStockProductsList) > 0)
                        @foreach($lowStockProductsList as $product)
                            <div
                                class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg hover:bg-slate-700/50 transition">
                                <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                    class="w-10 h-10 object-cover rounded mr-3">
                                <div class="flex-1">
                                    <p class="text-white text-sm font-semibold">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Min: {{ $product->min_stock }} unit</p>
                                </div>
                                <span
                                    class="text-xs font-semibold bg-rose-500/20 text-rose-300 px-2.5 py-1 rounded ml-3 whitespace-nowrap">
                                    {{ $product->current_stock }} unit
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <p class="text-slate-500 text-sm">Tidak ada produk dengan stok kritis</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Monthly Summary -->
            <div class="content-card">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white flex items-center"><span class="mr-2">📈</span> Ringkasan Bulan
                        Ini
                    </h2>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 text-sm">Total Barang Masuk</span>
                        <span class="text-emerald-400 text-sm font-semibold">{{ $stockInThisMonth ?? 0 }} unit</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 text-sm">Total Barang Keluar</span>
                        <span class="text-rose-400 text-sm font-semibold">{{ $stockOutThisMonth ?? 0 }} unit</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 text-sm">Produk dengan Stok Aman</span>
                        <span class="text-blue-400 text-sm font-semibold">{{ $safeStockProducts ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 text-sm">Total Kategori</span>
                        <span class="text-sky-400 text-sm font-semibold">{{ $totalCategories ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection