@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header -->
        <div class="flex gap-3 items-start mb-6">
            <span class="text-4xl">🏢</span>
            <div>
                <h1 class="text-3xl font-bold text-white">Daftar Supplier</h1>
                <p class="text-slate-400 text-sm mt-1">Lihat informasi semua supplier yang tersedia</p>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700">
            <div class="text-slate-300 text-sm">
                Total: <span class="font-bold text-white">{{ $suppliers->total() }}</span> supplier
            </div>
        </div>

        <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-100">
                    <thead class="text-xs uppercase font-bold bg-slate-800/50 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Nama Supplier</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Alamat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse ($suppliers as $supplier)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 font-semibold text-slate-100">{{ $supplier->name }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ $supplier->phone ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-300 break-all">{{ $supplier->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-300">
                                    <span class="line-clamp-2">{{ $supplier->address ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-4xl">📭</span>
                                        <p class="text-slate-400">Belum ada data supplier</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
            <div class="mt-6">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
@endsection