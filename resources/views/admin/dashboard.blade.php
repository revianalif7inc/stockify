@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header Section -->
        <div class="mb-12">
            <h1 class="text-5xl font-bold text-white mb-3">Dashboard Admin</h1>
            <p class="text-base text-gray-300">Selamat datang kembali, <span
                    class="font-semibold">{{ Auth::user()->name }}</span></p>
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

            <!-- Total Categories -->
            <div class="dashboard-stat-card stat-success">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Kategori</p>
                        <p class="text-4xl font-bold text-emerald-300">{{ $totalCategories ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Total kategori</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">📂</div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="dashboard-stat-card stat-info">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-400 mb-1">Pengguna</p>
                        <p class="text-4xl font-bold text-sky-300">{{ $totalUsers ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-3">Pengguna aktif</p>
                    </div>
                    <div class="text-5xl ml-4 opacity-80">👥</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-white mb-5 flex items-center"><span class="mr-2">⚡</span> Akses Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Manage Categories -->
                <a href="{{ route('admin.categories.index') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📂</div>
                        <span
                            class="text-xs font-semibold bg-blue-500/20 text-blue-300 px-2.5 py-1 rounded-full">Manajemen</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Kategori</h3>
                    <p class="text-sm text-slate-400">Kelola kategori produk</p>
                </a>

                <!-- View All Products -->
                <a href="{{ route('admin.products.index') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📦</div>
                        <span class="text-xs font-semibold bg-blue-500/20 text-blue-300 px-2.5 py-1 rounded-full">Lihat
                            Semua</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Produk</h3>
                    <p class="text-sm text-slate-400">Lihat daftar produk</p>
                </a>

                <!-- Add Product -->
                <a href="{{ route('admin.products.create') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">➕</div>
                        <span
                            class="text-xs font-semibold bg-green-500/20 text-green-300 px-2.5 py-1 rounded-full">Baru</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Tambah Produk</h3>
                    <p class="text-sm text-slate-400">Produk baru ke sistem</p>
                </a>

                <!-- Manage Suppliers -->
                <a href="{{ route('admin.suppliers.index') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">🏢</div>
                        <span
                            class="text-xs font-semibold bg-amber-500/20 text-amber-300 px-2.5 py-1 rounded-full">Manajemen</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Supplier</h3>
                    <p class="text-sm text-slate-400">Kelola supplier</p>
                </a>

                <!-- Manage Users -->
                <a href="{{ route('admin.users.index') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">👤</div>
                        <span
                            class="text-xs font-semibold bg-purple-500/20 text-purple-300 px-2.5 py-1 rounded-full">Manajemen</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Pengguna</h3>
                    <p class="text-sm text-slate-400">Kelola pengguna</p>
                </a>

                <!-- Stock Report -->
                <a href="{{ route('admin.reports.stock') }}" class="quick-action-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="text-4xl">📊</div>
                        <span
                            class="text-xs font-semibold bg-rose-500/20 text-rose-300 px-2.5 py-1 rounded-full">Laporan</span>
                    </div>
                    <h3 class="font-bold text-base text-white mb-1">Laporan Stok</h3>
                    <p class="text-sm text-slate-400">Lihat status stok</p>
                </a>
            </div>
        </div>

        <!-- Charts Section - Full Width -->
        <div class="mb-10">
            <!-- Unified Chart Card (combined / stock / sales / category) -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 mb-6 p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-xl font-bold text-white flex items-center"><span class="mr-2">📊</span> Grafik Ringkas
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="flex gap-2 bg-slate-800/50 p-1 rounded-lg border border-slate-700">
                            <button id="stockViewBtn"
                                class="px-3 py-1.5 rounded text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition"
                                onclick="setUnifiedView('stock')">📦 Grafik Stok</button>
                            <div class="flex items-center">
                                <button id="salesDailyBtn"
                                    class="px-3 py-1.5 rounded text-xs font-semibold text-slate-400 hover:bg-slate-700 transition"
                                    onclick="setUnifiedView('sales', 'daily')">📈 Penjualan Harian</button>
                                <button id="salesMonthlyBtn"
                                    class="px-3 py-1.5 rounded text-xs font-semibold text-slate-400 hover:bg-slate-700 transition ml-1"
                                    onclick="setUnifiedView('sales', 'monthly')">📆 Penjualan Bulanan</button>
                            </div>
                            <button id="categoryViewBtn"
                                class="px-3 py-1.5 rounded text-xs font-semibold text-slate-400 hover:bg-slate-700 transition"
                                onclick="setUnifiedView('category')">🏷️ Kategori</button>
                        </div>
                    </div>
                </div>
                <div class="w-full" style="height: 420px;">
                    <canvas id="mainUnifiedChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Transactions and Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            <!-- Transactions Card -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-700">
                    <h3 class="text-lg font-bold text-white flex items-center"><span class="mr-2">💰</span> Transaksi (7
                        hari)</h3>
                    <div class="text-xs text-slate-400">{{ isset($periodStart) ? $periodStart->format('d M') : '' }} -
                        {{ isset($periodEnd) ? $periodEnd->format('d M') : '' }}
                    </div>
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-1 p-4 bg-emerald-500/15 border border-emerald-500/30 rounded-lg">
                        <p class="text-xs text-emerald-300 font-medium uppercase mb-1">Barang Masuk</p>
                        <p class="text-3xl font-bold text-emerald-300">{{ $totalIn ?? 0 }}</p>
                    </div>
                    <div class="flex-1 p-4 bg-rose-500/15 border border-rose-500/30 rounded-lg">
                        <p class="text-xs text-rose-300 font-medium uppercase mb-1">Barang Keluar</p>
                        <p class="text-3xl font-bold text-rose-300">{{ $totalOut ?? 0 }}</p>
                    </div>
                </div>
                <div class="p-4 bg-yellow-500/15 border border-yellow-500/30 rounded-lg">
                    <p class="text-xs text-yellow-300 font-medium uppercase mb-1">Total Penjualan</p>
                    <p class="text-2xl font-bold text-yellow-300">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Recent Activities Card -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-700">
                    <h3 class="text-lg font-bold text-white flex items-center"><span class="mr-2">🔔</span> Aktivitas
                        Terbaru</h3>
                    <a href="{{ route('admin.users.index') }}"
                        class="text-sky-400 hover:text-sky-300 text-xs font-semibold">→</a>
                </div>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @if(!empty($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $act)
                            <div class="p-3 bg-slate-800/40 rounded-lg hover:bg-slate-700/40 transition border border-slate-700/40">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm text-white font-semibold truncate">{{ $act->user_name }}</div>
                                        <div class="text-xs text-slate-400 truncate">{{ $act->action }}</div>
                                    </div>
                                    <div class="text-xs text-slate-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($act->created_at)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6 text-slate-400 text-sm">Tidak ada aktivitas</div>
                    @endif
                </div>
            </div>

            <!-- Top Selling Products Card -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-700">
                    <h3 class="text-lg font-bold text-white flex items-center"><span class="mr-2">⭐</span> Penjualan
                        Terbanyak</h3>
                    <a href="{{ route('admin.products.index') }}"
                        class="text-sky-400 hover:text-sky-300 text-xs font-semibold">→</a>
                </div>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @if(!empty($topSellingProducts) && count($topSellingProducts) > 0)
                        @foreach($topSellingProducts as $prod)
                            @php
                                $qty = isset($topSelling[$prod->id]) ? $topSelling[$prod->id] : 0;
                            @endphp
                            <div
                                class="flex items-center justify-between p-3 bg-slate-800/40 rounded-lg hover:bg-slate-700/40 transition border border-slate-700/40">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <img src="{{ $prod->imageUrl }}" alt="{{ $prod->name }}"
                                        class="w-9 h-9 object-cover rounded flex-shrink-0">
                                    <div class="min-w-0">
                                        <div class="text-white text-sm font-semibold truncate">{{ substr($prod->name, 0, 15) }}
                                        </div>
                                        <div class="text-xs text-slate-400">{{ $prod->category->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-amber-300 whitespace-nowrap ml-2">{{ $qty }} unit</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6 text-slate-400 text-sm">Tidak ada data penjualan</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Products & System Info Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Recent Products -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white flex items-center"><span class="mr-2">📦</span> Produk Terbaru
                    </h2>
                    <a href="{{ route('admin.products.index') }}"
                        class="text-sky-400 hover:text-sky-300 text-xs font-semibold transition">Lihat Semua →</a>
                </div>
                <div class="space-y-3">
                    @if(isset($recentProducts) && count($recentProducts) > 0)
                        @foreach($recentProducts as $product)
                            <div
                                class="flex items-center justify-between p-3 bg-slate-800/40 rounded-lg hover:bg-slate-700/40 transition border border-slate-700/40">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                        class="w-10 h-10 object-cover rounded flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-white text-sm font-semibold truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $product->category->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span
                                    class="text-xs font-semibold bg-sky-500/30 text-sky-300 px-3 py-1.5 rounded-full whitespace-nowrap ml-2">{{ $product->current_stock }}
                                    unit</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <p class="text-slate-500 text-sm">Tidak ada produk</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Info -->
            <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white flex items-center"><span class="mr-2">⚙️</span> Informasi Sistem
                    </h2>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-4 bg-blue-500/15 border border-blue-500/30 rounded-lg">
                        <p class="text-xs text-blue-300 font-medium uppercase mb-1">Versi Sistem</p>
                        <p class="text-lg font-bold text-blue-300">v1.0.0</p>
                    </div>
                    <div class="p-4 bg-emerald-500/15 border border-emerald-500/30 rounded-lg">
                        <p class="text-xs text-emerald-300 font-medium uppercase mb-1">Status Server</p>
                        <p class="text-lg font-bold text-emerald-300">🟢 Online</p>
                    </div>
                    <div class="p-4 bg-purple-500/15 border border-purple-500/30 rounded-lg">
                        <p class="text-xs text-purple-300 font-medium uppercase mb-1">Mode Aplikasi</p>
                        <p class="text-lg font-bold text-purple-300">Production</p>
                    </div>
                    <div class="p-4 bg-orange-500/15 border border-orange-500/30 rounded-lg">
                        <p class="text-xs text-orange-300 font-medium uppercase mb-1">Last Update</p>
                        <p class="text-sm font-bold text-orange-300">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
        <script>
            // Unified chart: supports modes 'combined' (default), 'stock', 'sales' (daily/monthly), 'category'
            let mainChart = null;
            let currentView = 'combined';
            let currentSalesMode = 'daily';

            // Data from controller (productChartLabels/Data/Sales are now categories-based)
            const productLabels = @json($productChartLabels ?? []);
            const productStocks = @json($productChartData ?? []);
            const productSales = @json($productChartSales ?? []);
            const dailyLabels = @json($dailySalesLabels ?? []);
            const dailyData = @json($dailySalesData ?? []);
            const monthlyLabels = @json($monthlySalesLabels ?? []);
            const monthlyData = @json($monthlySalesData ?? []);

            function updateButtonStates() {
                // reset all
                const btns = ['stockViewBtn', 'salesDailyBtn', 'salesMonthlyBtn', 'categoryViewBtn'];
                btns.forEach(id => {
                    const b = document.getElementById(id);
                    if (!b) return;
                    b.classList.remove('bg-blue-600', 'text-white');
                    b.classList.add('text-slate-400');
                });

                if (currentView === 'stock') {
                    document.getElementById('stockViewBtn').classList.add('bg-blue-600', 'text-white');
                } else if (currentView === 'sales') {
                    if (currentSalesMode === 'daily') document.getElementById('salesDailyBtn').classList.add('bg-blue-600', 'text-white');
                    else document.getElementById('salesMonthlyBtn').classList.add('bg-blue-600', 'text-white');
                } else if (currentView === 'category') {
                    document.getElementById('categoryViewBtn').classList.add('bg-blue-600', 'text-white');
                }
            }

            function renderUnifiedChart() {
                if (mainChart) mainChart.destroy();
                const ctx = document.getElementById('mainUnifiedChart').getContext('2d');

                if (currentView === 'combined') {
                    mainChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: productLabels,
                            datasets: [
                                {
                                    label: 'Stok (unit)',
                                    data: productStocks,
                                    backgroundColor: 'rgba(56,189,248,0.75)',
                                    borderColor: 'rgba(56,189,248,1)',
                                    yAxisID: 'y',
                                },
                                {
                                    label: 'Penjualan (Rp)',
                                    data: productSales,
                                    type: 'line',
                                    borderColor: 'rgba(250,204,21,1)',
                                    backgroundColor: 'rgba(250,204,21,0.15)',
                                    fill: true,
                                    yAxisID: 'y1',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: { legend: { labels: { color: '#e2e8f0' } } },
                            scales: {
                                y: { beginAtZero: true, ticks: { color: '#94a3b8' } },
                                y1: { position: 'right', beginAtZero: true, ticks: { color: '#94a3b8' }, grid: { drawOnChartArea: false } },
                                x: { ticks: { color: '#94a3b8' } }
                            }
                        }
                    });
                } else if (currentView === 'stock') {
                    mainChart = new Chart(ctx, {
                        type: 'bar',
                        data: { labels: productLabels, datasets: [{ label: 'Stok (unit)', data: productStocks, backgroundColor: 'rgba(56,189,248,0.75)' }] },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: '#e2e8f0' } } }, scales: { y: { beginAtZero: true, ticks: { color: '#94a3b8' } }, x: { ticks: { color: '#94a3b8' } } } }
                    });
                } else if (currentView === 'sales') {
                    if (currentSalesMode === 'daily') {
                        mainChart = new Chart(ctx, { type: 'line', data: { labels: dailyLabels, datasets: [{ label: 'Penjualan Harian (Rp)', data: dailyData, borderColor: 'rgba(34,197,94,1)', backgroundColor: 'rgba(34,197,94,0.15)', fill: true }] }, options: { responsive: true, maintainAspectRatio: false } });
                    } else {
                        mainChart = new Chart(ctx, { type: 'bar', data: { labels: monthlyLabels, datasets: [{ label: 'Penjualan Bulanan (Rp)', data: monthlyData, backgroundColor: 'rgba(59,130,246,0.75)' }] }, options: { responsive: true, maintainAspectRatio: false } });
                    }
                } else if (currentView === 'category') {
                    mainChart = new Chart(ctx, { type: 'bar', data: { labels: productLabels, datasets: [{ label: 'Penjualan per Kategori (Rp)', data: productSales, backgroundColor: 'rgba(79,70,229,0.75)' }] }, options: { responsive: true, maintainAspectRatio: false } });
                }
            }

            function setUnifiedView(view, salesMode = null) {
                if (view === 'sales' && salesMode) currentSalesMode = salesMode;
                currentView = view;
                updateButtonStates();
                renderUnifiedChart();
            }

            document.addEventListener('DOMContentLoaded', function () {
                // default is stock view
                currentView = 'stock';
                currentSalesMode = 'daily';
                updateButtonStates();
                renderUnifiedChart();
            });
        </script>
@endsection