@extends('layouts.app')
@section('title', 'Employee Mentoring Details')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('user-mentoring.index') }}" class="hover:text-[#06b6d4] transition-colors">Mentoring Data</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-900 font-medium">Employee Mentoring</span>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('user-mentoring.index') }}" class="bg-white shadow-[0_2px_4px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_6px_rgba(0,0,0,0.05),0_2px_4px_rgba(0,0,0,0.1)] border border-gray-100 rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-gray-700">
            <i class="fas fa-arrow-left text-gray-400"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm animate-fade-in">
    <div class="flex items-center gap-2.5 text-sm font-medium">
        <i class="fas fa-check-circle text-emerald-500 text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(isset($errors) && $errors->any())
<div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm animate-fade-in">
    <div class="flex items-center gap-2.5 text-sm font-bold mb-1">
        <i class="fas fa-exclamation-triangle text-rose-500 text-base"></i>
        <span>Terdapat kesalahan saat menyimpan data:</span>
    </div>
    <ul class="list-disc pl-8 text-xs space-y-0.5 text-rose-700">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Employee Header Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center gap-6">
        @if($employee->foto)
            <img src="{{ $employee->foto }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover shadow-sm">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->nama) }}&background=0284c7&color=fff&size=100" alt="Avatar" class="w-20 h-20 rounded-full object-cover shadow-sm">
        @endif
        
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $employee->nama }}</h1>
            <p class="text-gray-500 text-sm mb-3">{{ $employee->position?->nama_jabatan }} {{ $employee->department ? '- ' . $employee->department->nama_departemen : '' }}</p>
            
            <div class="flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                    <span class="text-indigo-900">{{ $mentorings->count() }}</span> Mentoring Plans
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                    <span class="text-emerald-900">{{ $mentorings->where('status_mentoring', 'completed')->count() }}</span> Completed
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                    <span class="text-amber-900">{{ $mentorings->where('status_mentoring', 'in_progress')->count() }}</span> In Progress
                </span>
            </div>
        </div>
    </div>
</div>

<!-- MAIN TIMELINE CONTAINER -->
<div class="mt-8">
    <!-- Controls (Search & Add) -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <!-- Search -->
        <div class="relative w-full md:w-80">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#06b6d4] focus:border-[#06b6d4] sm:text-sm transition-shadow text-slate-800" placeholder="Search Mentoring by year, mentor, project...">
        </div>
        <!-- Add Button -->
        @can('tambah_mentoring')
        <a href="{{ route('mentoring.create', ['employee_id' => $employee->id, 'redirect_to' => url()->current()]) }}" class="w-full md:w-auto bg-[#06b6d4] hover:bg-[#0891b2] text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Mentoring
        </a>
        @endcan
    </div>

    <!-- TIMELINE SECTION -->
    <div class="timeline-main-wrapper" id="idpTimelineContainer">
        <!-- The Vertical Continuous Line (Hidden on small screens) -->
        <div class="timeline-line-track"></div>

        @forelse($mentorings as $mentoring)
        <!-- ================= ITEM ================= -->
        <div class="idp-timeline-item relative mb-8 group">
            
            <!-- Date & Dot Column -->
            <div class="timeline-meta-col">
                <div class="w-full">
                    <h3 class="idp-tahun text-3xl font-bold text-slate-900 tracking-tight">{{ $mentoring->tahun ?? '-' }}</h3>
                    <p class="idp-periode text-sm text-slate-500 mt-0.5 font-medium">
                        @if($mentoring->periode)
                            {{ str_starts_with(strtolower($mentoring->periode), 'periode') ? $mentoring->periode : (is_numeric($mentoring->periode) ? 'Periode ' . $mentoring->periode : $mentoring->periode) }}
                        @else
                            Tahunan
                        @endif
                    </p>
                </div>
                <!-- Timeline Dot with ring -->
                @if($mentoring->status_mentoring === 'completed')
                    <div class="timeline-status-dot bg-emerald-400"></div>
                @elseif($mentoring->status_mentoring === 'in_progress')
                    <div class="timeline-status-dot bg-orange-400"></div>
                @else
                    <div class="timeline-status-dot bg-slate-400"></div>
                @endif
            </div>

            <!-- Content Card -->
            <div class="timeline-card bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 flex flex-col overflow-hidden">
                
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <!-- Mentor Info -->
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                            <i class="fas fa-user-tie text-sm"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block leading-tight">Mentor</span>
                            <span class="idp-mentor font-semibold text-slate-800 text-sm leading-snug">{{ $mentoring->mentor ?? '-' }}</span>
                        </div>
                    </div>
                    <!-- Status Badge -->
                    @if($mentoring->status_mentoring === 'completed')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200/60 rounded-full text-xs font-semibold">
                            Completed
                        </span>
                    @elseif($mentoring->status_mentoring === 'in_progress')
                        <span class="px-3 py-1 bg-orange-50 text-orange-600 border border-orange-200/60 rounded-full text-xs font-semibold">
                            In Progress
                        </span>
                    @else
                        <span class="px-3 py-1 bg-slate-50 text-slate-600 border border-slate-200/60 rounded-full text-xs font-semibold">
                            {{ ucfirst($mentoring->status_mentoring ?? 'Pending') }}
                        </span>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="px-6 py-5 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <!-- Detail Text -->
                    <div class="flex-1 md:pr-10">
                        @if(!empty($mentoring->project) && trim($mentoring->project) !== '-')
                            <h4 class="text-sm font-semibold text-slate-800 mb-1 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">Project</span>
                                <span class="idp-project font-medium text-slate-700">{{ $mentoring->project }}</span>
                            </h4>
                        @endif

                        <h4 class="text-sm font-semibold text-slate-800 mb-1 mt-2">Area Pengembangan</h4>
                        @if(!empty($mentoring->development) && trim($mentoring->development) !== '-')
                            <p class="idp-development text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $mentoring->development }}</p>
                        @else
                            <p class="idp-development text-slate-400 text-sm italic">Belum diisi</p>
                        @endif

                        @if($mentoring->target || $mentoring->monitoring || $mentoring->final)
                            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 mt-3 pt-3 border-t border-slate-100">
                                @if($mentoring->target)
                                    <span><i class="far fa-calendar-alt text-slate-400 mr-1"></i> Target: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($mentoring->target)->format('d M Y') }}</strong></span>
                                @endif
                                @if($mentoring->monitoring)
                                    <span><i class="far fa-calendar-check text-slate-400 mr-1"></i> Monitoring: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($mentoring->monitoring)->format('d M Y') }}</strong></span>
                                @endif
                                @if($mentoring->final)
                                    <span><i class="far fa-flag text-slate-400 mr-1"></i> Final: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($mentoring->final)->format('d M Y') }}</strong></span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Actions Box -->
                    <div class="flex items-center gap-2 shrink-0 w-full md:w-auto justify-end border-t border-slate-100 md:border-none pt-4 md:pt-0">
                        <a href="{{ route('user-mentoring.show', $mentoring->id) }}" class="flex items-center gap-1.5 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 hover:shadow hover:-translate-y-0.5 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm" title="Lihat & Kelola Mentoring">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Detail Mentoring
                        </a>
                        @can('edit_mentoring')
                        <a href="{{ route('mentoring.edit', [$mentoring->id, 'employee_id' => $employee->id, 'redirect_to' => url()->current()]) }}" class="p-2 text-slate-400 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200 shadow-sm inline-flex items-center justify-center" title="Edit Info Mentoring">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                        </a>
                        @endcan
                        @can('hapus_mentoring')
                        <form action="{{ route('mentoring.destroy', $mentoring->id) }}" method="POST" hx-boost="false" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Mentoring tahun ini?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                            <button type="submit" class="p-2 text-slate-400 bg-white border border-slate-200 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-200 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200 shadow-sm inline-flex items-center justify-center" title="Hapus Mentoring">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center text-slate-500">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-clipboard-list text-4xl text-slate-300 mb-3"></i>
                <p class="font-medium text-slate-900">Belum ada Mentoring Plan</p>
                <p class="text-xs text-slate-500 mt-1">Tambahkan data mentoring baru menggunakan tombol di atas.</p>
            </div>
        </div>
        @endforelse

        <!-- Empty search result message -->
        <div id="noSearchResult" class="hidden bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center text-slate-500">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-search text-3xl text-slate-300 mb-2"></i>
                <p class="font-medium text-slate-900">Tidak ada Mentoring yang cocok</p>
                <p class="text-xs text-slate-400 mt-0.5">Coba cari dengan kata kunci tahun, periode, mentor, project, atau area pengembangan lain.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')

