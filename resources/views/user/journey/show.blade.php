@extends('layouts.app')
@section('title', 'Employee Journey - ' . $karyawan->name)

@section('content')
<!-- Header & Back Button -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="{{ route('user-journey.index') }}" class="hover:text-gray-700 transition-colors">Employee Journey</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <span class="text-gray-900 font-medium">Detail Employee Journey</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('user-journey.pdf', $karyawan->id) }}" onclick="exportPdfWithCurrentFilters(event, '{{ route('user-journey.pdf', $karyawan->id) }}')" target="_blank" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 bg-[#0ea5e9] text-white rounded-lg shadow-sm hover:-translate-y-0.5 hover:bg-[#0284c7] hover:shadow-[0_4px_10px_rgb(14,165,233,0.3)] transition-all duration-300">
            <i class="fas fa-print"></i> Print to PDF
        </a>
        <a href="{{ route('user-journey.index') }}" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] transition-all duration-300">
            <i class="fas fa-arrow-left text-gray-400"></i> Back
        </a>
    </div>
</div>

<!-- 1. Employee Profile Header -->
@include('components.employee-profile-header', ['employee' => $karyawan])

<!-- 2. Perjalanan Karier (Timeline) -->
<div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 mb-6 relative overflow-hidden transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-0.5">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Career Journey</h2>
            <p class="text-sm text-gray-500">Employee career track record and achievements</p>
        </div>
        @can('tambah_journey')
        <button type="button" onclick="document.getElementById('modalTambahKarier').classList.remove('hidden')" class="bg-[#0ea5e9] text-white px-4 py-2.5 rounded-lg text-sm font-medium shadow-sm hover:bg-[#0284c7] transition-all flex items-center gap-2 hover:shadow-md hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Add
        </button>
        @endcan
    </div>

    <!-- Timeline UI -->
    <div class="relative mt-2 mb-0 pt-4 pb-0" x-data="{ 
            activeTimeline: {{ $perjalananKarirs->isNotEmpty() ? $perjalananKarirs->last()->id : 'null' }},
            scrollLeft() { $refs.timelineContainer.scrollBy({ left: -300, behavior: 'smooth' }) },
            scrollRight() { $refs.timelineContainer.scrollBy({ left: 300, behavior: 'smooth' }) }
        }">
        
        <!-- Scroll Buttons -->
        @if($perjalananKarirs->count() > 3)
        <button @click="scrollLeft()" class="absolute left-0 top-[75px] z-20 w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-100 transition-colors shadow-sm -ml-4">
            <i class="fas fa-chevron-left text-xs"></i>
        </button>
        <button @click="scrollRight()" class="absolute right-0 top-[75px] z-20 w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-100 transition-colors shadow-sm -mr-4">
            <i class="fas fa-chevron-right text-xs"></i>
        </button>
        @endif
        
        <!-- Scrollable Wrapper: Ditambahkan pt-6 agar tahun yang bergerak ke atas tidak terpotong -->
        <div x-ref="timelineContainer" class="overflow-x-auto no-scrollbar pb-4 px-4 md:px-8 relative scroll-smooth pt-6">
            
            <div class="relative min-w-full w-max px-4">
                
                <!-- Connecting Line Base (Tepat di tengah icon) -->
                <div class="absolute top-[72px] left-[96px] right-[96px] border-t-2 border-dashed border-sky-100 z-0 pointer-events-none"></div>
                <div class="absolute top-[71px] left-[96px] right-[96px] h-[4px] bg-gradient-to-r from-transparent via-sky-300 to-transparent opacity-30 z-0 rounded-full blur-[2px] pointer-events-none"></div>
                
                <div class="flex gap-12 relative z-10 justify-between">
                    @forelse($perjalananKarirs as $pk)
                    <div class="flex flex-col items-center group cursor-pointer w-40 shrink-0 relative transition-all duration-300 ease-out" 
                         @click="activeTimeline = {{ $pk->id }}"
                         :class="activeTimeline == {{ $pk->id }} ? 'transform -translate-y-2' : 'hover:-translate-y-1'">
                        
                        <!-- Active Highlight Blob -->
                        <div x-show="activeTimeline == {{ $pk->id }}" 
                             x-transition.opacity.duration.500ms
                             class="absolute -inset-x-4 -inset-y-4 bg-amber-50/40 rounded-2xl blur-md -z-10"></div>

                        <!-- Year Text -->
                        <span class="text-xs font-bold mb-8 transition-all duration-300 h-4 flex items-center"
                              :class="activeTimeline == {{ $pk->id }} ? 'text-amber-500 scale-110 drop-shadow-sm' : 'text-gray-400 group-hover:text-gray-600'">
                            {{ $pk->tahun_karir }}
                        </span>
                        
                        <!-- Dot / Icon Box (Tinggi dipatok h-12 agar garis selalu pas di tengah) -->
                        <div class="relative mb-5 h-12 flex items-center justify-center">

                            @if($pk->status_pencapaian == 'aktif/tercapai')
                                <!-- Desain Posisi Saat Ini: Persis seperti Screenshot referensi -->
                                <div class="w-12 h-12 rounded-full flex items-center justify-center relative z-10 bg-white border border-white shadow-[0_0_15px_rgba(253,230,138,0.7)] ring-[5px] ring-amber-50 transition-all duration-300"
                                     :class="activeTimeline == {{ $pk->id }} ? 'scale-110' : 'group-hover:scale-105'">
                                    
                                    <!-- Glow Ring Kuning Transparan -->
                                    <div class="absolute inset-0 rounded-full border-2 border-amber-200/60 scale-125"></div>
                                    
                                    <!-- Marker Putih / Abu-abu Terang -->
                                    <i class="fas fa-map-marker-alt text-[20px] text-gray-200 drop-shadow-sm animate-bounce" style="animation-duration: 2s;"></i>
                                </div>
                            @elseif($pk->status_pencapaian == 'proyeksi/target')
                                <!-- Projection -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10 bg-white border-2 border-gray-200 text-gray-300 transition-all duration-300 shadow-sm"
                                     :class="activeTimeline == {{ $pk->id }} ? 'border-sky-300 text-sky-400 scale-110 shadow-md ring-4 ring-sky-50 ring-offset-2' : 'group-hover:border-sky-200 group-hover:text-sky-300'">
                                    <div class="w-2 h-2 rounded-full border-2 border-current transition-colors duration-300"></div>
                                </div>
                            @else
                                <!-- History -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center relative z-10 bg-white border-2 border-emerald-500 text-emerald-500 transition-all duration-300 shadow-sm"
                                     :class="activeTimeline == {{ $pk->id }} ? 'bg-emerald-50 scale-110 shadow-md ring-4 ring-emerald-50 ring-offset-2' : 'group-hover:bg-emerald-50'">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Position Title & Badges -->
                        <div class="text-center transition-all duration-500 ease-out flex flex-col items-center" 
                             :class="activeTimeline == {{ $pk->id }} ? 'transform translate-y-1' : ''">
                             
                            <!-- Badge Tipe Milestone -->
                            <span class="inline-block px-3 py-1 mb-2 text-[9px] font-bold uppercase rounded-full tracking-wider border
                                @if($pk->status_pencapaian == 'aktif/tercapai') bg-white text-amber-500 border-amber-300 shadow-sm
                                @elseif($pk->status_pencapaian == 'proyeksi/target') bg-sky-50 text-sky-600 border-sky-200
                                @else bg-emerald-50 text-emerald-600 border-emerald-200
                                @endif">
                                @php
                                    $milestoneLabels = [
                                        'aktif' => 'Initial Join',
                                        'tetap' => 'Current Role',
                                        'promosi' => 'Promotion',
                                        'rotasi' => 'Rotation',
                                        'demosi' => 'Demotion',
                                    ];
                                @endphp
                                {{ $milestoneLabels[$pk->tipe_milestone] ?? ucwords($pk->tipe_milestone) }}
                            </span>

                            <p class="font-bold text-[15px] px-2 transition-colors duration-300 flex items-center justify-center gap-1.5" 
                               :class="{
                                   'text-amber-500 drop-shadow-sm': {{ $pk->status_pencapaian == 'aktif/tercapai' ? 'true' : 'false' }},
                                   'text-gray-900 drop-shadow-sm': {{ $pk->status_pencapaian != 'aktif/tercapai' ? 'true' : 'false' }} && activeTimeline == {{ $pk->id }},
                                   'text-gray-500': activeTimeline != {{ $pk->id }} && {{ $pk->status_pencapaian == 'riwayat/selesai' ? 'true' : 'false' }},
                                   'text-gray-400': activeTimeline != {{ $pk->id }} && {{ $pk->status_pencapaian == 'proyeksi/target' ? 'true' : 'false' }}
                               }">
                                {{ $pk->nama_posisi }}
                                @if($pk->operational_award)
                                    <i class="fas fa-trophy text-amber-400" title="{{ $pk->operational_award }}"></i>
                                @endif
                            </p>
                            
                            <!-- Subtext -->
                            <div class="transition-all duration-300" :class="activeTimeline == {{ $pk->id }} ? 'opacity-100 transform translate-y-0' : 'opacity-70'">
                                @if($pk->status_pencapaian == 'aktif/tercapai')
                                    <p class="text-[11px] font-bold text-amber-500 mt-1 uppercase tracking-widest">Current Position</p>
                                @elseif($pk->status_pencapaian == 'proyeksi/target')
                                    <p class="text-[11px] font-medium text-sky-500 mt-1">Target Projection</p>
                                @else
                                    <p class="text-[11px] text-gray-500 mt-1 line-clamp-1 max-w-[120px]">{{ $pk->catatan_detail ?? 'Completed' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="w-full text-center py-8 text-sm text-gray-500 flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 text-2xl mb-3"><i class="fas fa-route"></i></div>
                            No career journey data available.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Detail of Selected Timeline (Dynamic) -->
        @if($perjalananKarirs->isNotEmpty())
            <div class="mt-2 px-4 md:px-8">
                @foreach($perjalananKarirs as $pk)
                <div x-show="activeTimeline == {{ $pk->id }}" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     style="display: none;"
                     class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.04)] relative overflow-hidden mb-0 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                    
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-500 border border-sky-100 shadow-sm shrink-0">
                                <i class="far fa-calendar-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-lg">Career Details - Year {{ $pk->tahun_karir }}</h3>
                                <p class="text-[11px] text-sky-500 font-bold uppercase tracking-widest mt-1">Position & Award Information</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            @can('edit_journey')
                            <button type="button" onclick="document.getElementById('modalEditKarier_{{ $pk->id }}').classList.remove('hidden')" class="px-4 py-2 text-xs font-bold text-sky-600 bg-sky-50 hover:bg-sky-100 border border-sky-100 rounded-lg transition-all flex items-center gap-1.5">
                                <i class="fas fa-pen"></i> Edit
                            </button>
                            @endcan
                            @can('hapus_journey')
                            <form action="{{ route('employee-journey.destroy', $pk->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this career journey?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-100 rounded-lg transition-all flex items-center gap-1.5">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-indigo-200">
                            <div class="flex items-start gap-4">
                                <div class="mt-0.5 w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0 border border-indigo-100/50"><i class="fas fa-briefcase text-lg"></i></div>
                                <div class="w-full">
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1.5">Position / Title</p>
                                    <p class="font-bold text-gray-800 text-base mb-2">{{ $pk->nama_posisi }}</p>
                                    
                                    <span class="text-[11px] font-bold text-indigo-600 inline-flex items-center gap-1.5 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 mb-4">
                                        <i class="far fa-building"></i> {{ $pk->department ? $pk->department->nama_departemen : '-' }}
                                    </span>
                                    
                                    <div class="border-l-2 border-indigo-200 pl-3">
                                        <p class="text-xs text-gray-500 leading-relaxed">{{ $pk->catatan_detail ?? 'No detailed notes recorded for this year.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-amber-200">
                            <div class="flex items-start gap-4">
                                <div class="mt-0.5 w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 border border-amber-100/50"><i class="fas fa-trophy text-lg"></i></div>
                                <div class="w-full">
                                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1.5">Special Achievement</p>
                                    
                                    @if($pk->operational_award)
                                        <p class="font-bold text-gray-800 text-base mb-4">{{ $pk->operational_award }}</p>
                                        <div class="border-l-2 border-amber-200 pl-3">
                                            <p class="text-xs text-gray-500 leading-relaxed">Award recorded for this year.</p>
                                        </div>
                                    @else
                                        <p class="font-bold text-gray-400 text-sm mb-4 italic">No Awards Yet</p>
                                        <div class="border-l-2 border-amber-100 pl-3">
                                            <p class="text-xs text-gray-400 leading-relaxed italic">No awards recorded for this year.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Kesimpulan & Rekomendasi -->
    <div class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col h-full transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-0.5">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100/50">
                <i class="fas fa-clipboard-check text-lg"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-900">Summary & Recommendation</h2>
                <p class="text-[11px] text-gray-500 font-medium">Assessor qualitative evaluation</p>
            </div>
        </div>

        @php
            $activePk = $karyawan->perjalananKarirs->where('status_pencapaian', 'aktif/tercapai')->first() ?? $karyawan->perjalananKarirs->last();
        @endphp
        <form action="{{ route('employee-journey.recommendation', $karyawan->id) }}" method="POST" class="flex flex-col flex-grow">
            @csrf
            
            <div class="space-y-4 flex-grow">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Assessor Decision <span class="text-red-500">*</span></label>
                    <select name="keputusan" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('keputusan') border-red-500 @enderror" {{ auth()->user()->can('edit_journey') ? '' : 'disabled' }}>
                        <option value="">Select Decision</option>
                        <option value="Promote to Next Grade" {{ ($activePk && $activePk->keputusan == 'Promote to Next Grade') ? 'selected' : '' }}>Promote to Next Grade</option>
                        <option value="Ready for Promotion" {{ ($activePk && $activePk->keputusan == 'Ready for Promotion') ? 'selected' : '' }}>Ready for Promotion</option>
                        <option value="Fit for Current Role" {{ ($activePk && $activePk->keputusan == 'Fit for Current Role') ? 'selected' : '' }}>Fit for Current Role</option>
                        <option value="Extend Contract" {{ ($activePk && $activePk->keputusan == 'Extend Contract') ? 'selected' : '' }}>Extend Contract</option>
                        <option value="Needs Further Development" {{ ($activePk && $activePk->keputusan == 'Needs Further Development') ? 'selected' : '' }}>Needs Further Development</option>
                        <option value="Recommend for Rotation" {{ ($activePk && $activePk->keputusan == 'Recommend for Rotation') ? 'selected' : '' }}>Recommend for Rotation</option>
                        <option value="Performance Improvement Plan" {{ ($activePk && $activePk->keputusan == 'Performance Improvement Plan') ? 'selected' : '' }}>Performance Improvement Plan</option>
                        <option value="Terminate" {{ ($activePk && $activePk->keputusan == 'Terminate') ? 'selected' : '' }}>Terminate</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Qualitative Notes</label>
                    <textarea name="catatan_rekomendasi_atasan" rows="4" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3 px-3 text-sm font-medium text-gray-700 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all resize-none placeholder-gray-400" placeholder="Write overall evaluation notes here..." {{ auth()->user()->can('edit_journey') ? '' : 'readonly' }}>{{ $activePk ? $activePk->catatan_rekomendasi_atasan : '' }}</textarea>
                </div>
            </div>

            @can('edit_journey')
            <div class="pt-5 mt-auto">
                <button type="submit" class="w-full bg-slate-800 text-white rounded-xl py-3 text-[13px] font-bold shadow-sm hover:bg-slate-900 hover:shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Save Evaluation
                </button>
            </div>
            @endcan
        </form>
    </div>

    <!-- Assessment Summary -->
    @php
        $assessmentReports = $karyawan->assessmentReports->sortByDesc('tahun')->sortByDesc('periode')->sortByDesc('id')->values();
        
        $assessmentItems = $assessmentReports->mapWithKeys(function ($r, $index) {
            $periodLabel = '';
            if ($r->tahun && $r->periode) {
                $periodLabel = $r->tahun . ' - Periode ' . $r->periode;
            } elseif ($r->tahun) {
                $periodLabel = 'Tahun ' . $r->tahun;
            } elseif ($r->periode) {
                $periodLabel = 'Periode ' . $r->periode;
            } else {
                $periodLabel = 'Sesi #' . $r->id;
            }

            $scores = [
                (float) ($r->kb_kecerdasan_umum ?? 0),
                (float) ($r->sk_sistematika_kerja ?? 0),
                (float) ($r->kp_kepemimpinan ?? 0),
                (float) ($r->ms_planning_controlling ?? 0),
                (float) ($r->kp_integritas ?? 0),
            ];
            $filteredScores = array_filter($scores, fn($s) => $s > 0);
            $avg = count($filteredScores) > 0 ? round(array_sum($filteredScores) / count($filteredScores), 1) : 0.0;

            return [(string) $r->id => [
                'id' => $r->id,
                'label' => $periodLabel,
                'tahun' => $r->tahun ?? '-',
                'periode' => $r->periode ?? null,
                'avg' => number_format($avg, 1),
                'avg_raw' => $avg,
                'general_intelligence' => (float) ($r->kb_kecerdasan_umum ?? 0),
                'work_systematics' => (float) ($r->sk_sistematika_kerja ?? 0),
                'integrity' => (float) ($r->kp_integritas ?? 0),
                'leadership' => (float) ($r->kp_kepemimpinan ?? 0),
                'has_score' => count($filteredScores) > 0,
            ]];
        });

        $firstReportId = $assessmentReports->first()?->id ? (string) $assessmentReports->first()->id : '';
    @endphp

    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col h-full relative transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-0.5"
         x-data="{
            open: false,
            selectedId: '{{ $firstReportId }}',
            items: {{ json_encode($assessmentItems) }},
            init() {
                window.selectedAssessmentId = this.selectedId;
                this.$watch('selectedId', val => window.selectedAssessmentId = val);
            },
            get current() {
                return this.items[this.selectedId] || null;
            },
            get avg() {
                return this.current ? this.current.avg_raw : 0;
            },
            selectPeriod(id) {
                this.selectedId = id;
                this.open = false;
            }
         }">
        
        <!-- Header Card: Rapi 1 Baris Horizontal -->
        <div class="flex justify-between items-center mb-6 relative z-30 border-b border-gray-50 pb-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100/50 shrink-0">
                    <i class="fas fa-chart-pie text-lg"></i>
                </div>
                <div class="truncate">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider truncate">
                        Assessment Summary <span x-text="current && current.tahun !== '-' ? '(' + current.tahun + ')' : ''">{{ $assessmentReports->first()?->tahun ? '('.$assessmentReports->first()->tahun.')' : '' }}</span>
                    </h2>
                    <p class="text-[11px] text-gray-500 font-medium truncate">Employee performance score summary</p>
                </div>
            </div>

            <!-- Custom Dropdown Periode (Sesuai Konsep Sistem RadarHC) -->
            <div class="flex items-center gap-2 shrink-0">
                @if($assessmentReports->count() > 0)
                    <div class="relative inline-block text-left" @click.outside="open = false">
                        <button type="button" 
                                @click="open = !open" 
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-700 shadow-sm transition-all focus:outline-none focus:border-[#0ea5e9] cursor-pointer">
                            <i class="far fa-calendar-alt text-[#0ea5e9]"></i>
                            <span x-text="current?.label || '{{ $assessmentItems->first()['label'] ?? 'Pilih Periode' }}'">
                                {{ $assessmentItems->first()['label'] ?? 'Pilih Periode' }}
                            </span>
                            <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-1.5 min-w-[210px] bg-white rounded-lg shadow-xl border border-gray-100 p-1.5 z-50"
                             style="display: none;">
                            <div class="space-y-0.5 max-h-60 overflow-y-auto custom-scrollbar">
                                @foreach($assessmentItems as $id => $item)
                                    <button type="button" 
                                            @click="selectPeriod('{{ $id }}')"
                                            class="w-full text-left px-3 py-2 text-xs rounded-md flex items-center justify-between gap-3 transition-colors cursor-pointer"
                                            :class="selectedId === '{{ $id }}' ? 'bg-[#e0f2fe] text-[#0284c7] font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium'">
                                        <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                                        <i x-show="selectedId === '{{ $id }}'" class="fas fa-check text-[10px] text-[#0284c7]"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="relative inline-block text-left" @click.outside="open = false">
                        <button type="button" 
                                @click="open = !open" 
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-500 shadow-sm transition-all focus:outline-none cursor-pointer">
                            <i class="far fa-calendar-alt text-[#0ea5e9]"></i>
                            <span>Pilih Periode</span>
                            <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" 
                             class="absolute right-0 top-full mt-1.5 min-w-[210px] bg-white rounded-lg shadow-xl border border-gray-100 p-2 z-50 text-center"
                             style="display: none;">
                            <span class="text-xs text-gray-400 italic">Belum ada riwayat asesmen</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center h-full gap-8 relative z-10">
            
            <!-- Area Rata-Rata Skor Kiri -->
            <div class="w-full md:w-5/12 flex flex-col items-center justify-center p-6 bg-gradient-to-b from-gray-50 to-white rounded-2xl border border-gray-100 shadow-sm h-full">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Average Score</p>
                <div class="text-6xl font-black text-gray-800 tracking-tight drop-shadow-sm mb-1">
                    <span x-text="current?.avg || '0.0'">{{ $assessmentItems->first()['avg'] ?? '0.0' }}</span><span class="text-2xl text-gray-400 font-bold">/5</span>
                </div>
                
                <template x-if="avg >= 4">
                    <div class="mt-3 text-[11px] font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-1.5 shadow-sm"><i class="fas fa-star text-emerald-500"></i> Excellent</div>
                </template>
                <template x-if="avg >= 3 && avg < 4">
                    <div class="mt-3 text-[11px] font-bold text-sky-700 bg-sky-100 border border-sky-200 px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-1.5 shadow-sm"><i class="fas fa-thumbs-up text-sky-500"></i> Good</div>
                </template>
                <template x-if="avg > 0 && avg < 3">
                    <div class="mt-3 text-[11px] font-bold text-amber-700 bg-amber-100 border border-amber-200 px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-1.5 shadow-sm"><i class="fas fa-exclamation-triangle text-amber-500"></i> Needs Improvement</div>
                </template>
                <template x-if="!current || avg <= 0">
                    <div class="mt-3 text-[11px] font-bold text-gray-600 bg-gray-100 border border-gray-200 px-4 py-1.5 rounded-full uppercase tracking-widest shadow-sm">No Data</div>
                </template>
            </div>

            <!-- Detail Skor (Progress Bars) Kanan -->
            <div class="w-full md:w-7/12 space-y-5">
                
                <!-- Indikator 1 -->
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide">General Intelligence</span>
                        <span class="text-sm font-black text-gray-900" x-text="current ? parseFloat(current.general_intelligence).toFixed(1) : '0.0'">{{ number_format($assessmentItems->first()['general_intelligence'] ?? 0, 1) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                        <div class="bg-sky-500 h-full rounded-full transition-all duration-1000 shadow-sm" :style="'width: ' + (current ? ((current.general_intelligence / 5) * 100) : 0) + '%'" style="width: {{ ((($assessmentItems->first()['general_intelligence'] ?? 0) / 5) * 100) }}%"></div>
                    </div>
                </div>

                <!-- Indikator 2 -->
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide">Work Systematics</span>
                        <span class="text-sm font-black text-gray-900" x-text="current ? parseFloat(current.work_systematics).toFixed(1) : '0.0'">{{ number_format($assessmentItems->first()['work_systematics'] ?? 0, 1) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000 shadow-sm" :style="'width: ' + (current ? ((current.work_systematics / 5) * 100) : 0) + '%'" style="width: {{ ((($assessmentItems->first()['work_systematics'] ?? 0) / 5) * 100) }}%"></div>
                    </div>
                </div>

                <!-- Indikator 3 -->
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide">Integrity</span>
                        <span class="text-sm font-black text-gray-900" x-text="current ? parseFloat(current.integrity).toFixed(1) : '0.0'">{{ number_format($assessmentItems->first()['integrity'] ?? 0, 1) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000 shadow-sm" :style="'width: ' + (current ? ((current.integrity / 5) * 100) : 0) + '%'" style="width: {{ ((($assessmentItems->first()['integrity'] ?? 0) / 5) * 100) }}%"></div>
                    </div>
                </div>

                <!-- Indikator 4 -->
                <div>
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide">Leadership</span>
                        <span class="text-sm font-black text-gray-900" x-text="current ? parseFloat(current.leadership).toFixed(1) : '0.0'">{{ number_format($assessmentItems->first()['leadership'] ?? 0, 1) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden shadow-inner">
                        <div class="bg-amber-500 h-full rounded-full transition-all duration-1000 shadow-sm" :style="'width: ' + (current ? ((current.leadership / 5) * 100) : 0) + '%'" style="width: {{ ((($assessmentItems->first()['leadership'] ?? 0) / 5) * 100) }}%"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- IDP Summary & Radar Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 relative transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-0.5 flex flex-col h-full z-20">
        
        @php
            // Helper closure to check if an IDP has actual assessment scores
            $hasScore = function($idp) {
                if (!$idp || !$idp->penilaian) return false;
                $scores = [
                    $idp->penilaian->head_managing_change, $idp->penilaian->head_empowerment, $idp->penilaian->head_fostering_teamwork,
                    $idp->penilaian->head_analysis_decision, $idp->penilaian->head_planning_controlling, $idp->penilaian->head_integrity_ethics,
                    $idp->penilaian->head_excellence, $idp->penilaian->head_compassion, $idp->penilaian->head_humility,
                    $idp->penilaian->staff_leads_courageously, $idp->penilaian->staff_coach_develop, $idp->penilaian->staff_teamwork,
                    $idp->penilaian->staff_trouble_shooting, $idp->penilaian->staff_customer_satisfaction, $idp->penilaian->staff_cross_functional,
                    $idp->penilaian->staff_quality_focus, $idp->penilaian->staff_continuous_improvement, $idp->penilaian->staff_planning_monitoring,
                    $idp->penilaian->staff_personal_integrity, $idp->penilaian->staff_drive_for_result, $idp->penilaian->staff_learning,
                    $idp->penilaian->unit_directiveness, $idp->penilaian->unit_monitoring, $idp->penilaian->unit_teamwork,
                    $idp->penilaian->unit_quality_customer, $idp->penilaian->unit_continuous_improvement, $idp->penilaian->unit_personal_integrity,
                ];
                foreach ($scores as $s) {
                    if (!is_null($s) && $s !== '' && (float)$s > 0) return true;
                }
                return false;
            };

            // Find first IDP that has scores, or fallback to the latest IDP
            $idpWithScore = $karyawan->idps->first($hasScore);
            $activeIdp = $idpWithScore ?? $karyawan->idps->sortByDesc('created_at')->first();
            $activeLabel = $activeIdp ? ($activeIdp->tahun . ($activeIdp->periode ? ' (P' . $activeIdp->periode . ')' : '')) : 'Pilih Periode';
        @endphp

        <!-- Efek Background Hiasan (dibatasi di dalam container rounded) -->
        <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-sky-50 rounded-full opacity-60 blur-3xl"></div>
        </div>

        <!-- Header Card: Rapi 1 Baris Horizontal -->
        <div class="flex justify-between items-center mb-4 relative z-30 border-b border-gray-50 pb-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center text-sky-500 shadow-sm border border-sky-100/50 shrink-0">
                    <i class="fas fa-bullseye text-lg"></i>
                </div>
                <div class="truncate">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider truncate">IDP Summary</h2>
                    <p class="text-[11px] text-gray-500 font-medium truncate">Progress & Competency Mapping</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
                @if($karyawan->idps->count() > 1)
                <!-- Custom Dropdown Periode (Sesuai Konsep Sistem RadarHC) -->
                <div x-data="{
                    open: false,
                    selectedId: '{{ $activeIdp?->id }}',
                    selectedLabel: '{{ $activeLabel }}',
                    init() {
                        window.selectedIdpId = this.selectedId;
                        this.$watch('selectedId', val => window.selectedIdpId = val);
                    },
                    selectPeriod(id, label) {
                        this.selectedId = id;
                        this.selectedLabel = label;
                        window.selectedIdpId = id;
                        this.open = false;
                        if (typeof window.switchIdpPeriod === 'function') {
                            window.switchIdpPeriod(id);
                        }
                    }
                }" class="relative inline-block text-left" @click.outside="open = false">
                    <!-- Trigger Button (Sesuai Standar UI Sistem) -->
                    <button type="button" 
                        @click="open = !open" 
                        class="inline-flex items-center gap-2 px-3 py-1 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-700 shadow-sm transition-all focus:outline-none focus:border-[#0ea5e9] cursor-pointer">
                        <span x-text="selectedLabel">{{ $activeLabel }}</span>
                        <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <!-- Floating Dropdown Menu (Clean & Sesuai Tom Select Sistem) -->
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-1.5 min-w-[230px] bg-white rounded-lg shadow-xl border border-gray-100 p-1.5 z-50"
                        style="display: none;">
                        
                        <div class="space-y-0.5 max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($karyawan->idps as $idp)
                                @php
                                    $idpLabel = ($idp->tahun ?? '-') . ($idp->periode ? ' (P' . $idp->periode . ')' : '');
                                    $isCompleted = ($idp->status_idp === 'completed');
                                @endphp
                                <button type="button" 
                                    @click="selectPeriod('{{ $idp->id }}', '{{ $idpLabel }}')"
                                    class="w-full text-left px-3 py-2 text-xs rounded-md flex items-center justify-between gap-3 transition-colors cursor-pointer"
                                    :class="selectedId == '{{ $idp->id }}' ? 'bg-[#e0f2fe] text-[#0284c7] font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium'">
                                    <span class="whitespace-nowrap">{{ $idp->tahun ?? '-' }}{{ $idp->periode ? ' (Periode ' . $idp->periode . ')' : '' }}</span>
                                    @if($isCompleted)
                                        <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-full shrink-0">
                                            Completed
                                        </span>
                                    @else
                                        <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-full shrink-0">
                                            In Progress
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @elseif($activeIdp)
                <!-- Jika hanya ada 1 data IDP, tampilkan label periode rapi -->
                <span class="text-[11px] font-semibold text-gray-600 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-lg">
                    Tahun {{ $activeIdp->tahun ?? '-' }}{{ $activeIdp->periode ? ' - Periode ' . $activeIdp->periode : '' }}
                </span>
                @endif

                <!-- Dynamic Status Badge Container -->
                <div id="idpStatusBadgeContainer">
                    @if($activeIdp)
                        @if($activeIdp->status_idp === 'completed')
                            <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completed
                            </span>
                        @elseif($activeIdp->status_idp === 'in_progress')
                            <span class="bg-orange-50 border border-orange-200 text-orange-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span> In Progress
                            </span>
                        @else
                            <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                            </span>
                        @endif
                    @else
                        <span class="bg-gray-100 border border-gray-200 text-gray-500 text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                            No Data
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Area Konten IDP -->
        <div class="mt-2 flex flex-col flex-grow relative z-10">
            <!-- Chart -->
            <div class="w-full h-72 flex items-center justify-center relative bg-gray-50/50 rounded-xl border border-gray-100/50 p-2">
                <canvas id="radarChart" style="width: 100%; height: 100%;"></canvas>
            </div>

            @if($activeIdp)
            <!-- Info Footer IDP (Mentor, Rata-rata Skor, dan Link Detail) -->
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500 px-1">
                <div class="flex items-center gap-1.5">
                    <span class="text-gray-400">Mentor:</span>
                    <span id="idpMentorText" class="font-semibold text-gray-700">{{ $activeIdp->mentor ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span id="idpScoreAvg" class="font-bold text-sky-600 text-xs"></span>
                    <a id="idpDetailLink" href="{{ route('user-idp.show', $activeIdp->id) }}" class="text-xs text-sky-600 hover:text-sky-800 hover:underline inline-flex items-center gap-1 font-medium transition-colors">
                        <span>Detail IDP</span>
                        <i class="fas fa-chevron-right text-[9px]"></i>
                    </a>
                </div>
            </div>

            <!-- Alert bila belum dinilai (otomatis tampil/hilang saat ganti pilihan di dropdown) -->
            <div id="idpEmptyAlert" class="mt-2 text-center py-2 px-3 text-xs bg-amber-50 text-amber-700 border border-amber-200/60 rounded-xl transition-all duration-300 {{ $hasScore($activeIdp) ? 'hidden' : '' }}">
                <i class="fas fa-info-circle mr-1 text-amber-500"></i> Penilaian IDP pada periode ini belum diisi.
            </div>
            @endif
        </div>
    </div>

    <!-- Training Progress -->
   <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 relative overflow-hidden transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-0.5 flex flex-col h-full">
        
        <!-- Efek Background Hiasan -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-50 rounded-full opacity-60 blur-3xl pointer-events-none"></div>

        <!-- Header Card -->
        <div class="flex justify-between items-start mb-6 relative z-10 border-b border-gray-50 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100/50 shrink-0">
                    <i class="fas fa-graduation-cap text-lg"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Training Progress</h2>
                    <p class="text-[11px] text-gray-500 font-medium">Training target fulfillment</p>
                </div>
            </div>
        </div>
        
        @php
            $training = $trainings->first();
            $riwayat = \App\Models\RiwayatTraining::where('id_karyawan', $karyawan->id)->get();
            $c_mandatory = $riwayat->where('kategori', 'mandatory')->filter(function($r) {
                return $r->eva1_status == 'completed' && $r->eva2_status == 'completed' && $r->eva3_status == 'completed';
            })->count();
            $c_skill = $riwayat->where('kategori', 'skill')->filter(function($r) {
                return $r->eva1_status == 'completed' && $r->eva2_status == 'completed' && $r->eva3_status == 'completed';
            })->count();
            $c_managerial = $riwayat->where('kategori', 'managerial')->filter(function($r) {
                return $r->eva1_status == 'completed' && $r->eva2_status == 'completed' && $r->eva3_status == 'completed';
            })->count();

            $achieveMandatory = ($training && $training->target_mandatory > 0) ? min(100, round(($c_mandatory / $training->target_mandatory) * 100)) : 0;
            $achieveSkill = ($training && $training->target_skill > 0) ? min(100, round(($c_skill / $training->target_skill) * 100)) : 0;
            $achieveManagerial = ($training && $training->target_managerial > 0) ? min(100, round(($c_managerial / $training->target_managerial) * 100)) : 0;
        @endphp

        <!-- Area Konten Progress (mb-auto agar rata atas jika tidak ada elemen lain di bawahnya) -->
        <div class="space-y-4 flex-grow relative z-10 mb-auto">
            
            <!-- Mandatory -->
            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 transition-colors hover:bg-emerald-50/50 hover:border-emerald-200">
                <div class="flex justify-between items-end mb-2.5">
                    <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Mandatory
                    </span>
                    <span class="text-sm font-black text-gray-900">{{ $achieveMandatory }}<span class="text-[10px] text-gray-500 font-bold ml-0.5">%</span></span>
                </div>
                <div class="w-full bg-gray-200/60 rounded-full h-2.5 overflow-hidden shadow-inner">
                    <div class="bg-[#22c55e] h-full rounded-full transition-all duration-1000 shadow-sm relative" 
                         style="width: {{ $achieveMandatory }}%">
                        <div class="absolute top-0 left-0 right-0 h-[1px] bg-white/40"></div>
                    </div>
                </div>
            </div>

            <!-- Skill -->
            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 transition-colors hover:bg-sky-50/50 hover:border-sky-200">
                <div class="flex justify-between items-end mb-2.5">
                    <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span> Skill
                    </span>
                    <span class="text-sm font-black text-gray-900">{{ $achieveSkill }}<span class="text-[10px] text-gray-500 font-bold ml-0.5">%</span></span>
                </div>
                <div class="w-full bg-gray-200/60 rounded-full h-2.5 overflow-hidden shadow-inner">
                    <div class="bg-[#0ea5e9] h-full rounded-full transition-all duration-1000 shadow-sm relative"
                         style="width: {{ $achieveSkill }}%">
                        <div class="absolute top-0 left-0 right-0 h-[1px] bg-white/40"></div>
                    </div>
                </div>
            </div>

            <!-- Managerial -->
            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 transition-colors hover:bg-rose-50/50 hover:border-rose-200">
                <div class="flex justify-between items-end mb-2.5">
                    <span class="text-[12px] font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Managerial
                    </span>
                    <span class="text-sm font-black text-gray-900">{{ $achieveManagerial }}<span class="text-[10px] text-gray-500 font-bold ml-0.5">%</span></span>
                </div>
                <div class="w-full bg-gray-200/60 rounded-full h-2.5 overflow-hidden shadow-inner">
                    <div class="bg-[#f43f5e] h-full rounded-full transition-all duration-1000 shadow-sm relative"
                         style="width: {{ $achieveManagerial }}%">
                        <div class="absolute top-0 left-0 right-0 h-[1px] bg-white/40"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Sub-info / Footer -->
        <div class="mt-3.5 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500 relative z-10">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-check-double text-emerald-500"></i>
                Status: Eva 1, Eva 2, & Eva 3 Completed
            </span>
            <a href="{{ auth()->check() && strtolower((string) auth()->user()->role) === 'admin' ? route('training.show', $karyawan->id) : route('user-training.show', $karyawan->id) }}" class="text-emerald-600 hover:text-emerald-700 font-bold hover:underline flex items-center gap-1">
                Training Details <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    
    <!-- Certification -->
    <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col h-[280px] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 uppercase tracking-wider">
                <i class="fas fa-certificate text-emerald-500"></i> Certification
            </h2>
            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded border border-emerald-100">
                {{ $karyawan->certifications->where('status_sertifikat', 'aktif')->count() }} Active Certifications
            </span>
        </div>
        
        <!-- Area Konten Certification dengan batas tinggi dan scroll -->
        <div class="space-y-3 overflow-y-auto pr-2 flex-grow no-scrollbar">
            @forelse($karyawan->certifications as $cert)
                <div class="p-3 border border-gray-100 rounded-xl flex gap-3 hover:border-emerald-200 hover:shadow-sm transition-all bg-gray-50/50">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-award text-base"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-xs">{{ $cert->nama_sertifikasi }}</h4>
                        <p class="text-[10px] text-gray-500 mt-1">
                            Issued: {{ \Carbon\Carbon::parse($cert->terbit)->format('M Y') }} &bull; Provider: {{ $cert->penerbit }}
                            @if($cert->status_sertifikat != 'aktif')
                                <span class="text-red-500 font-bold ml-1">(Expired)</span>
                            @endif
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-6 italic bg-gray-50/50 rounded-xl border border-dashed border-gray-200 flex-1 flex items-center justify-center">
                    No certification data available.
                </p>
            @endforelse
        </div>
    </div>

    <!-- SCARF Profile -->
    <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col h-full transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 uppercase tracking-wider">
                <i class="fas fa-brain text-sky-500"></i> SCARF Profile
            </h2>
            @can('edit_journey')
            <button onclick="document.getElementById('editScarfModal').classList.remove('hidden')" class="text-xs font-bold text-[#0ea5e9] flex items-center gap-1.5 hover:text-[#0284c7] transition-all py-1.5 px-3 rounded-lg hover:bg-sky-50 shadow-sm border border-gray-100 hover:border-sky-100 bg-white">
                <i class="fas fa-cog"></i> Manage
            </button>
            @endcan
        </div>
        
        <!-- Area Konten SCARF Profile -->
        <div class="flex flex-col gap-4 flex-grow justify-center pb-2">
            <!-- Status -->
            <div class="flex items-center gap-3">
                <div class="w-24 text-[11px] font-bold text-gray-800 uppercase tracking-wide">Status</div>
                <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden relative shadow-inner">
                    <div class="bg-[#1b7356] h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $karyawan->scarf->status ?? 0 }}%"></div>
                </div>
                <div class="w-10 text-right text-[12px] font-bold text-gray-900">{{ $karyawan->scarf->status ?? 0 }}%</div>
            </div>
            
            <!-- Certainty -->
            <div class="flex items-center gap-3">
                <div class="w-24 text-[11px] font-bold text-gray-800 uppercase tracking-wide">Certainty</div>
                <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden relative shadow-inner">
                    <div class="bg-[#fbb93a] h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $karyawan->scarf->certainty ?? 0 }}%"></div>
                </div>
                <div class="w-10 text-right text-[12px] font-bold text-gray-900">{{ $karyawan->scarf->certainty ?? 0 }}%</div>
            </div>
            
            <!-- Autonomy -->
            <div class="flex items-center gap-3">
                <div class="w-24 text-[11px] font-bold text-gray-800 uppercase tracking-wide">Autonomy</div>
                <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden relative shadow-inner">
                    <div class="bg-[#12a1b9] h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $karyawan->scarf->autonomy ?? 0 }}%"></div>
                </div>
                <div class="w-10 text-right text-[12px] font-bold text-gray-900">{{ $karyawan->scarf->autonomy ?? 0 }}%</div>
            </div>
            
            <!-- Relatedness -->
            <div class="flex items-center gap-3">
                <div class="w-24 text-[11px] font-bold text-gray-800 uppercase tracking-wide">Relatedness</div>
                <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden relative shadow-inner">
                    <div class="bg-[#c22e23] h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $karyawan->scarf->relatedness ?? 0 }}%"></div>
                </div>
                <div class="w-10 text-right text-[12px] font-bold text-gray-900">{{ $karyawan->scarf->relatedness ?? 0 }}%</div>
            </div>
            
            <!-- Fairness -->
            <div class="flex items-center gap-3">
                <div class="w-24 text-[11px] font-bold text-gray-800 uppercase tracking-wide">Fairness</div>
                <div class="flex-1 bg-gray-100 h-2.5 rounded-full overflow-hidden relative shadow-inner">
                    <div class="bg-[#606a66] h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $karyawan->scarf->fairness ?? 0 }}%"></div>
                </div>
                <div class="w-10 text-right text-[12px] font-bold text-gray-900">{{ $karyawan->scarf->fairness ?? 0 }}%</div>
            </div>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Via Character -->
    <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200 flex flex-col justify-between h-full">
        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Via Character</h2>
                @can('tambah_journey')
                <button onclick="document.getElementById('addViaCharacterModal').classList.remove('hidden')" class="btn-3d bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center gap-2 transition-all hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i> Add Data
                </button>
                @endcan
            </div>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-[10px] font-bold text-white uppercase tracking-wider bg-[#0c7c85]">
                            <th class="px-4 py-3 w-[42%] text-center border-b border-[#0c7c85]">Overuse Strength</th>
                            <th class="px-4 py-3 bg-[#9e2a2b] w-[42%] text-center border-b border-[#9e2a2b]">Underuse Strength</th>
                            @canany(['edit_journey', 'hapus_journey'])
                            <th class="px-3 py-3 bg-gray-100 text-gray-600 w-[16%] text-center border-b border-gray-200">Actions</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($karyawan->viaCharacters ?? [] as $via)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 font-medium text-gray-800 bg-[#eef8f8] text-center">{{ $via->overuse_strength }}</td>
                            <td class="px-4 py-3.5 font-medium text-gray-800 bg-[#fcedea] text-center">{{ $via->underuse_strength }}</td>
                            @canany(['edit_journey', 'hapus_journey'])
                            <td class="px-3 py-3.5 text-center bg-white">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="document.getElementById('editViaCharacterModal{{ $via->id }}').classList.remove('hidden')" class="text-[#0ea5e9] hover:text-[#0284c7] transition-colors p-1.5 rounded-lg hover:bg-sky-50 shadow-sm border border-transparent hover:border-sky-100" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                                    <form action="{{ route('employee-via-character.destroy', $via->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 transition-colors p-1.5 rounded-lg hover:bg-rose-50 shadow-sm border border-transparent hover:border-rose-100" title="Delete"><i class="fas fa-trash-alt text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                            @endcanany
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->can('edit_journey') || auth()->user()->can('hapus_journey')) ? 3 : 2 }}" class="px-6 py-8 text-center text-gray-400 text-xs italic bg-gray-50/50">No Via Character data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Strength & Weakness -->
    <div class="bg-white rounded-2xl p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200 flex flex-col justify-between h-full">
        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Strength & Weakness</h2>
                @can('tambah_journey')
                <button onclick="document.getElementById('addStrengthModal').classList.remove('hidden')" class="btn-3d bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center gap-2 transition-all hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i> Add Data
                </button>
                @endcan
            </div>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-[10px] font-bold text-white uppercase tracking-wider bg-[#2563eb]">
                            <th class="px-4 py-3 w-[42%] text-center border-b border-[#2563eb]">Strength</th>
                            <th class="px-4 py-3 bg-[#b45309] w-[42%] text-center border-b border-[#b45309]">Weakness</th>
                            @canany(['edit_journey', 'hapus_journey'])
                            <th class="px-3 py-3 bg-gray-100 text-gray-600 w-[16%] text-center border-b border-gray-200">Actions</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($karyawan->strengths ?? [] as $sw)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 font-medium text-gray-800 bg-[#eff6ff] text-center">{{ $sw->strength }}</td>
                            <td class="px-4 py-3.5 font-medium text-gray-800 bg-[#fffbeb] text-center">{{ $sw->weakness }}</td>
                            @canany(['edit_journey', 'hapus_journey'])
                            <td class="px-3 py-3.5 text-center bg-white">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="document.getElementById('editStrengthModal{{ $sw->id }}').classList.remove('hidden')" class="text-[#0ea5e9] hover:text-[#0284c7] transition-colors p-1.5 rounded-lg hover:bg-sky-50 shadow-sm border border-transparent hover:border-sky-100" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                                    <form action="{{ route('employee-strength.destroy', $sw->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 transition-colors p-1.5 rounded-lg hover:bg-rose-50 shadow-sm border border-transparent hover:border-rose-100" title="Delete"><i class="fas fa-trash-alt text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                            @endcanany
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->can('edit_journey') || auth()->user()->can('hapus_journey')) ? 3 : 2 }}" class="px-6 py-8 text-center text-gray-400 text-xs italic bg-gray-50/50">No Strength & Weakness data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Mentoring & Coaching Section (Matrix & GROW Model) -->
@php
    $mentorings = \App\Models\Mentoring::where('id_karyawan', $karyawan->id)
        ->orderByDesc('tahun')
        ->orderByDesc('periode')
        ->orderByDesc('id')
        ->get();

    $mentoringItems = $mentorings->mapWithKeys(function ($m, $index) {
        $comp = $m->competency;
        $comm = $m->commitment;
        $box = null;
       if ($comp === 'Rendah' && $comm === 'Tinggi') $box = 'BR';
        elseif ($comp === 'Rendah' && $comm === 'Rendah') $box = 'BL';
        elseif ($comp === 'Tinggi' && $comm === 'Tinggi') $box = 'TR';
        elseif ($comp === 'Tinggi' && $comm === 'Rendah') $box = 'TL';

        $periodLabel = '';
        if ($m->tahun && $m->periode) {
            $periodLabel = $m->tahun . ' - Periode ' . $m->periode;
        } elseif ($m->tahun) {
            $periodLabel = 'Tahun ' . $m->tahun;
        } elseif ($m->periode) {
            $periodLabel = 'Periode ' . $m->periode;
        } else {
            $periodLabel = 'Sesi #' . $m->id;
        }


        $hasGrow = (bool) ($m->grow_goals || $m->grow_realitas || $m->grow_options || $m->grow_wrap_up || $m->grow_insight);

        return [(string) $m->id => [
            'id' => $m->id,
            'label' => $periodLabel,
            'tahun' => $m->tahun,
            'periode' => $m->periode,
            'competency' => $comp,
            'commitment' => $comm,
            'box' => $box,
            'mentor' => $m->mentor ?? '-',
            'grow_goals' => $m->grow_goals,
            'grow_realitas' => $m->grow_realitas,
            'grow_options' => $m->grow_options,
            'grow_wrap_up' => $m->grow_wrap_up,
            'grow_insight' => $m->grow_insight,
            'has_grow' => $hasGrow,
            'url' => route('user-mentoring.show', $m->id),
        ]];
    });

    $firstMentoringId = $mentorings->first()?->id ? (string) $mentorings->first()->id : '';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6"
     x-data="{
        selectedId: '{{ $firstMentoringId }}',
        items: {{ json_encode($mentoringItems) }},
        init() {
            window.selectedMentoringId = this.selectedId;
            this.$watch('selectedId', val => window.selectedMentoringId = val);
        },
        get current() {
            return this.items[this.selectedId] || null;
        },
        get activeBox() {
            return this.current ? this.current.box : null;
        }
     }">
    <!-- Mentoring & Coaching Matrix -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200 flex flex-col justify-between h-full">
        <div class="flex-1 flex flex-col">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 sm:mb-5">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-bold text-gray-900">Mentoring & Coaching Matrix</h2>
                    </div>
                    <div class="flex items-center gap-3 mt-1.5 text-xs" x-show="current" x-cloak>
                        <div class="flex items-center gap-1.5">
                            <span class="text-gray-400">Competency:</span>
                            <span class="font-bold px-2 py-0.5 rounded text-[11px]"
                                  :class="current?.competency === 'Tinggi' ? 'bg-emerald-100 text-emerald-800' : (current?.competency === 'Rendah' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-500')"
                                  x-text="current?.competency ? (current.competency === 'Tinggi' ? 'High' : 'Low') : '-'"></span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-gray-400">Commitment:</span>
                            <span class="font-bold px-2 py-0.5 rounded text-[11px]"
                                  :class="current?.commitment === 'Tinggi' ? 'bg-emerald-100 text-emerald-800' : (current?.commitment === 'Rendah' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-500')"
                                  x-text="current?.commitment ? (current.commitment === 'Tinggi' ? 'High' : 'Low') : '-'"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-center">
                    @if($mentorings->count() > 0)
                        <!-- Custom Dropdown Periode (Sesuai Konsep Sistem RadarHC) -->
                        <div x-data="{ open: false }" class="relative inline-block text-left" @click.outside="open = false">
                            <!-- Trigger Button (Sesuai Standar UI Sistem) -->
                            <button type="button" 
                                    @click="open = !open" 
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-700 shadow-sm transition-all focus:outline-none focus:border-[#0ea5e9] cursor-pointer">
                                <i class="far fa-calendar-alt text-[#0ea5e9]"></i>
                                <span x-text="current?.label || '{{ $mentoringItems->first()['label'] ?? 'Pilih Periode' }}'">
                                    {{ $mentoringItems->first()['label'] ?? 'Pilih Periode' }}
                                </span>
                                <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Floating Dropdown Menu (Clean & Sesuai Tom Select Sistem) -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-1.5 min-w-[210px] bg-white rounded-lg shadow-xl border border-gray-100 p-1.5 z-50"
                                 style="display: none;">
                                <div class="space-y-0.5 max-h-60 overflow-y-auto custom-scrollbar">
                                    @foreach($mentoringItems as $id => $item)
                                        <button type="button" 
                                                @click="selectedId = '{{ $id }}'; open = false"
                                                class="w-full text-left px-3 py-2 text-xs rounded-md flex items-center justify-between gap-3 transition-colors cursor-pointer"
                                                :class="selectedId === '{{ $id }}' ? 'bg-[#e0f2fe] text-[#0284c7] font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium'">
                                            <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                                            <i x-show="selectedId === '{{ $id }}'" class="fas fa-check text-[10px] text-[#0284c7]"></i>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div x-data="{ open: false }" class="relative inline-block text-left" @click.outside="open = false">
                            <button type="button" 
                                    @click="open = !open" 
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-500 shadow-sm transition-all focus:outline-none cursor-pointer">
                                <i class="far fa-calendar-alt text-[#0ea5e9]"></i>
                                <span>Pilih Periode</span>
                                <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-1.5 min-w-[210px] bg-white rounded-lg shadow-xl border border-gray-100 p-2 z-50 text-center"
                                 style="display: none;">
                                <span class="text-xs text-gray-400 italic">Belum ada riwayat mentoring</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="w-full rounded-2xl overflow-hidden grid grid-cols-2 shadow-xs border border-gray-100 font-extrabold text-xs sm:text-[13px] tracking-wider text-white flex-1 min-h-[360px] sm:min-h-[400px] lg:min-h-[420px]">
                <!-- Top Left: Yellow (Comp: Rendah, Comm: Tinggi) -->
               <div class="relative flex flex-col justify-center items-center text-center px-2 sm:px-4 py-5 sm:py-7 space-y-2 sm:space-y-2.5 whitespace-nowrap transition-all duration-300"
                    :class="(activeBox && activeBox !== 'TL') ? 'opacity-30 bg-[#facd42] text-[#8a6800]' : 'opacity-100 z-10'"
                    :style="activeBox === 'TL' ? 'background: linear-gradient(135deg, #fde065 0%, #facd42 60%, #f3c332 100%); box-shadow: 3px 3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.5), inset 0 -1.5px 2px rgba(0,0,0,0.06); color: #7c5c00;' : 'background-color: #facd42; color: #8a6800;'">
                    <div x-show="activeBox === 'TL'" class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10" :style="activeBox === 'TL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.6);' : ''">DIRECTING - MODERATE</div>
                    <div class="relative z-10" :style="activeBox === 'TL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.6);' : ''">MENTORING - MODERATE</div>
                    <div class="relative z-10" :style="activeBox === 'TL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.6);' : ''">COACHING - MODERATE</div>
                </div>
                
                <!-- Top Right: Blue (Comp: Rendah, Comm: Rendah) -->
                <div class="relative flex flex-col justify-center items-center text-center p-5 sm:p-7 text-white space-y-2 sm:space-y-2.5 transition-all duration-300"
                     :class="(activeBox && activeBox !== 'TR') ? 'opacity-30 bg-[#4485f5]' : 'opacity-100 z-10'"
                     :style="activeBox === 'TR' 
                        ? 'background: linear-gradient(135deg, #5390f7 0%, #4485f5 60%, #397ceb 100%); box-shadow: -3px 3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.35), inset 0 -1.5px 2px rgba(0,0,0,0.08);' 
                        : 'background-color: #4485f5;'">
                    <div x-show="activeBox === 'TR'" class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10" :style="activeBox === 'TR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25);' : ''">DIRECTING - LOW</div>
                    <div class="relative z-10" :style="activeBox === 'TR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25);' : ''">MENTORING - LOW</div>
                    <div class="relative z-10" :style="activeBox === 'TR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25);' : ''">COACHING - HIGH</div>
                </div>
                
                <!-- Bottom Left: Green (Comp: Tinggi, Comm: Tinggi) -->
                <div class="relative flex flex-col justify-center items-center text-center p-5 sm:p-7 space-y-2 sm:space-y-2.5 transition-all duration-300"
                     :class="(activeBox && activeBox !== 'BL') ? 'opacity-30 bg-[#78c87f] text-[#144918]' : 'opacity-100 z-10'"
                     :style="activeBox === 'BL' 
                        ? 'background: linear-gradient(135deg, #87d38e 0%, #78c87f 60%, #6abd72 100%); box-shadow: 3px -3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.4), inset 0 -1.5px 2px rgba(0,0,0,0.06); color: #144918;' 
                        : 'background-color: #78c87f; color: #144918;'">
                    <div x-show="activeBox === 'BL'" class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10" :style="activeBox === 'BL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.5);' : ''">DIRECTING - HIGH</div>
                    <div class="relative z-10" :style="activeBox === 'BL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.5);' : ''">MENTORING - HIGH</div>
                    <div class="relative z-10" :style="activeBox === 'BL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.5);' : ''">COACHING - LOW</div>
                </div>
                
                <!-- Bottom Right: Red/Coral (Comp: Tinggi, Comm: Rendah) -->
                <div class="relative flex flex-col justify-center items-center text-center p-5 sm:p-7 text-white space-y-2 sm:space-y-2.5 transition-all duration-300"
                     :class="(activeBox && activeBox !== 'BR') ? 'opacity-30 bg-[#f05051]' : 'opacity-100 z-10'"
                     :style="activeBox === 'BR' 
                        ? 'background: linear-gradient(135deg, #f75d5e 0%, #f05051 60%, #e84546 100%); box-shadow: -3px -3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.35), inset 0 -1.5px 2px rgba(0,0,0,0.08);' 
                        : 'background-color: #f05051;'">
                    <div x-show="activeBox === 'BR'" class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10" :style="activeBox === 'BR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25);' : ''">DIRECTING - MODERATE</div>
                    <div class="relative z-10" :style="activeBox === 'BR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25);' : ''">MENTORING - HIGH</div>
                    <div class="relative z-10" :style="activeBox === 'BR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25);' : ''">COACHING - MODERATE</div>
                </div>
            </div>
        </div>
    </div>

    <!-- GROW Model -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200 flex flex-col justify-between h-full">
        <div class="flex-1 flex flex-col justify-between gap-3">
            <!-- Header Card: Title & Dropdown Periode (Synced with Mentoring Matrix) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                            <i class="fas fa-seedling text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">GROW Model</h2>
                    </div>
                </div>

                <!-- Custom Dropdown Periode (Sesuai Konsep Sistem RadarHC - Synced dengan Mentoring Matrix) -->
                <div class="flex items-center gap-2 self-start sm:self-center">
                    @if($mentorings->count() > 0)
                        <div x-data="{ open: false }" class="relative inline-block text-left" @click.outside="open = false">
                            <button type="button" 
                                    @click="open = !open" 
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-700 shadow-sm transition-all focus:outline-none focus:border-[#0ea5e9] cursor-pointer">
                                <i class="far fa-calendar-alt text-[#0ea5e9]"></i>
                                <span x-text="current?.label || '{{ $mentoringItems->first()['label'] ?? 'Pilih Periode' }}'">
                                    {{ $mentoringItems->first()['label'] ?? 'Pilih Periode' }}
                                </span>
                                <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-1.5 min-w-[210px] bg-white rounded-lg shadow-xl border border-gray-100 p-1.5 z-50"
                                 style="display: none;">
                                <div class="space-y-0.5 max-h-60 overflow-y-auto custom-scrollbar">
                                    @foreach($mentoringItems as $id => $item)
                                        <button type="button" 
                                                @click="selectedId = '{{ $id }}'; open = false"
                                                class="w-full text-left px-3 py-2 text-xs rounded-md flex items-center justify-between gap-3 transition-colors cursor-pointer"
                                                :class="selectedId === '{{ $id }}' ? 'bg-[#e0f2fe] text-[#0284c7] font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium'">
                                            <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                                            <i x-show="selectedId === '{{ $id }}'" class="fas fa-check text-[10px] text-[#0284c7]"></i>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div x-data="{ open: false }" class="relative inline-block text-left" @click.outside="open = false">
                            <button type="button" 
                                    @click="open = !open" 
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-semibold text-gray-500 shadow-sm transition-all focus:outline-none cursor-pointer">
                                <i class="far fa-calendar-alt text-[#0ea5e9]"></i>
                                <span>Pilih Periode</span>
                                <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 top-full mt-1.5 min-w-[210px] bg-white rounded-lg shadow-xl border border-gray-100 p-2 z-50 text-center"
                                 style="display: none;">
                                <span class="text-xs text-gray-400 italic">Belum ada riwayat mentoring</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Yellow Header Bar -->
            <div class="w-full bg-[#fbbf24] text-gray-900 font-extrabold text-sm sm:text-base py-3 sm:py-3.5 px-4 rounded-xl text-center tracking-wider shadow-xs uppercase flex items-center justify-center min-h-[48px] sm:min-h-[52px] select-none shrink-0">
                GROW MODEL
            </div>

            <!-- GROW Model Items (Selalu tampil walau data kosong) -->
            <div class="space-y-3 flex-1 flex flex-col justify-between">
                <!-- Goals -->
                <div class="flex items-stretch rounded-xl overflow-hidden shadow-xs flex-1 min-h-[48px] sm:min-h-[52px]">
                    <div class="bg-[#059669] text-white font-extrabold text-xs sm:text-sm tracking-wider w-28 sm:w-32 py-2.5 px-3 flex items-center justify-center shrink-0 uppercase select-none">
                        GOALS
                    </div>
                    <div class="bg-[#d1fae5] px-6 py-2.5 text-gray-800 flex-1 min-w-0 text-sm font-semibold flex items-center leading-snug break-words whitespace-pre-line max-h-24 sm:max-h-28 overflow-y-auto custom-scrollbar" :title="current?.grow_goals || ''">
                        <div class="my-auto w-full" x-text="current?.grow_goals || '-'"></div>
                    </div>
                </div>

                <!-- Reality -->
                <div class="flex items-stretch rounded-xl overflow-hidden shadow-xs flex-1 min-h-[48px] sm:min-h-[52px]">
                    <div class="bg-[#3b82f6] text-white font-extrabold text-xs sm:text-sm tracking-wider w-28 sm:w-32 py-2.5 px-3 flex items-center justify-center shrink-0 uppercase select-none">
                        REALITY
                    </div>
                    <div class="bg-[#dbeafe] px-6 py-2.5 text-gray-800 flex-1 min-w-0 text-sm font-semibold flex items-center leading-snug break-words whitespace-pre-line max-h-24 sm:max-h-28 overflow-y-auto custom-scrollbar" :title="current?.grow_realitas || ''">
                        <div class="my-auto w-full" x-text="current?.grow_realitas || '-'"></div>
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-stretch rounded-xl overflow-hidden shadow-xs flex-1 min-h-[48px] sm:min-h-[52px]">
                    <div class="bg-[#8b5cf6] text-white font-extrabold text-xs sm:text-sm tracking-wider w-28 sm:w-32 py-2.5 px-3 flex items-center justify-center shrink-0 uppercase select-none">
                        OPTIONS
                    </div>
                    <div class="bg-[#ede9fe] px-6 py-2.5 text-gray-800 flex-1 min-w-0 text-sm font-semibold flex items-center leading-snug break-words whitespace-pre-line max-h-24 sm:max-h-28 overflow-y-auto custom-scrollbar" :title="current?.grow_options || ''">
                        <div class="my-auto w-full" x-text="current?.grow_options || '-'"></div>
                    </div>
                </div>

                <!-- Wrap Up -->
                <div class="flex items-stretch rounded-xl overflow-hidden shadow-xs flex-1 min-h-[48px] sm:min-h-[52px]">
                    <div class="bg-[#ef4444] text-white font-extrabold text-xs sm:text-sm tracking-wider w-28 sm:w-32 py-2.5 px-3 flex items-center justify-center shrink-0 uppercase select-none">
                        WRAP UP
                    </div>
                    <div class="bg-[#fee2e2] px-6 py-2.5 text-gray-800 flex-1 min-w-0 text-sm font-semibold flex items-center leading-snug break-words whitespace-pre-line max-h-24 sm:max-h-28 overflow-y-auto custom-scrollbar" :title="current?.grow_wrap_up || ''">
                        <div class="my-auto w-full" x-text="current?.grow_wrap_up || '-'"></div>
                    </div>
                </div>

                <!-- Insight -->
                <div class="flex items-stretch rounded-xl overflow-hidden shadow-xs flex-1 min-h-[48px] sm:min-h-[52px]">
                    <div class="bg-[#f97316] text-white font-extrabold text-xs sm:text-sm tracking-wider w-28 sm:w-32 py-2.5 px-3 flex items-center justify-center shrink-0 uppercase select-none">
                        INSIGHT
                    </div>
                    <div class="bg-[#ffedd5] px-6 py-2.5 text-gray-800 flex-1 min-w-0 text-sm font-semibold flex items-center leading-snug break-words whitespace-pre-line max-h-24 sm:max-h-28 overflow-y-auto custom-scrollbar" :title="current?.grow_insight || ''">
                        <div class="my-auto w-full" x-text="current?.grow_insight || '-'"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-1 flex justify-end shrink-0">
            <a :href="current?.url || '{{ route('user-mentoring.show-employee', $karyawan->id) }}'" 
               class="text-xs font-bold text-[#0ea5e9] hover:text-[#0284c7] inline-flex items-center gap-1.5 transition-colors group">
                <span>Mentoring Details</span>
                <i class="fas fa-chevron-right text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

@push('modals')
<!-- Modal Tambah Perjalanan Karier -->
<div id="modalTambahKarier" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop (Hitam Transparan tanpa blur) -->
    <div class="fixed inset-0 bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="document.getElementById('modalTambahKarier').classList.add('hidden')"></div>

    <!-- Modal panel -->
    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all z-10 flex flex-col max-h-[90vh]" style="width: 100%; max-width: 580px;">
        <!-- Header -->
        <div class="bg-gray-50/50 px-6 py-3.5 border-b border-gray-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900" id="modal-title">Add Career Journey</h3>
            </div>
            <button type="button" onclick="document.getElementById('modalTambahKarier').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('employee-journey.store') }}" method="POST" class="flex flex-col overflow-hidden">
            @csrf
            <input type="hidden" name="employee_id" value="{{ $karyawan->id }}">
            
            <div class="px-6 py-4 overflow-y-auto custom-scrollbar flex-1 max-h-[75vh]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <!-- Milestone Year -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Milestone Year <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                            </div>
                            <input type="number" name="tahun_karir" placeholder="e.g. 2024" required class="w-full pl-8 pr-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors">
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department / Division <span class="text-red-500">*</span></label>
                        <select name="department_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('department_id') border-red-500 @enderror">
                            <option value="" disabled selected>Select Department...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Position / Title <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-user-tie text-gray-400 text-xs"></i>
                            </div>
                            <input type="text" name="nama_posisi" placeholder="e.g. Operational Unit Head" required class="w-full pl-8 pr-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors">
                        </div>
                    </div>

                    <!-- Milestone Type -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Milestone Type <span class="text-red-500">*</span></label>
                        <select name="tipe_milestone" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('tipe_milestone') border-red-500 @enderror">
                            <option value="aktif">Initial Join</option>
                            <option value="tetap">Stay in Role</option>
                            <option value="promosi">Promotion</option>
                            <option value="rotasi">Rotation</option>
                            <option value="demosi">Demotion</option>
                        </select>
                    </div>

                    <!-- Achievement Status -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Achievement Status <span class="text-red-500">*</span></label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <!-- Radio 1: History / Completed -->
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pencapaian" value="riwayat/selesai" class="peer sr-only">
                                <div class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-gray-600 bg-gray-50 flex items-center justify-center gap-1.5 text-xs font-medium transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-gray-100">
                                    <i class="fas fa-check-circle text-[10px]"></i> History (Completed)
                                </div>
                            </label>
                            <!-- Radio 2: Active / Current -->
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pencapaian" value="aktif/tercapai" class="peer sr-only" checked>
                                <div class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-gray-600 bg-gray-50 flex items-center justify-center gap-1.5 text-xs font-medium transition-all peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 hover:bg-gray-100">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i> Active (Current)
                                </div>
                            </label>
                            <!-- Radio 3: Projection / Target -->
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pencapaian" value="proyeksi/target" class="peer sr-only">
                                <div class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-gray-600 bg-gray-50 flex items-center justify-center gap-1.5 text-xs font-medium transition-all peer-checked:bg-sky-50 peer-checked:border-sky-500 peer-checked:text-sky-700 hover:bg-gray-100">
                                    <div class="w-2.5 h-2.5 rounded-full border-2 border-gray-300 peer-checked:border-sky-500 flex items-center justify-center">
                                        <div class="w-1 h-1 bg-transparent rounded-full peer-checked:bg-sky-500"></div>
                                    </div> 
                                    Projection (Target)
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Position Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Position Notes / Details</label>
                        <textarea name="catatan_detail" rows="2" placeholder="Brief description of the role..." class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors resize-none"></textarea>
                    </div>

                    <!-- Award -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Operational Excellence Award</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-graduation-cap text-gray-400 text-xs"></i>
                            </div>
                            <input type="text" name="operational_award" placeholder="e.g. ISO 9001 Auditor" class="w-full pl-8 pr-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-3.5 rounded-b-2xl border-t border-gray-100 flex justify-end gap-2.5 shrink-0">
                <button type="button" onclick="document.getElementById('modalTambahKarier').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] shadow-sm flex items-center gap-1.5 transition-colors">
                    <i class="fas fa-save"></i> Save Career
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('modals')
@foreach($perjalananKarirs as $pk)
<!-- Modal Edit Perjalanan Karier -->
<div id="modalEditKarier_{{ $pk->id }}" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="document.getElementById('modalEditKarier_{{ $pk->id }}').classList.add('hidden')"></div>
    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all z-10 flex flex-col max-h-[90vh]" style="width: 100%; max-width: 580px;">
        <div class="bg-gray-50/50 px-6 py-3.5 border-b border-gray-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
                    <i class="fas fa-pen text-sm"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">Edit Career Journey</h3>
            </div>
            <button type="button" onclick="document.getElementById('modalEditKarier_{{ $pk->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1.5 rounded-lg transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('employee-journey.update', $pk->id) }}" method="POST" class="flex flex-col overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 overflow-y-auto custom-scrollbar flex-1 max-h-[75vh]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <!-- Milestone Year -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Milestone Year <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                            </div>
                            <input type="number" name="tahun_karir" value="{{ $pk->tahun_karir }}" required class="w-full pl-8 pr-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors">
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department / Division <span class="text-red-500">*</span></label>
                        <select name="department_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('department_id') border-red-500 @enderror">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $pk->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Position / Title <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-user-tie text-gray-400 text-xs"></i>
                            </div>
                            <input type="text" name="nama_posisi" value="{{ $pk->nama_posisi }}" required class="w-full pl-8 pr-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors">
                        </div>
                    </div>

                    <!-- Milestone Type -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Milestone Type <span class="text-red-500">*</span></label>
                        <select name="tipe_milestone" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border @error('tipe_milestone') border-red-500 @enderror">
                            <option value="aktif" {{ $pk->tipe_milestone == 'aktif' ? 'selected' : '' }}>Initial Join</option>
                            <option value="tetap" {{ $pk->tipe_milestone == 'tetap' ? 'selected' : '' }}>Stay in Role</option>
                            <option value="promosi" {{ $pk->tipe_milestone == 'promosi' ? 'selected' : '' }}>Promotion</option>
                            <option value="rotasi" {{ $pk->tipe_milestone == 'rotasi' ? 'selected' : '' }}>Rotation</option>
                            <option value="demosi" {{ $pk->tipe_milestone == 'demosi' ? 'selected' : '' }}>Demotion</option>
                        </select>
                    </div>

                    <!-- Achievement Status -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Achievement Status <span class="text-red-500">*</span></label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pencapaian" value="riwayat/selesai" class="peer sr-only" {{ $pk->status_pencapaian == 'riwayat/selesai' ? 'checked' : '' }}>
                                <div class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-gray-600 bg-gray-50 flex items-center justify-center gap-1.5 text-xs font-medium transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-gray-100">
                                    <i class="fas fa-check-circle text-[10px]"></i> History (Completed)
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pencapaian" value="aktif/tercapai" class="peer sr-only" {{ $pk->status_pencapaian == 'aktif/tercapai' ? 'checked' : '' }}>
                                <div class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-gray-600 bg-gray-50 flex items-center justify-center gap-1.5 text-xs font-medium transition-all peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 hover:bg-gray-100">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i> Active (Current)
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status_pencapaian" value="proyeksi/target" class="peer sr-only" {{ $pk->status_pencapaian == 'proyeksi/target' ? 'checked' : '' }}>
                                <div class="w-full px-2 py-1.5 rounded-lg border border-gray-200 text-gray-600 bg-gray-50 flex items-center justify-center gap-1.5 text-xs font-medium transition-all peer-checked:bg-sky-50 peer-checked:border-sky-500 peer-checked:text-sky-700 hover:bg-gray-100">
                                    <div class="w-2.5 h-2.5 rounded-full border-2 border-gray-300 peer-checked:border-sky-500 flex items-center justify-center">
                                        <div class="w-1 h-1 bg-transparent rounded-full peer-checked:bg-sky-500"></div>
                                    </div> 
                                    Projection (Target)
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Position Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Position Notes / Details</label>
                        <textarea name="catatan_detail" rows="2" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors resize-none">{{ $pk->catatan_detail }}</textarea>
                    </div>

                    <!-- Award -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Operational Excellence Award</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-graduation-cap text-gray-400 text-xs"></i>
                            </div>
                            <input type="text" name="operational_award" value="{{ $pk->operational_award }}" class="w-full pl-8 pr-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-sky-100 focus:border-[#0ea5e9] transition-colors">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50/80 px-6 py-3.5 rounded-b-2xl border-t border-gray-100 flex justify-end gap-2.5 shrink-0">
                <button type="button" onclick="document.getElementById('modalEditKarier_{{ $pk->id }}').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] shadow-sm flex items-center gap-1.5 transition-colors"><i class="fas fa-save"></i> Update Career</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modals for Manage Data -->
<!-- Modal Edit SCARF Profile -->
<div id="editScarfModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) this.classList.add('hidden')">
    <!-- Mengubah max-w-md menjadi max-w-xs agar lebih kecil dan pas untuk input angka -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xs mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <!-- Header diperkecil padding dan font-nya -->
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-base font-bold text-gray-900">Manage SCARF Profile</h3>
            <button type="button" onclick="document.getElementById('editScarfModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-md"></i>
            </button>
        </div>
        
        <form action="{{ route('employee-scarf.update', $karyawan->id) }}" method="POST" class="p-5">
            @csrf
            
            <!-- Jarak antar elemen dirapatkan menjadi space-y-3 -->
            <div class="space-y-3 mb-5">
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Status (0-100%)</label>
                    <input type="number" step="any" name="status" min="0" max="100" value="{{ $karyawan->scarf->status ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Certainty (0-100%)</label>
                    <input type="number" step="any" name="certainty" min="0" max="100" value="{{ $karyawan->scarf->certainty ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Autonomy (0-100%)</label>
                    <input type="number" step="any" name="autonomy" min="0" max="100" value="{{ $karyawan->scarf->autonomy ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Relatedness (0-100%)</label>
                    <input type="number" step="any" name="relatedness" min="0" max="100" value="{{ $karyawan->scarf->relatedness ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Fairness (0-100%)</label>
                    <input type="number" step="any" name="fairness" min="0" max="100" value="{{ $karyawan->scarf->fairness ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>

            <!-- Footer tombol dibuat lebih ringkas -->
            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editScarfModal').classList.add('hidden')" class="px-4 py-1.5 text-[13px] font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-1.5 text-[13px] font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Via Character -->
<div id="addViaCharacterModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Add Via Character</h3>
            <button type="button" onclick="document.getElementById('addViaCharacterModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('employee-via-character.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Overuse Strength</label>
                    <input type="text" name="overuse_strength" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Underuse Strength</label>
                    <input type="text" name="underuse_strength" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addViaCharacterModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Via Character -->
@foreach($karyawan->viaCharacters ?? [] as $via)
<div id="editViaCharacterModal{{ $via->id }}" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Edit Via Character</h3>
            <button type="button" onclick="document.getElementById('editViaCharacterModal{{ $via->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('employee-via-character.update', $via->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Overuse Strength</label>
                    <input type="text" name="overuse_strength" value="{{ $via->overuse_strength }}" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Underuse Strength</label>
                    <input type="text" name="underuse_strength" value="{{ $via->underuse_strength }}" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editViaCharacterModal{{ $via->id }}').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal Tambah Strength & Weakness -->
<div id="addStrengthModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Add Strength & Weakness</h3>
            <button type="button" onclick="document.getElementById('addStrengthModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('employee-strength.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Strength</label>
                    <input type="text" name="strength" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weakness</label>
                    <input type="text" name="weakness" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addStrengthModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Strength & Weakness -->
@foreach($karyawan->strengths ?? [] as $st)
<div id="editStrengthModal{{ $st->id }}" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Edit Strength & Weakness</h3>
            <button type="button" onclick="document.getElementById('editStrengthModal{{ $st->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('employee-strength.update', $st->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Strength</label>
                    <input type="text" name="strength" value="{{ $st->strength }}" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weakness</label>
                    <input type="text" name="weakness" value="{{ $st->weakness }}" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editStrengthModal{{ $st->id }}').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endpush

@section('scripts')
<script>
    (function() {
        const initChart = function() {
            const radarCanvas = document.getElementById('radarChart');
            if(!radarCanvas) return;
            // Prevent re-initialization if Chart already exists
            if (radarCanvas.chartInstance) {
                radarCanvas.chartInstance.destroy();
            }
            if (typeof Chart === 'undefined') {
                setTimeout(initChart, 100);
                return;
            }
            
            const ctx = radarCanvas.getContext('2d');
            
            const jabatan = @json(strtolower(trim($karyawan->position?->nama_jabatan ?? '')));
            
            // Map all IDP history with their penilaian data
            const idpHistory = {
                @foreach($karyawan->idps as $idp)
                "{{ $idp->id }}": {
                    id: {{ $idp->id }},
                    tahun: @json($idp->tahun ?? '-'),
                    periode: @json($idp->periode ? 'Periode ' . $idp->periode : ''),
                    status: @json(strtolower($idp->status_idp ?? 'pending')),
                    statusLabel: @json(ucwords(str_replace('_', ' ', $idp->status_idp ?? 'Pending'))),
                    mentor: @json($idp->mentor ?? '-'),
                    detailUrl: @json(route('user-idp.show', $idp->id)),
                    hasPenilaian: {{ $hasScore($idp) ? 'true' : 'false' }},
                    penilaian: @json($idp->penilaian)
                },
                @endforeach
            };

            function parseScore(val) {
                if (val === null || val === undefined || val === '') return 0;
                let parsed = parseFloat(val);
                return isNaN(parsed) ? 0 : parsed;
            }

            // Define competency models
            const competencyModels = {
                head: {
                    labels: ['Managing Change', 'Empowerment', 'Fostering Teamwork', 'Analysis & Decision Making', 'Planning & Controlling', 'Integrity & Ethics', 'Excellence', 'Compassion', 'Humility'],
                    dbKeys: ['head_managing_change', 'head_empowerment', 'head_fostering_teamwork', 'head_analysis_decision', 'head_planning_controlling', 'head_integrity_ethics', 'head_excellence', 'head_compassion', 'head_humility'],
                    target: Array(9).fill(4)
                },
                staff: {
                    labels: ['Leads Courageously', 'Coach & Develop Others', 'Teamwork', 'Trouble Shooting', 'Customer Satisfaction', 'Cross Functional Capability', 'Quality Focus', 'Continuous Improvement', 'Planning Monitoring', 'Personal Integrity', 'Drive For Result', 'Learning'],
                    dbKeys: ['staff_leads_courageously', 'staff_coach_develop', 'staff_teamwork', 'staff_trouble_shooting', 'staff_customer_satisfaction', 'staff_cross_functional', 'staff_quality_focus', 'staff_continuous_improvement', 'staff_planning_monitoring', 'staff_personal_integrity', 'staff_drive_for_result', 'staff_learning'],
                    target: Array(12).fill(4)
                },
                unit: {
                    labels: ['Directiveness', 'Monitoring', 'Teamwork', 'Quality & Customer Focus', 'Continuous Improvement', 'Personal Integrity'],
                    dbKeys: ['unit_directiveness', 'unit_monitoring', 'unit_teamwork', 'unit_quality_customer', 'unit_continuous_improvement', 'unit_personal_integrity'],
                    target: Array(6).fill(4)
                }
            };

            function resolveModel(penilaianObj) {
                // If penilaian specifically has filled scores in a category, use that category
                if (penilaianObj) {
                    if (competencyModels.head.dbKeys.some(k => parseScore(penilaianObj[k]) > 0)) return 'head';
                    if (competencyModels.staff.dbKeys.some(k => parseScore(penilaianObj[k]) > 0)) return 'staff';
                    if (competencyModels.unit.dbKeys.some(k => parseScore(penilaianObj[k]) > 0)) return 'unit';
                }
                // Fallback based on employee position
                if (jabatan === 'department head' || jabatan === 'section head' || jabatan === 'group head' || (jabatan.includes('head') && !jabatan.includes('unit'))) {
                    return 'head';
                } else if (jabatan.includes('staff')) {
                    return 'staff';
                } else {
                    return 'unit';
                }
            }

            function calculateScores(penilaianObj, dbKeys) {
                let scores = [];
                let total = 0;
                let count = 0;
                dbKeys.forEach(key => { 
                    const score = penilaianObj ? parseScore(penilaianObj[key]) : 0;
                    scores.push(score);
                    if (score > 0) {
                        total += score;
                        count++;
                    }
                });
                return {
                    scores: scores,
                    avg: count > 0 ? (total / count).toFixed(1) : '0.0',
                    hasScore: count > 0
                };
            }

            function getMultilineLabel(key) {
                if (key === 'Analysis & Decision Making') return ['Analysis &', 'Decision Making'];
                if (key === 'Planning & Controlling') return ['Planning &', 'Controlling'];
                if (key === 'Integrity & Ethics') return ['Integrity &', 'Ethics'];
                if (key === 'Managing Change') return ['Managing', 'Change'];
                if (key === 'Fostering Teamwork') return ['Fostering', 'Teamwork'];
                if (key === 'Leads Courageously') return ['Leads', 'Courageously'];
                if (key === 'Coach & Develop Others') return ['Coach &', 'Develop Others'];
                if (key === 'Trouble Shooting') return ['Trouble', 'Shooting'];
                if (key === 'Customer Satisfaction') return ['Customer', 'Satisfaction'];
                if (key === 'Cross Functional Capability') return ['Cross Functional', 'Capability'];
                if (key === 'Quality Focus') return ['Quality', 'Focus'];
                if (key === 'Continuous Improvement') return ['Continuous', 'Improvement'];
                if (key === 'Planning Monitoring') return ['Planning', 'Monitoring'];
                if (key === 'Personal Integrity') return ['Personal', 'Integrity'];
                if (key === 'Drive For Result') return ['Drive For', 'Result'];
                if (key === 'Quality & Customer Focus') return ['Quality &', 'Customer Focus'];
                return key;
            }

            const initialIdpId = "{{ $activeIdp?->id }}";
            const initialIdp = idpHistory[initialIdpId] || null;
            const initialModelKey = resolveModel(initialIdp ? initialIdp.penilaian : null);
            const initialModel = competencyModels[initialModelKey];
            const initialResult = calculateScores(initialIdp ? initialIdp.penilaian : null, initialModel.dbKeys);

            // Update average score in UI on initial load
            const initialAvgEl = document.getElementById('idpScoreAvg');
            if (initialAvgEl) {
                initialAvgEl.textContent = parseFloat(initialResult.avg) > 0 ? `Rata-rata: ${initialResult.avg}/5` : '';
            }

            radarCanvas.chartInstance = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: initialModel.labels.map(getMultilineLabel),
                    datasets: [{
                        label: 'Current Score',
                        data: initialResult.scores,
                        fill: true,
                        backgroundColor: 'rgba(14, 165, 233, 0.25)', // Sky blue with opacity
                        borderColor: 'rgb(14, 165, 233)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(14, 165, 233)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(14, 165, 233)'
                    }, {
                        label: 'Target (4.0)',
                        data: initialModel.target,
                        fill: true,
                        backgroundColor: 'rgba(16, 185, 129, 0.08)', // Emerald
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1.5,
                        borderDash: [5, 5],
                        pointRadius: 3,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(16, 185, 129)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: { line: { tension: 0.1 } },
                    scales: {
                        r: {
                            min: 0,
                            max: 5,
                            suggestedMin: 0,
                            suggestedMax: 5,
                            angleLines: { display: true, color: 'rgba(0, 0, 0, 0.06)' },
                            grid: { color: 'rgba(0, 0, 0, 0.06)' },
                            ticks: { display: false, stepSize: 1 },
                            pointLabels: {
                                font: { size: 9, family: "'Inter', sans-serif", weight: '600' },
                                color: '#475569'
                            }
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { size: 10, family: "'Inter', sans-serif" } } },
                        tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.9)', titleFont: { size: 11 }, bodyFont: { size: 11 }, padding: 10, cornerRadius: 8 }
                    }
                }
            });

            // Function to dynamically switch active IDP period and update chart + UI
            
        window.exportPdfWithCurrentFilters = function(event, baseUrl) {
            event.preventDefault();
            const params = new URLSearchParams();
            if (window.selectedAssessmentId) params.set('assessment_id', window.selectedAssessmentId);
            if (window.selectedIdpId) params.set('idp_id', window.selectedIdpId);
            if (window.selectedMentoringId) params.set('mentoring_id', window.selectedMentoringId);
            const finalUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
            window.open(finalUrl, '_blank');
        };

            window.switchIdpPeriod = function(selectedId) {
                window.selectedIdpId = selectedId;
                const idp = idpHistory[selectedId];
                if (!idp) return;

                const modelKey = resolveModel(idp.penilaian);
                const model = competencyModels[modelKey];
                const res = calculateScores(idp.penilaian, model.dbKeys);
                
                // Update radar chart dataset & labels smoothly
                if (radarCanvas && radarCanvas.chartInstance) {
                    radarCanvas.chartInstance.data.labels = model.labels.map(getMultilineLabel);
                    radarCanvas.chartInstance.data.datasets[0].data = res.scores;
                    radarCanvas.chartInstance.data.datasets[1].data = model.target;
                    radarCanvas.chartInstance.update();
                }

                // Update Mentor text
                const mentorEl = document.getElementById('idpMentorText');
                if (mentorEl) mentorEl.textContent = idp.mentor || '-';

                // Update Average Score text
                const avgScoreEl = document.getElementById('idpScoreAvg');
                if (avgScoreEl) {
                    avgScoreEl.textContent = parseFloat(res.avg) > 0 ? `Rata-rata: ${res.avg}/5` : '';
                }

                // Update Detail Link
                const detailLink = document.getElementById('idpDetailLink');
                if (detailLink) {
                    detailLink.href = idp.detailUrl;
                }

                // Update Status Badge
                const badgeContainer = document.getElementById('idpStatusBadgeContainer');
                if (badgeContainer) {
                    if (idp.status === 'completed') {
                        badgeContainer.innerHTML = `<span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completed
                        </span>`;
                    } else if (idp.status === 'in_progress') {
                        badgeContainer.innerHTML = `<span class="bg-orange-50 border border-orange-200 text-orange-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span> In Progress
                        </span>`;
                    } else {
                        badgeContainer.innerHTML = `<span class="bg-sky-50 border border-sky-200 text-sky-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm flex items-center gap-1.5 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> ${idp.statusLabel.toUpperCase()}
                        </span>`;
                    }
                }

                // Update Notice if no penilaian
                const alertEl = document.getElementById('idpEmptyAlert');
                if (alertEl) {
                    if (!res.hasScore) {
                        alertEl.classList.remove('hidden');
                    } else {
                        alertEl.classList.add('hidden');
                    }
                }
            };

            const idpSelector = document.getElementById('idpSelector');
            if (idpSelector) {
                idpSelector.addEventListener('change', function() {
                    window.switchIdpPeriod(this.value);
                });
            }
        };

        // Initialize immediately if script is executed normally
        initChart();
        
        // Lock background body scroll when any modal is opened
        const toggleModalScrollLock = function() {
            const hasOpenModal = document.querySelector('#modalTambahKarier:not(.hidden), [id^="modalEditKarier_"]:not(.hidden), #editScarfModal:not(.hidden), #addViaCharacterModal:not(.hidden), [id^="editViaCharacterModal"]:not(.hidden), #addStrengthModal:not(.hidden), [id^="editStrengthModal"]:not(.hidden)');
            if (hasOpenModal) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        };

        const modalObserver = new MutationObserver(toggleModalScrollLock);
        document.querySelectorAll('#modalTambahKarier, [id^="modalEditKarier_"], #editScarfModal, #addViaCharacterModal, [id^="editViaCharacterModal"], #addStrengthModal, [id^="editStrengthModal"]').forEach(function(el) {
            modalObserver.observe(el, { attributes: true, attributeFilter: ['class'] });
        });
    })();
</script>
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
</style>
@endsection

@endsection

