@extends('layouts.app')

@section('content')
    <div class="p-4 pt-4">
        <div class="flex gap-3 items-start mb-6">
            <span class="text-4xl">👤</span>
            <div>
                <h1 class="text-3xl font-bold text-white">Edit User</h1>
                <p class="text-slate-400 text-sm mt-1">Perbarui informasi akun. Kosongkan password jika tidak ingin
                    mengubahnya.</p>
            </div>
        </div>

        @if (session('error'))
            <div class="p-4 mb-4 rounded-lg bg-rose-500/20 border-l-4 border-rose-500 text-rose-100 flex gap-3 items-start"
                role="alert">
                <span class="text-xl mt-0.5">!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 mb-4 text-sm rounded-lg bg-rose-500/10 border-l-4 border-rose-500 text-rose-200">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30 p-6 w-full">

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="w-full">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-slate-200">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 rounded-md text-sm bg-slate-900/40 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"
                            required>
                        @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-slate-200">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 rounded-md text-sm bg-slate-900/40 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"
                            required>
                        @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-slate-200">Password Baru (Kosongkan
                            jika tidak ingin mengubah)</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 rounded-md text-sm bg-slate-900/40 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('password') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-slate-200">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 rounded-md text-sm bg-slate-900/40 border border-slate-700 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('password_confirmation') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-slate-200">Role</label>
                        <select name="role" id="role"
                            class="w-full px-4 py-3 rounded-md bg-slate-900/40 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin" @if(old('role', $user->role) === 'admin') selected @endif>Admin</option>
                            <option value="manager" @if(old('role', $user->role) === 'manager') selected @endif>Manager
                            </option>
                            <option value="staff" @if(old('role', $user->role) === 'staff') selected @endif>Staff</option>
                        </select>
                        @error('role') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div></div>
                    <div>
                        <label for="is_active" class="block mb-2 text-sm font-medium text-slate-200">Status</label>
                        <select name="is_active" id="is_active"
                            class="w-full px-4 py-3 rounded-md bg-slate-900/40 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500"
                            required>
                            <option value="1" @if(old('is_active', $user->is_active) == 1) selected @endif>Aktif</option>
                            <option value="0" @if(old('is_active', $user->is_active) == 0) selected @endif>Tidak Aktif
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-4 rounded-md">
                        Perbarui User
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center gap-2 bg-transparent border border-slate-700 text-slate-200 py-2 px-4 rounded-md hover:bg-slate-700/30">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection