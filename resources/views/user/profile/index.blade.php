@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Profile and team member data from your department</p> 
    </div>
    <div class="flex gap-3">
        @can('tambah_karyawan')
        <button onclick="document.getElementById('importEmployeeModal').classList.remove('hidden')" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] hover:border-gray-300">
            <i class="fas fa-file-import text-gray-400"></i> Import
        </button>
        <a href="{{ route('employee.create') }}" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-4 py-2 rounded-lg text-sm font-medium shadow-[0_4px_12px_rgba(14,165,233,0.3)] transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(14,165,233,0.4)] flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Employee
        </a>
        @endcan
    </div>
</div>

@if($myProfile)
<!-- Head Profile Card -->
@php
    $colors = [
        ['bg' => 'fef2f2', 'border' => 'fecaca', 'text' => '991b1b'], // red
        ['bg' => 'fff7ed', 'border' => 'fed7aa', 'text' => '9a3412'], // orange
        ['bg' => 'fefce8', 'border' => 'fef08a', 'text' => '854d0e'], // yellow
        ['bg' => 'f0fdf4', 'border' => 'bbf7d0', 'text' => '166534'], // green
        ['bg' => 'ecfdf5', 'border' => 'a7f3d0', 'text' => '065f46'], // emerald
        ['bg' => 'f0fdfa', 'border' => '99f6e4', 'text' => '115e59'], // teal
        ['bg' => 'ecfeff', 'border' => 'a5f3fc', 'text' => '155e75'], // cyan
        ['bg' => 'f0f9ff', 'border' => 'bae6fd', 'text' => '075985'], // sky
        ['bg' => 'eff6ff', 'border' => 'bfdbfe', 'text' => '1e40af'], // blue
        ['bg' => 'eef2ff', 'border' => 'c7d2fe', 'text' => '3730a3'], // indigo
        ['bg' => 'f5f3ff', 'border' => 'ddd6fe', 'text' => '5b21b6'], // violet
        ['bg' => 'faf5ff', 'border' => 'e9d5ff', 'text' => '6b21a8'], // purple
        ['bg' => 'fdf4ff', 'border' => 'f5d0fe', 'text' => '86198f'], // fuchsia
        ['bg' => 'fdf2f8', 'border' => 'fbcfe8', 'text' => '9d174d'], // pink
        ['bg' => 'fff1f2', 'border' => 'fecdd3', 'text' => '9f1239'], // rose
    ];
    $deptId = $myProfile->department_id ?? 0;
    $colorIndex = $deptId % count($colors);
    $theme = $colors[$colorIndex];
@endphp
<div class="card-3d rounded-xl border p-5 flex items-center justify-between mb-6 shadow-sm" style="background-color: #{{ $theme['bg'] }}; border-color: #{{ $theme['border'] }};">
    <div class="flex items-center gap-4">
        @if($myProfile->foto)
            <img src="{{ $myProfile->foto }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover shadow-sm">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($myProfile->nama) }}&background={{ $theme['text'] }}&color=ffffff" alt="Avatar" class="w-16 h-16 rounded-full object-cover shadow-sm border border-gray-100">
        @endif
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-gray-900">{{ $myProfile->nama }}</h2>
            </div>
            <p class="text-[11px] text-gray-500 mt-0.5">NPK: {{ $myProfile->npk }} <span class="mx-2 text-gray-300">|</span> Departemen: {{ $myProfile->department ? $myProfile->department->nama_departemen : '-' }}</p>
            <p class="text-xs font-medium text-gray-700 mt-1">{{ ucwords($myProfile->position?->nama_jabatan) }}</p>
        </div>
    </div>
    
    <div class="flex gap-8 text-right pr-4">
        <div>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Masa Kerja</p>
            <p class="text-base font-bold text-[#065f46]">
                @php
                    $masuk = \Carbon\Carbon::parse($myProfile->masuk_kerja);
                    $diff = $masuk->diff(\Carbon\Carbon::now());
                    echo $diff->y > 0 ? $diff->y . ' Thn' : $diff->m . ' Bln';
                @endphp
            </p>
        </div>
    </div>