<style>
/* Custom Timeline Styling */
.timeline-main-wrapper {
    position: relative;
    padding-left: 160px;
}
.timeline-line-track {
    position: absolute;
    left: 120px;
    top: 1.5rem;
    bottom: 2rem;
    width: 2px;
    background-color: #e2e8f0;
}
.timeline-meta-col {
    position: absolute;
    left: -160px;
    top: 1rem;
    width: 140px;
    text-align: right;
    padding-right: 2.5rem;
    z-index: 10;
}
.timeline-status-dot {
    position: absolute;
    right: 13px;
    top: 0.5rem;
    width: 14px;
    height: 14px;
    border-radius: 9999px;
    box-shadow: 0 0 0 6px #ffffff, 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    z-index: 20;
}
.timeline-card {
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.timeline-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08), 0 8px 16px -6px rgba(0, 0, 0, 0.04);
}

@media (max-width: 768px) {
    .timeline-main-wrapper {
        padding-left: 0 !important;
    }
    .timeline-line-track, .timeline-status-dot {
        display: none !important;
    }
    .timeline-meta-col {
        position: relative !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        text-align: left !important;
        padding-right: 0 !important;
        margin-bottom: 0.75rem !important;
    }
}

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

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase().trim();
        let items = document.querySelectorAll('.idp-timeline-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            let tahunEl = item.querySelector('.idp-tahun');
            let mentorEl = item.querySelector('.idp-mentor');
            let periodeEl = item.querySelector('.idp-periode');
            let devEl = item.querySelector('.idp-development');
            let projEl = item.querySelector('.idp-project');
            
            let tahun = tahunEl ? tahunEl.textContent.toLowerCase() : '';
            let mentor = mentorEl ? mentorEl.textContent.toLowerCase() : '';
            let periode = periodeEl ? periodeEl.textContent.toLowerCase() : '';
            let dev = devEl ? devEl.textContent.toLowerCase() : '';
            let proj = projEl ? projEl.textContent.toLowerCase() : '';
            
            if (tahun.includes(filter) || mentor.includes(filter) || periode.includes(filter) || dev.includes(filter) || proj.includes(filter)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        let noResultEl = document.getElementById('noSearchResult');
        if (noResultEl) {
            if (visibleCount === 0 && items.length > 0) {
                noResultEl.classList.remove('hidden');
            } else {
                noResultEl.classList.add('hidden');
            }
        }
    });
</script>
@endpush
