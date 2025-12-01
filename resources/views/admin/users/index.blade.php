@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <!-- Header -->
        <div class="flex gap-3 items-start mb-6">
            <span class="text-4xl">👥</span>
            <div>
                <h1 class="text-3xl font-bold text-white">Manajemen User</h1>
                <p class="text-slate-400 text-sm mt-1">Kelola akun pengguna sistem, role, dan status aktif</p>
            </div>
        </div>

        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert>{{ session('error') }}</x-alert>
        @endif

        <!-- Action Bar -->
        <div class="flex justify-between items-center mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700">
            <div class="text-slate-300 text-sm">
                Total: <span class="font-bold text-white">{{ $users->total() }}</span> user
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2.5 px-4 rounded-lg transition flex gap-2 items-center">
                ➕ Tambah User
            </a>
        </div>

        <x-table-card>
            <table class="w-full text-sm text-left text-slate-100">
                <thead class="text-xs uppercase font-bold bg-slate-800/50 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dibuat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 font-semibold text-slate-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-300 break-all">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <x-badge variant="admin">{{ ucfirst($user->role) }}</x-badge>
                                @elseif($user->role === 'manager')
                                    <x-badge variant="manager">{{ ucfirst($user->role) }}</x-badge>
                                @else
                                    <x-badge variant="staff">{{ ucfirst($user->role) }}</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <x-badge variant="active">✓ Aktif</x-badge>
                                @else
                                    <x-badge variant="inactive">✗ Nonaktif</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-300 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <x-action-buttons :editUrl="route('admin.users.edit', $user->id)"
                                    :deleteUrl="route('admin.users.destroy', $user->id)"
                                    confirm="Yakin ingin menghapus user ini?" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">📭</span>
                                    <p class="text-slate-400">Belum ada data user</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>
    </x-table-card>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
    </div>
@endsection