</div>
@else
<div class="card-3d bg-red-50 rounded-xl border border-red-200 p-5 flex items-center justify-between mb-6 shadow-sm">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-red-500 text-2xl">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-red-800">Profil Karyawan Tidak Ditemukan</h2>
            <p class="text-xs text-red-600 mt-1">Sistem tidak dapat menemukan data Karyawan yang cocok dengan email akun Anda (<strong>{{ auth()->user()->email }}</strong>).</p>
        </div>
    </div>
</div>
@endif

<div class="card-3d overflow-visible relative">
    <!-- Header / Actions / Filters -->
    <form method="GET" action="{{ route('profile.index') }}" class="p-4 border-b border-gray-100 flex justify-between items-center bg-white relative z-20 rounded-t-[20px]">
        <div class="relative w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Employee, Department, Readiness..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] focus:border-transparent transition-all">
            <button type="submit" class="hidden"></button>
        </div>
        
        <div class="flex items-center gap-3 relative z-30" x-data="{ openFilter: false }">
            @if(request()->hasAny(['search', 'department_id', 'position_id']))
                <a href="{{ route('profile.index') }}" class="text-xs text-red-500 hover:text-red-700 underline px-2">Reset</a>
            @endif

            <button type="button" @click="openFilter = !openFilter" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 relative bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] hover:border-gray-300">
                <i class="fas fa-filter text-gray-400"></i> Filter
                @if(request()->hasAny(['department_id', 'position_id']))
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
                        <select name="department_id" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#0ea5e9]">
                            <option value="">All</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Position</label>
                        <select name="position_id" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#0ea5e9]">
                            <option value="">All</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ request('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->nama_jabatan }}</option>
                            @endforeach
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
    <div class="w-full overflow-x-auto rounded-b-[20px]">
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
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(37,99,235,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
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
                    <td class="px-5 py-4 text-center text-gray-600">{{ $emp->department?->nama_departemen }}</td>
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
                            <div class="relative group/tooltip">
                                <a href="{{ route('profile.show', $emp->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 hover:bg-blue-100 hover:text-blue-600 rounded-lg transition-all">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 shadow-md z-50">
                                    Lihat Detail Profil
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 w-2 h-2 bg-gray-800 rotate-45"></div>
                                </div>
                            </div>

                            @can('edit_karyawan')
                            <div class="relative group/tooltip">
                                <a href="{{ route('employee.edit', $emp->id) }}" class="w-8 h-8 flex items-center justify-center bg-yellow-50 text-yellow-500 hover:bg-yellow-100 hover:text-yellow-600 rounded-lg transition-all">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 shadow-md z-50">
                                    Edit Data
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 w-2 h-2 bg-gray-800 rotate-45"></div>
                                </div>
                            </div>
                            @endcan

                            @can('hapus_karyawan')
                            <div class="relative group/tooltip">
                                <form action="{{ route('employee.destroy', $emp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 rounded-lg transition-all">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 shadow-md z-50">
                                    Hapus Data
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-1 w-2 h-2 bg-gray-800 rotate-45"></div>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Empty State -->
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">No team member data available</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-5 border-t border-gray-100 bg-white flex justify-between items-center rounded-b-[20px]">
        @if(method_exists($employees, 'links'))
            <div class="w-full">
                {{ $employees->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

@push('modals')
<!-- Modal Import Karyawan -->
<div id="importEmployeeModal" class="hidden fixed inset-0 z-[100] overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-4 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="document.getElementById('importEmployeeModal').classList.add('hidden')"></div>
        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-gray-100 my-8">
            <div class="bg-white px-6 pt-5 pb-3 sm:px-6 sm:pt-5 sm:pb-3 border-b border-gray-100 flex justify-between items-start">
                <div class="flex gap-3 items-start">
                    <div class="bg-green-50 p-2.5 rounded-xl flex-shrink-0">
                        <i class="fas fa-file-import text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">Impor Data Karyawan</h3>
                        <p class="text-xs text-gray-500 mt-1">Unggah file spreadsheet untuk menambahkan data massal.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('importEmployeeModal').classList.add('hidden')" class="bg-gray-50 p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition-all">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>
            <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data" hx-encoding="multipart/form-data">
                @csrf
                <div class="bg-white px-6 py-4 sm:px-6 sm:py-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <!-- Format Kolom Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 flex flex-col md:flex-row md:items-center justify-between mb-4 gap-3 md:gap-0">
                        <div class="flex items-center gap-2">
                            <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm flex-shrink-0">
                                <i class="fas fa-table text-green-600 text-base"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Format Kolom</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5">NPK, Nama, Departemen, Tgl Lahir, dll.</p>
                            </div>
                        </div>
                        <a href="{{ route('employee.template') }}" data-hx-boost="false" download class="inline-flex justify-center items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm whitespace-nowrap">
                            <i class="fas fa-download"></i> Unduh Template
                        </a>
                    </div>
                    <div class="border-2 border-dashed border-green-300 bg-green-50/30 rounded-xl p-6 text-center hover:bg-green-50/50 transition-colors cursor-pointer relative mb-4">
                        <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" required onchange="
                            if(this.files.length > 0) {
                                document.getElementById('file-upload-state').classList.remove('hidden');
                                document.getElementById('file-name').innerText = this.files[0].name;
                                document.getElementById('file-size').innerText = (this.files[0].size / (1024*1024)).toFixed(2) + ' MB';
                            } else {
                                document.getElementById('file-upload-state').classList.add('hidden');
                            }
                        ">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600 mb-2 shadow-sm">
                            <i class="fas fa-cloud-upload-alt text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-700 font-medium mb-1">Tarik file ke sini, atau <span class="text-green-600 hover:text-green-700 underline font-semibold">klik</span></p>
                        <p class="text-[10px] text-gray-500">.XLSX, .XLS, .CSV (Maksimal 15 MB)</p>
                    </div>
                    <!-- File selected state -->
                    <div id="file-upload-state" class="hidden border border-gray-200 rounded-xl p-3 mb-4 relative overflow-hidden bg-white shadow-sm">
                        <div class="flex items-start justify-between mb-2 relative z-10">
                            <div class="flex items-center gap-2">
                                <div class="bg-green-50 border border-green-100 text-green-700 font-bold text-[10px] p-2 rounded-lg flex-shrink-0">XLS</div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-semibold text-gray-900" id="file-name">template.xlsx</h4>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1 h-1 rounded-full bg-green-500"></span> Siap
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-0.5" id="file-size">0.0 MB</p>
                                </div>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-red-500 transition-colors p-1" onclick="
                                document.querySelector('input[name=file]').value = '';
                                document.getElementById('file-upload-state').classList.add('hidden');
                            ">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                        <div class="absolute bottom-0 left-0 h-1 bg-green-500 w-full rounded-b-xl"></div>
                    </div>
                    <!-- Checkboxes -->
                    <div class="space-y-3">
                        <label class="flex items-start gap-2.5 cursor-pointer group">
                            <div class="flex items-center h-4 mt-0.5">
                                <input type="checkbox" name="overwrite" class="w-3.5 h-3.5 text-green-600 border-gray-300 rounded focus:ring-green-600" checked>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-900 group-hover:text-green-700 transition-colors">Timpa data jika NPK sudah ada</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                    <button type="button" onclick="document.getElementById('importEmployeeModal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-all sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#0ea5e9] text-xs font-medium text-white hover:bg-[#0284c7] focus:outline-none transition-all sm:w-auto flex items-center gap-1.5">
                        <i class="fas fa-cloud-upload-alt"></i> Impor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@endsection
