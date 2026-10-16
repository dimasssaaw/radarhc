@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <p class="text-sm text-gray-500 mt-1">Manage system user access, roles, and statuses</p>
    </div>
    <div class="flex gap-3">
        @can('tambah_user')
        <a href="{{ route('user-management.create') }}" class="bg-[#06b6d4] hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-[0_4px_12px_rgba(6,182,212,0.3)] transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(6,182,212,0.4)] flex items-center gap-2">
            <i class="fas fa-plus"></i> Add User
        </a>
        @endcan
    </div>
</div>

<div class="card-3d overflow-visible relative">
    <!-- Header / Actions / Filters -->
    <form method="GET" action="{{ route('user-management.index') }}" class="p-4 border-b border-gray-100 flex justify-between items-center bg-white relative z-20 rounded-t-[20px]">
        <div class="relative w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau Role..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent transition-all">
            <button type="submit" class="hidden"></button>
        </div>

        <div class="flex items-center gap-3 relative z-30" x-data="{ openFilter: false }">
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('user-management.index') }}" class="text-xs text-red-500 hover:text-red-700 underline px-2">Reset</a>
            @endif

            <button type="button" @click="openFilter = !openFilter" class="px-4 py-2 text-sm font-medium flex items-center gap-2 relative bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] hover:border-gray-300">
                <i class="fas fa-filter text-gray-400"></i> Filter
                @if(request()->hasAny(['role', 'status']))
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                @endif
            </button>
            
            <!-- Filter Dropdown (Small) -->
            <div id="filterDropdown" 
                 x-show="openFilter" 
                 @click.outside="openFilter = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                 style="display: none;"
                 class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 p-4">
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Role</label>
                        <select name="role" onchange="this.form.submit()" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#06b6d4]">
                            <option value="">Semua</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="head" {{ request('role') == 'head' ? 'selected' : '' }}>Department Head</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status" onchange="this.form.submit()" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#06b6d4]">
                            <option value="">Semua</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-b-[20px]">
        <table class="w-full text-sm text-left">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Pengguna</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Username / NPK</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Role</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Login Terakhir</th>
                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $index => $user)
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative {{ $index % 2 == 1 ? 'bg-[#f0fdfa]/30' : 'bg-white' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#064e3b] text-white flex items-center justify-center font-bold shadow-sm shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">Akun {{ strtolower($user->role ?? 'karyawan') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <div class="font-semibold text-gray-900">{{ $user->npk ?? '-' }}</div>
                        <div class="text-xs">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if(strtolower($user->role) === 'admin' || strtolower($user->role) === 'head')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                {{ ucfirst($user->role) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ ucfirst($user->role ?? 'Karyawan') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->status === 'aktif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            @can('edit_user')
                            <a href="{{ route('user-management.edit', $user->id) }}" class="p-1.5 text-gray-400 hover:text-[#06b6d4] transition-colors rounded-lg hover:bg-cyan-50">
                                <i class="fas fa-pen"></i>
                            </a>
                            @endcan
                            @can('hapus_user')
                            <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-500 transition-colors rounded-lg hover:bg-rose-50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                            <p>Tidak ada pengguna ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white rounded-b-[20px]">
        <div class="text-sm text-gray-500">
            Showing <span class="font-medium text-gray-900">{{ $users->firstItem() }}</span> to <span class="font-medium text-gray-900">{{ $users->lastItem() }}</span> of <span class="font-medium text-gray-900">{{ $users->total() }}</span> entries
        </div>
        <div>
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
    @else
    <div class="px-6 py-4 border-t border-gray-100 flex items-center bg-white rounded-b-[20px]">
        <div class="text-sm text-gray-500">
            Showing {{ $users->count() }} entries
        </div>
    </div>
    @endif
</div>
@endsection

