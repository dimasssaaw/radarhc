@extends('layouts.app')
@section('title', 'Assessment Report')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Assessment Report</h1>
        <p class="text-sm text-gray-500 mt-1">View and manage all assessment reports in one place</p>
    </div>
</div>

<div class="card-3d overflow-hidden">
    <!-- Header / Actions / Filters -->
    <form method="GET" action="{{ route('user-assessment-report.index') }}" class="p-4 border-b border-gray-100 flex justify-between items-center bg-white relative">
        <div class="relative w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Employee, Departemen, Kaderisasi..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] focus:border-transparent transition-all">
            <button type="submit" class="hidden"></button>
        </div>
        
        <div class="flex items-center gap-3 relative">
            @if(request()->hasAny(['search', 'department_id', 'jabatan']))
                <a href="{{ route('user-assessment-report.index') }}" class="text-xs text-red-500 hover:text-red-700 underline px-2">Reset</a>
            @endif

            <button type="button" onclick="document.getElementById('filterDropdown').classList.toggle('hidden')" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 relative bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] hover:border-gray-300">
                <i class="fas fa-filter text-gray-400"></i> Filter
                @if(request()->hasAny(['department_id', 'jabatan']))
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                @endif
            </button>
            
            <!-- Filter Dropdown (Small) -->
            <div id="filterDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50 p-3">
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Department</label>
                        <select name="department_id" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#0ea5e9]">
                            <option value="">All</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Position</label>
                        <select name="jabatan" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#0ea5e9]">
                            <option value="">All</option>
                            <option value="head" {{ request('jabatan') == 'head' ? 'selected' : '' }}>Department Head</option>
                            <option value="group head" {{ request('jabatan') == 'group head' ? 'selected' : '' }}>Group Head</option>
                            <option value="unit head" {{ request('jabatan') == 'unit head' ? 'selected' : '' }}>Unit Head</option>
                            <option value="staff" {{ request('jabatan') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded-md text-xs font-medium transition-colors">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th class="px-5 py-4 font-semibold tracking-wider w-12 text-center">No.</th>
                    <th class="px-5 py-4 font-semibold tracking-wider">Employee</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">NPK</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Position</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Department</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Readiness</th>
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
                                <div class="font-semibold text-gray-900 group-hover:text-[#0ea5e9] transition-colors">{{ $emp->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $emp->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-gray-600 font-medium">{{ $emp->npk }}</td>
                    <td class="px-5 py-4 text-center text-gray-600">{{ $emp->position?->nama_jabatan }}</td>
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
                            $style = $readinessStyles[$emp->readiness] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200/50', 'dot' => 'bg-gray-500'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium {{ $style['bg'] }} {{ $style['text'] }} border {{ $style['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span> {{ $emp->readiness === 'not_ready' ? 'Not Ready' : ucwords($emp->readiness) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('user-assessment-report.show', $emp->id) }}" class="text-gray-400 hover:text-blue-600 p-1.5 rounded-md hover:bg-blue-50 transition-colors" title="Lihat Report">
                                <i class="fas fa-eye w-4"></i>
                            </a>
                            @if($emp->assessmentReports->count() > 0)
                                @can('edit_assessment')
                                <a href="{{ route('assessment-report.edit', $emp->assessmentReports->last()->id) }}" class="text-gray-400 hover:text-amber-600 p-1.5 rounded-md hover:bg-amber-50 transition-colors" title="Edit Assessment">
                                    <i class="fas fa-edit w-4"></i>
                                </a>
                                @endcan
                            @else
                                @can('tambah_assessment')
                                <a href="{{ route('assessment-report.create', ['employee_id' => $emp->id]) }}" class="text-gray-400 hover:text-emerald-600 p-1.5 rounded-md hover:bg-emerald-50 transition-colors" title="Tambah Assessment Baru">
                                    <i class="fas fa-file-medical w-4"></i>
                                </a>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Empty State -->
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
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
    <div class="p-5 border-t border-gray-100 bg-white flex justify-between items-center">
        @if(method_exists($employees, 'links'))
            <div class="w-full">
                {{ $employees->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

@endsection

