@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        {{-- Header Section --}}
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-1">History Konfirmasi</h1>
            <p class="text-slate-400">Daftar barang yang sudah dikonfirmasi</p>
        </div>

        {{-- Filter Section --}}
        <div class="rounded-xl border border-slate-700/50 bg-slate-900 p-4">
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tipe</label>
                    <select name="type" class="w-full px-4 py-2 rounded-lg bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Mulai</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 rounded-lg bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Akhir</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 rounded-lg bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Cari
                    </button>
                    <a href="{{ route('staff.history') }}" class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Total Dikonfirmasi -->
            <div class="p-4 rounded-lg border border-blue-500/30 bg-blue-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-300 font-medium">Total Dikonfirmasi</p>
                        <p class="text-2xl font-bold text-blue-400 mt-2">{{ $confirmed->count() }}</p>
                    </div>
                    <div class="text-3xl text-blue-500/40">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
            </div>
            <!-- Barang Masuk -->
            <div class="p-4 rounded-lg border border-emerald-500/30 bg-emerald-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-300 font-medium">Barang Masuk</p>
                        <p class="text-2xl font-bold text-emerald-400 mt-2">{{ $confirmed->where('type', 'in')->count() }}</p>
                    </div>
                    <div class="text-3xl text-emerald-500/40">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>
            <!-- Barang Keluar -->
            <div class="p-4 rounded-lg border border-rose-500/30 bg-rose-950/40 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-rose-300 font-medium">Barang Keluar</p>
                        <p class="text-2xl font-bold text-rose-400 mt-2">{{ $confirmed->where('type', 'out')->count() }}</p>
                    </div>
                    <div class="text-3xl text-rose-500/40">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <x-table-card>
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
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
                    @forelse($confirmed as $index => $item)
                        <tr class="border-t border-slate-700 hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4 font-medium text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ $item->product->imageUrl ?? asset('images/no-image.svg') }}" alt="{{ $item->product->name ?? 'Produk' }}" class="w-12 h-12 object-cover rounded border border-slate-700">
                            </td>
                            <td class="px-6 py-4">
                                @if($item->type === 'in')
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
                                <div class="font-medium text-slate-100">{{ $item->product->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $item->product_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $item->type === 'in' ? 'bg-emerald-950/50 text-emerald-300' : 'bg-rose-950/50 text-rose-300' }} font-bold text-sm">{{ $item->quantity }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-100">{{ $item->user->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $item->user->role ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-100">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-lg mb-2">📋 Tidak ada data</p>
                                <p class="text-sm">Belum ada barang yang dikonfirmasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-table-card>

        {{-- Pagination --}}
        @if($confirmed instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="flex justify-center">
                {{ $confirmed->links() }}
            </div>
        @endif
    </div>
@endsection
