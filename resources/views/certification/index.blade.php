@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Certification Data</h1>
        <p class="text-sm text-gray-500 mt-1">Select an employee to view certification details.</p>
    </div>
    <!-- Tombol Tambah dihilangkan karena konsep Read-Only -->
</div>

<div class="card-3d overflow-visible relative bg-white rounded-2xl shadow-sm border border-gray-100">
    <!-- Header / Actions / Filters -->
    <form method="GET" action="{{ route('certification.index') }}" class="p-4 border-b border-gray-100 flex justify-between items-center bg-white relative z-20 rounded-t-[20px]">
        <div class="relative w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Employee, NPK, Jabatan..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent transition-all">
            <button type="submit" class="hidden"></button>
        </div>
        
        <div class="flex items-center gap-3 relative z-30" x-data="{ openFilter: false }">
            @if(request()->hasAny(['search', 'department_id', 'jabatan']))
                <a href="{{ route('certification.index') }}" class="text-xs text-red-500 hover:text-red-700 underline px-2">Reset</a>
            @endif

            <button type="button" @click="openFilter = !openFilter" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 relative bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] hover:border-gray-300">
                <i class="fas fa-filter text-gray-400"></i> Filter
                @if(request()->hasAny(['department_id', 'jabatan']))
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
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Department</label>
                        <select name="department_id" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#06b6d4]">
                            <option value="">All</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Position</label>
                        <select name="jabatan" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#06b6d4]">
                            <option value="">All</option>
                            <option value="head" {{ request('jabatan') == 'head' ? 'selected' : '' }}>Department Head</option>
                            <option value="group head" {{ request('jabatan') == 'group head' ? 'selected' : '' }}>Group Head</option>
                            <option value="unit head" {{ request('jabatan') == 'unit head' ? 'selected' : '' }}>Unit Head</option>
                            <option value="staff" {{ request('jabatan') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#06b6d4] hover:bg-[#0891b2] text-white px-3 py-1.5 rounded-md text-xs font-medium transition-colors">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-b-[20px]">
        <table class="w-full text-left border-collapse">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th class="px-5 py-4 font-semibold tracking-wider w-12 text-center">No.</th>
                    <th class="px-5 py-4 font-semibold tracking-wider">Employee</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">NPK</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Position</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Department</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Total Sertifikat</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                
                @forelse($employees as $emp)
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
                    <td class="px-5 py-4 text-center text-gray-500 font-medium">
                        {{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($emp->foto)
                                <img src="{{ $emp->foto }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->nama) }}&background=random" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @endif
                            <div>
                                <div class="font-semibold text-gray-900 group-hover:text-[#06b6d4] transition-colors">{{ $emp->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $emp->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-gray-600 font-medium">{{ $emp->npk }}</td>
                    <td class="px-5 py-4 text-center text-gray-600">{{ $emp->position?->nama_jabatan }}</td>
                    <td class="px-5 py-4 text-center text-gray-600">{{ $emp->department ? $emp->department->nama_departemen : '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center justify-center min-w-[24px] px-2 py-1 rounded-md text-xs font-semibold bg-cyan-50 text-[#06b6d4] border border-cyan-200/50">
                            {{ $emp->certifications ? $emp->certifications->count() : 0 }}
                        </span>
                    </td>
                    
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Hanya tombol Lihat Detail Sertifikasi dengan tulisan See all -->
                            <a href="{{ route('certification.show', $emp->id) }}" class="text-[#06b6d4] hover:text-[#0891b2] font-semibold text-xs px-3 py-1.5 rounded-md hover:bg-cyan-50 transition-colors" title="Lihat Detail Sertifikasi">
                                See all
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Empty State -->
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">No employee data available</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($employees, 'links') && $employees->hasPages())
    <div class="p-4 border-t border-gray-100 bg-white rounded-b-[20px]">
        {{ $employees->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection

@push('modals')
<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
@endpush

