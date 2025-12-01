@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header -->
        <div class="flex gap-3 items-start mb-6">
            <span class="text-4xl">📈</span>
            <div>
                <h1 class="text-3xl font-bold text-white">Laporan Pergerakan Stok</h1>
                <p class="text-slate-400 text-sm mt-1">Pantau semua transaksi stok masuk dan keluar</p>
            </div>
        </div>

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        <!-- Filter Section -->
        <div class="mb-6 p-6 rounded-lg border border-slate-700 bg-slate-800/30">
            <h3 class="text-lg font-semibold mb-4 text-slate-100">🔍 Filter Laporan</h3>
            <form method="GET" action="{{ route('manager.reports.movement') }}" class="space-y-4">
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-semibold text-slate-200">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 rounded-lg text-sm border focus:outline-none focus:ring-2">
                    </div>

                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-semibold text-slate-200">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 rounded-lg text-sm border focus:outline-none focus:ring-2">
                    </div>

                    <div>
                        <label for="type" class="block mb-2 text-sm font-semibold text-slate-200">Tipe Pergerakan</label>
                        <select name="type" id="type" class="w-full px-4 py-2 rounded-lg text-sm border focus:outline-none focus:ring-2">
                            <option value="">Semua Tipe</option>
                            <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stok Masuk</option>
                            <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stok Keluar</option>
                        </select>
                    </div>

                    <div class="flex gap-2 items-end">
                        <button type="submit" class="flex-1 text-white bg-emerald-600 hover:bg-emerald-700 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                            <i class="fas fa-search mr-2"></i> Filter
                        </button>
                        <a href="{{ route('manager.reports.movement') }}" class="flex-1 text-center text-slate-200 bg-slate-700 hover:bg-slate-600 font-medium rounded-lg text-sm px-5 py-2.5 transition">
                            <i class="fas fa-redo mr-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 rounded-lg border border-emerald-500/30 bg-emerald-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-300 font-medium">Total Stok Masuk</p>
                        <p class="text-2xl font-bold text-emerald-400 mt-2">{{ $movements->where('type', 'in')->sum('quantity') }} unit</p>
                    </div>
                    <div class="text-3xl text-emerald-500/40"><i class="fas fa-arrow-down"></i></div>
                </div>
            </div>

            <div class="p-4 rounded-lg border border-rose-500/30 bg-rose-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-rose-300 font-medium">Total Stok Keluar</p>
                        <p class="text-2xl font-bold text-rose-400 mt-2">{{ $movements->where('type', 'out')->sum('quantity') }} unit</p>
                    </div>
                    <div class="text-3xl text-rose-500/40"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>

            <div class="p-4 rounded-lg border border-blue-500/30 bg-blue-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-300 font-medium">Total Transaksi</p>
                        <p class="text-2xl font-bold text-blue-400 mt-2">{{ $movements->count() }} transaksi</p>
                    </div>
                    <div class="text-3xl text-blue-500/40"><i class="fas fa-exchange-alt"></i></div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="rounded-lg border border-slate-600 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-4 w-1/12">No.</th>
                            <th scope="col" class="px-6 py-4 w-1/12">Gambar</th>
                            <th scope="col" class="px-6 py-4 w-2/12">Tipe</th>
                            <th scope="col" class="px-6 py-4 w-3/12">Produk</th>
                            <th scope="col" class="px-6 py-4 w-1/12 text-center">Kuantitas</th>
                            <th scope="col" class="px-6 py-4 w-2/12">User</th>
                            <th scope="col" class="px-6 py-4 w-3/12">Tanggal & Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $index => $movement)
                            <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                                <td class="px-6 py-4 font-medium text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ $movement->product ? $movement->product->imageUrl : asset('images/no-image.svg') }}" alt="{{ $movement->product->name ?? 'No Image' }}" class="w-12 h-12 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    @if ($movement->type === 'in')
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/50 border border-emerald-500/50">
                                            <i class="fas fa-arrow-down text-emerald-400"></i>
                                            <span class="text-emerald-300 font-semibold">MASUK</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-950/50 border border-rose-500/50">
                                            <i class="fas fa-arrow-up text-rose-400"></i>
                                            <span class="text-rose-300 font-semibold">KELUAR</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $movement->product->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">ID: {{ $movement->product_id }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $movement->type === 'in' ? 'bg-emerald-950/50 text-emerald-300' : 'bg-rose-950/50 text-rose-300' }} font-bold text-sm">{{ $movement->quantity }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $movement->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $movement->user->role ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-100">{{ $movement->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $movement->created_at->format('H:i') }} WIB</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-inbox text-2xl text-slate-600"></i>
                                        <p>Tidak ada pergerakan stok dalam periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Info -->
        @if ($movements->count() > 0)
            <div class="mt-4 p-4 rounded-lg bg-slate-900 border border-slate-600 text-center text-slate-300">
                <p>Menampilkan <strong>{{ $movements->count() }}</strong> data pergerakan stok</p>
            </div>
        @endif
    </div>
@endsection