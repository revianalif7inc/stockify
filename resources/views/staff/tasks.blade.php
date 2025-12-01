@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <h1 class="text-3xl font-bold text-white mb-4">Tugas Saya</h1>
        <p class="text-slate-400 mb-6">Daftar tugas untuk staff</p>

        <div class="rounded-lg border border-slate-600 overflow-hidden p-6 bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase font-semibold bg-slate-800 border-b border-slate-700">
                        <tr>
                            <th class="px-4 py-3">Gambar</th>
                            <th class="px-4 py-3">Tugas</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr class="border-t border-slate-700 hover:bg-slate-900/50">
                                <td class="px-4 py-3"><img
                                        src="{{ $task['movement']->product->imageUrl ?? asset('images/no-image.svg') }}"
                                        alt="{{ $task['movement']->product->name ?? 'Produk' }}"
                                        class="w-10 h-10 object-cover rounded"></td>
                                <td class="px-4 py-3 text-white">{{ $task['title'] }}</td>
                                <td class="px-4 py-3 text-slate-300">
                                    {{ $task['movement']->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i') }} WIB
                                </td>
                                <td class="px-4 py-3 text-slate-300">{{ $task['movement']->quantity }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $task['type'] == 'in' ? 'Masuk' : 'Keluar' }}</td>
                                <td class="px-4 py-3">
                                    @php $status = $task['status'] ?? ($task['movement']->status ?? 'pending'); @endphp
                                    @if($status === 'approved')
                                        <span class="px-2 py-1 rounded bg-emerald-600 text-white text-xs">Approved</span>
                                    @elseif($status === 'rejected')
                                        <span class="px-2 py-1 rounded bg-red-600 text-white text-xs">Rejected</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-yellow-600 text-white text-xs">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($task['type'] == 'in')
                                        <a href="{{ route('staff.stock.in.confirm', $task['movement']->id) }}"
                                            class="text-sky-400">Konfirmasi</a>
                                    @else
                                        <a href="{{ route('staff.stock.out.confirm', $task['movement']->id) }}"
                                            class="text-sky-400">Konfirmasi</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-slate-400">Tidak ada tugas hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection