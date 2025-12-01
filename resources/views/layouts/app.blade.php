<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stockify - @if(Auth::user()?->role === 'admin') Admin @elseif(Auth::user()?->role === 'manager') Manager
    @else Staff @endif</title>
    {{-- Vite assets: use the Vite plugin so dev server or build output is loaded correctly --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="bg-slate-950 text-gray-100">

    {{-- Sidebar --}}
    <aside
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-6 transition-transform -translate-x-full sm:translate-x-0 bg-slate-900 border-r border-slate-700">
        <div class="px-6">
            <a href="@if(Auth::user()?->role === 'admin'){{ route('admin.dashboard') }}@elseif(Auth::user()?->role === 'manager'){{ route('manager.dashboard') }}@else{{ route('staff.dashboard') }}@endif"
                class="brand flex items-center mb-8">
                @php
                    $appLogo = \App\Models\Setting::get('app_logo');
                    $appName = \App\Models\Setting::get('app_name', config('app.name'));
                @endphp
                @if($appLogo)
                    <img src="{{ asset('storage/' . $appLogo) }}" alt="{{ $appName }}"
                        class="w-8 h-8 object-cover rounded mr-2">
                @else
                    <span class="text-2xl mr-2">📦</span>
                @endif
                <span class="text-xl font-bold text-white">{{ $appName }}</span>
            </a>

            <nav class="space-y-1">
                {{-- Dashboard Link (all roles) --}}
                <a href="@if(Auth::user()?->role === 'admin'){{ route('admin.dashboard') }}@elseif(Auth::user()?->role === 'manager'){{ route('manager.dashboard') }}@else{{ route('staff.dashboard') }}@endif"
                    class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500/20 {{ (request()->routeIs('admin.dashboard') || request()->routeIs('manager.dashboard') || request()->routeIs('staff.dashboard')) ? 'bg-blue-600 text-white shadow-md' : '' }}">
                    <span class="text-xl">📊</span>
                    <span>Dashboard</span>
                </a>

                {{-- ADMIN MENU --}}
                @if(Auth::user()?->role === 'admin')
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Manajemen</p>
                    </div>

                    <a href="{{ route('admin.categories.index') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📁</span>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📦</span>
                        <span>Daftar Produk</span>
                    </a>

                    <a href="{{ route('admin.products.create') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.products.create') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">➕</span>
                        <span>Tambah Produk</span>
                    </a>

                    <a href="{{ route('admin.suppliers.index') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">🏪</span>
                        <span>Supplier</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">👥</span>
                        <span>User</span>
                    </a>

                    <a href="{{ route('admin.settings') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.settings') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">⚙️</span>
                        <span>Pengaturan</span>
                    </a>

                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Laporan</p>
                    </div>

                    <a href="{{ route('admin.reports.stock') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.reports.stock') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📊</span>
                        <span>Laporan Stok</span>
                    </a>

                    <a href="{{ route('admin.reports.movement') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.reports.movement') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📈</span>
                        <span>Laporan Pergerakan</span>
                    </a>
                @endif

                {{-- MANAGER MENU --}}
                @if(Auth::user()?->role === 'manager')
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Transaksi</p>
                    </div>

                    <a href="{{ route('manager.stock.in') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.stock.in*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📥</span>
                        <span>Barang Masuk</span>
                    </a>

                    <a href="{{ route('manager.stock.out') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.stock.out*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📤</span>
                        <span>Barang Keluar</span>
                    </a>

                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Manajemen</p>
                    </div>

                    <a href="{{ route('manager.low_stock') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.low_stock') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">🚨</span>
                        <span>Stok Kritis</span>
                    </a>

                    <a href="{{ route('manager.products') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.products') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📦</span>
                        <span>Daftar Produk</span>
                    </a>

                    <a href="{{ route('manager.suppliers.index') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.suppliers.*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">🏢</span>
                        <span>Supplier</span>
                    </a>

                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Laporan</p>
                    </div>

                    <a href="{{ route('manager.reports.stock') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.reports.stock') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📊</span>
                        <span>Laporan Stok</span>
                    </a>

                    <a href="{{ route('manager.reports.movement') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('manager.reports.movement') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📈</span>
                        <span>Laporan Pergerakan</span>
                    </a>
                @endif

                {{-- STAFF MENU --}}
                @if(Auth::user()?->role === 'staff')
                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Transaksi</p>
                    </div>

                    <a href="{{ route('staff.stock.in') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('staff.stock.in*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📥</span>
                        <span>Barang Masuk</span>
                    </a>

                    <a href="{{ route('staff.stock.out') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('staff.stock.out*') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📤</span>
                        <span>Barang Keluar</span>
                    </a>

                    <div class="pt-4">
                        <p class="text-xs font-semibold text-slate-400 px-3 py-2 uppercase">Laporan</p>
                    </div>

                    <a href="{{ route('staff.history') }}"
                        class="block w-full px-3 py-3 rounded-r-2xl text-sm font-medium flex items-center gap-3 text-slate-200 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('staff.history') ? 'bg-blue-600 text-white shadow-md' : '' }}">
                        <span class="text-xl">📋</span>
                        <span>History Konfirmasi</span>
                    </a>

                @endif
            </nav>
        </div>
    </aside>

    {{-- Page content wrapper (push right to make room for sidebar) --}}
    <div class="sm:ml-64 min-h-screen">
        {{-- Topbar (fixed) --}}
        <header class="fixed top-0 left-0 right-0 z-30 bg-slate-900 border-b border-slate-700 h-16 sm:ml-64">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between h-full">
                <div class="flex items-center space-x-4">
                    <button id="mobile-open-btn"
                        class="sm:hidden px-2 py-1 rounded-md bg-slate-800 text-slate-200 hover:bg-slate-700">☰
                        Menu</button>
                    <h2 class="text-lg font-semibold text-white">@if(Auth::user()?->role === 'admin') Admin Panel
                    @elseif(Auth::user()?->role === 'manager') Manager Panel @else Staff Panel @endif</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-sm text-slate-300">{{ Auth::user()->name ?? 'User' }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-rose-400 hover:text-rose-300">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main content yield --}}
        <main class="pt-16">
            @yield('content')
        </main>
    </div>

    {{-- Flowbite / optional scripts and custom stacks --}}
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
    @stack('scripts')

    <script>
        // Simple mobile sidebar toggle
        document.getElementById('mobile-open-btn')?.addEventListener('click', function () {
            const aside = document.querySelector('aside');
            aside.classList.toggle('-translate-x-full');
        });
    </script>
</body>

</html>