@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-1">Welcome back, {{ $karyawan->nama ?? auth()->user()->name }}!</h1>
    <p class="text-sm text-gray-500">{{ $karyawan->position?->nama_jabatan ?? '-' }} • {{ $karyawan->department?->nama_departemen ?? '-' }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Status Kaderisasi Anda -->
    <div class="card-3d bg-blue-50 rounded-2xl border border-blue-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-semibold text-gray-500">Status Kaderisasi Anda</h3>
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="far fa-file-alt text-sm"></i>
            </div>
        </div>
        <h2 class="text-2xl font-black text-gray-900">{{ strtoupper($karyawan->readiness ?? 'N/A') }}</h2>
    </div>

    <!-- Anggota -->
    <div class="card-3d bg-purple-50 rounded-2xl border border-purple-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-semibold text-gray-500">Anggota</h3>
            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                <i class="fas fa-users text-sm"></i>
            </div>
        </div>
        <h2 class="text-2xl font-black text-gray-900">{{ $totalAnggota }}</h2>
    </div>

    <!-- Sertifikasi -->
    <div class="card-3d bg-orange-50 rounded-2xl border border-orange-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-semibold text-gray-500">Sertifikasi</h3>
            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                <i class="far fa-check-circle text-sm"></i>
            </div>
        </div>
        <h2 class="text-2xl font-black text-gray-900">{{ $totalSertifikasi }} <span class="text-sm font-medium text-gray-700">Active</span></h2>
    </div>

    <!-- Kader Siap -->
    <div class="card-3d bg-emerald-50 rounded-2xl border border-emerald-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xs font-semibold text-gray-500">Kader Siap (R3)</h3>
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                <i class="far fa-check-circle text-sm"></i>
            </div>
        </div>
        <h2 class="text-2xl font-black text-gray-900">{{ $kaderSiap }}</h2>
    </div>
</div>

<div class="card-3d bg-white rounded-2xl border border-gray-100 p-6 overflow-hidden">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">Tim {{ $karyawan->department?->nama_departemen ?? 'Departemen' }}</h2>
        <p class="text-xs text-gray-500 mt-1">Ringkasan anggota & kepala tim — buka Employee Journey untuk lihat laporan kinerja & roadmap</p>
    </div>
    
        <table class="w-full text-left border-collapse">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th class="px-5 py-4 font-semibold tracking-wider w-12 text-center">No.</th>
                    <th class="px-5 py-4 font-semibold tracking-wider">Employee</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Position</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Department</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Readiness</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @forelse($timDepartment as $emp)
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
                    <td class="px-5 py-4 text-center text-gray-500 font-medium">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($emp->foto)
                                <img src="{{ asset('storage/' . $emp->foto) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->nama) }}&background=random" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @endif
                            <div>
                                <div class="font-semibold text-gray-900 group-hover:text-[#0ea5e9] transition-colors">{{ $emp->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $emp->email ?? strtolower(str_replace(' ', '.', $emp->nama)) . '@sdi-company.com' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-gray-600">{{ $emp->position?->nama_jabatan ?? '-' }}</td>
                    <td class="px-5 py-4 text-center text-gray-600">{{ $emp->department ? $emp->department->nama_departemen : '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        @php
                            $readinessStyles = [
                                'R1 ready' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200/50', 'dot' => 'bg-green-500'],
                                'R2 ready' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200/50', 'dot' => 'bg-blue-500'],
                                'R3 ready' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200/50', 'dot' => 'bg-indigo-500'],
                                'R4 ready' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200/50', 'dot' => 'bg-purple-500'],
                                'R5 ready' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200/50', 'dot' => 'bg-yellow-500'],
                                'not_ready' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200/50', 'dot' => 'bg-red-500'],
                            ];
                            $style = $readinessStyles[$emp->readiness ?? ''] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200/50', 'dot' => 'bg-gray-500'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium {{ $style['bg'] }} {{ $style['text'] }} border {{ $style['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span> {{ ($emp->readiness ?? '') === 'not_ready' ? 'Not Ready' : ucwords($emp->readiness ?? '-') }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <div class="relative group/tooltip">
                                <a href="{{ route('profile.show', $emp->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 hover:bg-blue-100 hover:text-blue-600 rounded-lg transition-all">
                                    <i class="fas fa-id-card text-sm"></i>
                                </a>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 shadow-md z-50">
                                    Lihat Employee Journey
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 w-2 h-2 bg-gray-800 rotate-45"></div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Empty State -->
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">No team member data available</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
