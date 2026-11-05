@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="{{ route('user-idp.index') }}" class="hover:text-gray-700 transition-colors">IDP</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            @if(isset($Idp->id_karyawan))
                <a href="{{ route('user-idp.show-employee', $Idp->id_karyawan) }}" class="hover:text-gray-700 transition-colors">{{ $Idp->karyawan->nama ?? 'Employee' }}</a>
                <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            @endif
            <span class="text-gray-900 font-medium">Detail Individual Development Plan</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ isset($Idp->id_karyawan) ? route('user-idp.show-employee', $Idp->id_karyawan) : route('user-idp.index') }}" class="bg-[#0ea5e9] hover:bg-[#0284c7] shadow-sm hover:shadow-md rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-white">
            <i class="fas fa-arrow-left"></i> Back
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
        <span>There were errors saving the data:</span>
    </div>
    <ul class="list-disc pl-8 text-xs space-y-0.5 text-rose-700">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Employee Profile Header -->
@include('components.employee-profile-header', ['employee' => $Idp->karyawan])

<!-- Main Idp Grid layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pb-12">
    
    <!-- Left Column (Session Details) -->
    <div class="lg:col-span-4 flex flex-col gap-6">
        
        <!-- IDP Information -->
        <div class="bg-gradient-to-br from-emerald-50/80 to-teal-50/80 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-emerald-100 overflow-hidden relative transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-emerald-200">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-500"></div>
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-info-circle text-teal-500 text-base"></i> IDP Information
                </h3>
                <div class="flex items-center gap-2">
                    @php
                        $statusClasses = [
                            'in_progress' => 'bg-amber-50 text-amber-600 border-amber-200 shadow-[0_0_10px_rgba(251,191,36,0.2)]',
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-200 shadow-[0_0_10px_rgba(251,191,36,0.2)]',
                            'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-200 shadow-[0_0_10px_rgba(52,211,153,0.2)]',
                        ];
                        $sClass = $statusClasses[$Idp->status_idp] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                    @endphp
                   
                    
                    @canany(['edit_idp', 'tambah_idp'])
                    @if($Idp->id)
                        <button type="button" x-data @click="$dispatch('open-idp-info-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Edit IDP">
                            <i class="fas fa-pen"></i>
                        </button>
                    @else
                        <button type="button" x-data @click="$dispatch('open-idp-info-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Add IDP">
                            <i class="fas fa-plus"></i>
                        </button>
                    @endif
                    @endcanany
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    <!-- Tahun -->
                    <div class="group flex items-center gap-4 p-4 rounded-xl border border-white/50 bg-white/60 backdrop-blur-sm hover:bg-white/80 hover:border-teal-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 border border-slate-200 group-hover:bg-teal-50 group-hover:border-teal-200 group-hover:text-teal-500 transition-colors shadow-sm">
                            <i class="far fa-calendar-alt text-slate-400 group-hover:text-teal-500 transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Year</p>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-teal-700 transition-colors">{{ $Idp->tahun ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Periode -->
                    <div class="group flex items-center gap-4 p-4 rounded-xl border border-white/50 bg-white/60 backdrop-blur-sm hover:bg-white/80 hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 border border-indigo-100 group-hover:bg-indigo-100 group-hover:border-indigo-200 group-hover:text-indigo-600 transition-colors shadow-sm">
                            <i class="fas fa-layer-group text-indigo-400 group-hover:text-indigo-600 transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Period</p>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-indigo-700 transition-colors">{{ $Idp->periode ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="group flex items-center gap-4 p-4 rounded-xl border border-white/50 bg-white/60 backdrop-blur-sm hover:bg-white/80 hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:bg-emerald-100 group-hover:border-emerald-200 group-hover:text-emerald-600 transition-colors shadow-sm">
                            <i class="fas fa-flag text-emerald-400 group-hover:text-emerald-600 transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Status</p>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-emerald-700 transition-colors">{{ $Idp->status_idp ? ucwords(str_replace('_', ' ', $Idp->status_idp)) : '-' }}</p>
                        </div>
                    </div>

                    <!-- Mentor -->
                    <div class="group flex items-center gap-4 p-4 rounded-xl border border-white/50 bg-white/60 backdrop-blur-sm hover:bg-white/80 hover:border-cyan-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 border border-cyan-100 group-hover:bg-cyan-100 group-hover:border-cyan-200 group-hover:text-cyan-600 transition-colors shadow-sm">
                            <i class="far fa-user text-cyan-500 group-hover:text-cyan-600 transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Mentor</p>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-cyan-700 transition-colors">{{ $Idp->mentor ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Development Area -->
                    <div class="group flex items-center gap-4 p-4 rounded-xl border border-white/50 bg-white/60 backdrop-blur-sm hover:bg-white/80 hover:border-amber-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 border border-amber-100 group-hover:bg-amber-100 group-hover:border-amber-200 group-hover:text-amber-600 transition-colors shadow-sm">
                            <i class="fas fa-bullseye text-amber-500 group-hover:text-amber-600 transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Development Area</p>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-amber-700 transition-colors">{{ $Idp->development ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Review -->
        <div class="bg-gradient-to-br from-sky-50/80 to-blue-50/80 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-sky-100 overflow-hidden relative transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-sky-200">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500"></div>
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="far fa-calendar-check text-sky-500 text-base"></i> Review Schedule
                </h3>
                <div>
                    @can('edit_idp')
                    @if($Idp->id)
                        <button type="button" x-data @click="$dispatch('open-jadwal-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Edit Schedule">
                            <i class="fas fa-pen"></i>
                        </button>
                    @else
                        <button type="button" x-data @click="$dispatch('open-jadwal-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Add Schedule">
                            <i class="fas fa-plus"></i>
                        </button>
                    @endif
                    @endcan
                </div>
            </div>
            
            <div class="p-6">
                <div class="relative ml-4 space-y-6 before:absolute before:inset-0 before:ml-[-13px] before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-sky-500 before:via-blue-300 before:to-gray-200">
                    
                    <!-- Target -->
                    <div class="relative group">
                        <div class="absolute -left-[1.85rem] top-1.5 w-6 h-6 rounded-full bg-sky-500 border-4 border-white shadow-[0_0_10px_rgba(14,165,233,0.4)] flex items-center justify-center z-10 transition-transform duration-300 group-hover:scale-110">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-white/50 shadow-sm transition-all duration-300 hover:shadow-md hover:bg-white/90 hover:border-sky-200 hover:-translate-y-0.5 ml-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-sm font-bold text-sky-900">Target Setting</h4>
                            </div>
                            <p class="text-xs text-sky-600/80 font-medium flex items-center gap-1.5"><i class="far fa-clock text-sky-400"></i> {{ $Idp->target ? \Carbon\Carbon::parse($Idp->target)->format('d M Y') : 'Not set yet' }}</p>
                        </div>
                    </div>
                    
                    <!-- Monitoring -->
                    <div class="relative group">
                        <div class="absolute -left-[1.85rem] top-1.5 w-6 h-6 rounded-full bg-blue-400 border-4 border-white shadow-[0_0_10px_rgba(96,165,250,0.4)] flex items-center justify-center z-10 transition-transform duration-300 group-hover:scale-110">
                            <i class="fas fa-sync-alt text-white text-[10px]"></i>
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-white/50 shadow-sm transition-all duration-300 hover:shadow-md hover:bg-white/90 hover:border-blue-200 hover:-translate-y-0.5 ml-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-sm font-bold text-sky-900">Monitoring</h4>
                            </div>
                            <p class="text-xs text-sky-600/80 font-medium flex items-center gap-1.5"><i class="far fa-clock text-sky-400"></i> {{ $Idp->monitoring ? \Carbon\Carbon::parse($Idp->monitoring)->format('d M Y') : 'Not set yet' }}</p>
                        </div>
                    </div>
                    
                    <!-- Final -->
                    <div class="relative group">
                        <div class="absolute -left-[1.85rem] top-1.5 w-6 h-6 rounded-full bg-white border-4 border-sky-100 shadow-sm flex items-center justify-center z-10 transition-all duration-300 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:shadow-[0_0_10px_rgba(99,102,241,0.4)]">
                            <i class="fas fa-flag-checkered text-sky-300 text-[10px] group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl border border-white/50 shadow-sm transition-all duration-300 hover:shadow-md hover:bg-white/90 hover:border-indigo-200 hover:-translate-y-0.5 ml-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-sm font-bold text-sky-900 group-hover:text-indigo-700 transition-colors">Final Review</h4>
                            </div>
                            <p class="text-xs text-sky-600/80 font-medium flex items-center gap-1.5 group-hover:text-sky-700 transition-colors"><i class="far fa-clock text-sky-400 group-hover:text-indigo-300"></i> {{ $Idp->final ? \Carbon\Carbon::parse($Idp->final)->format('d M Y') : 'Not set yet' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column (Competency Assessment with Tabs - Option 2) -->
    <div class="lg:col-span-8 flex flex-col gap-6 w-full" x-data="{ 
        activeTab: {{ $chartGroups->first()?->id ?? 0 }},
        switchTab(groupId) {
            this.activeTab = groupId;
            this.$nextTick(() => {
                setTimeout(() => {
                    if (typeof window.renderSpiderChart === 'function') {
                        window.renderSpiderChart(groupId);
                    }
                }, 50);
            });
        }
    }">
        <!-- Tab Navigation Buttons -->
        @if($chartGroups->count() > 1)
        <div class="bg-slate-100 p-1.5 rounded-2xl border border-gray-200/80 shadow-inner flex items-center gap-2 overflow-x-auto no-scrollbar">
            @foreach($chartGroups as $grp)
            <button type="button"
                @click="switchTab({{ $grp->id }})"
                :class="activeTab === {{ $grp->id }} 
                    ? 'bg-[#0ea5e9] text-white shadow-md font-bold' 
                    : 'bg-transparent text-gray-600 hover:text-gray-900 hover:bg-white/80 font-medium'"
                class="px-4 py-2.5 rounded-xl text-xs transition-all duration-200 flex items-center gap-2 shrink-0">
                <i class="fas fa-bullseye text-sm" :class="activeTab === {{ $grp->id }} ? 'text-white' : 'text-sky-500'"></i>
                <span>{{ $grp->title }}</span>
                <span :class="activeTab === {{ $grp->id }} ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-600'" class="px-2 py-0.5 rounded-full text-[10px] font-bold">
                    {{ $grp->competencies->count() }}
                </span>
            </button>
            @endforeach
        </div>
        @endif

        <!-- Tab Content Cards -->
        @forelse($chartGroups as $grp)
        <div x-show="activeTab === {{ $grp->id }}" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-gradient-to-br from-slate-50 to-gray-100/80 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-200 overflow-hidden flex flex-col relative transition-all duration-300 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-500"></div>
            
            <!-- Header -->
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 border-b border-gray-100">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base flex items-center gap-2">
                            <i class="fas fa-bullseye text-[#0ea5e9] text-base shrink-0"></i> 
                            <span>{{ $grp->title }}</span>
                        </h3>
                        @if($grp->target_position)
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200 shrink-0">
                                Posisi: {{ ucwords($grp->target_position) }}
                            </span>
                        @endif
                    </div>
                    @if($grp->description)
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $grp->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Chart Area -->
            <div class="bg-white/60 backdrop-blur-sm mx-0 sm:mx-6 my-6 rounded-2xl p-4 sm:p-8 relative border border-white/50 shadow-[0_2px_15px_rgba(0,0,0,0.02)] w-full block">
                <div class="relative w-full min-w-[280px] sm:min-w-[400px] h-[400px] md:h-[480px] mx-auto overflow-hidden">
                    <canvas id="competencyChart_{{ $grp->id }}" style="width: 100%; height: 100%;"></canvas>
                </div>
                
                <div class="mt-6 md:mt-8 text-center w-full">
                    <span class="inline-flex px-4 py-1.5 rounded-full text-[10px] font-bold border border-gray-200 text-gray-500 bg-gray-50 shadow-sm uppercase tracking-wider text-center max-w-full overflow-hidden text-ellipsis whitespace-nowrap">
                        Proficiency Scale 1.0 (Novice) to 5.0 (Role Model)
                    </span>
                </div>
            </div>

            <!-- Competency Breakdown -->
            <div class="px-6 pb-6 pt-2">
                <div class="flex justify-between items-center mb-5">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-list-ul"></i> Competency Breakdown ({{ $grp->title }})
                    </h4>
                    @php
                        $compCount = $grp->competencies->count();
                        $totalScore = $grp->competencies->sum(fn($c) => (float)($competencyScores[$c->id] ?? 0));
                        $avgScore = $compCount > 0 ? $totalScore / $compCount : 0;
                    @endphp
                    <span class="text-xs font-bold px-3 py-1 bg-teal-50 text-teal-700 rounded-full border border-teal-100" id="avgScore_{{ $grp->id }}">
                        Avg: {{ number_format($avgScore, 1) }} / 5.0
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="competencyCards_{{ $grp->id }}">
                    @foreach($grp->competencies as $compIndex => $comp)
                    @php
                        $score = (float) ($competencyScores[$comp->id] ?? 0);
                        $scoreColor = $score >= 4 ? 'text-teal-600' : ($score >= 3 ? 'text-indigo-600' : 'text-amber-600');
                        $scoreBg = $score >= 4 ? 'bg-teal-50 border-teal-100' : ($score >= 3 ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100');
                    @endphp
                    <div class="group flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:bg-slate-50 transition-all duration-300 hover:shadow-md hover:border-gray-200 hover:-translate-y-0.5">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-8 h-8 rounded-full shrink-0 bg-slate-50 flex items-center justify-center border border-gray-200 text-xs font-bold text-gray-400 group-hover:bg-white group-hover:text-teal-500 transition-colors">
                                {{ $compIndex + 1 }}
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition-colors line-clamp-2 leading-tight" title="{{ $comp->name }}">{{ $comp->name }}</h5>
                                @if($comp->description)
                                    <p class="text-[10px] text-gray-400 line-clamp-1 mt-0.5">{{ $comp->description }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="shrink-0 px-2.5 py-1 rounded-lg border {{ $scoreBg }} {{ $scoreColor }} text-xs font-bold shadow-sm">
                            {{ number_format($score, 1) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-10 text-center border border-gray-200 shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 text-[#0ea5e9] flex items-center justify-center mx-auto mb-3 border border-sky-100">
                <i class="fas fa-bullseye text-2xl"></i>
            </div>
            <h4 class="font-bold text-gray-800 text-base mb-1">Belum Ada Spider Chart untuk Posisi Ini</h4>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                Posisi Anda saat ini (<span class="font-bold text-gray-700">{{ $Idp->karyawan->position?->nama_jabatan ?? 'Belum ditentukan' }}</span>) belum memiliki penilaian Spider Chart aktif.
            </p>
        </div>
        @endforelse

    </div>
</div>

<script>
    const chartGroupsData = @json($chartGroups);
    const competencyScoresMap = @json($competencyScores);
    window.idpChartInstances = window.idpChartInstances || {};

    function getMultilineLabel(key) {
        if (!key) return '';
        if (key.length > 18 && key.includes(' ')) {
            const words = key.split(' ');
            const mid = Math.ceil(words.length / 2);
            return [words.slice(0, mid).join(' '), words.slice(mid).join(' ')];
        }
        return key;
    }

    function renderSpiderChart(groupId) {
        const group = chartGroupsData.find(g => g.id === groupId);
        if (!group) return;

        const ctxEl = document.getElementById('competencyChart_' + groupId);
        if (!ctxEl) return;

        if (window.idpChartInstances[groupId]) {
            window.idpChartInstances[groupId].destroy();
        }

        const labels = group.competencies.map(c => getMultilineLabel(c.name));
        const dataScores = group.competencies.map(c => parseFloat(competencyScoresMap[c.id] || 0));

        const ctx = ctxEl.getContext('2d');
        window.idpChartInstances[groupId] = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target (5.0)',
                        data: Array(labels.length).fill(5),
                        backgroundColor: 'transparent',
                        borderColor: '#cbd5e1',
                        borderWidth: 1.5,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Skor Aktual',
                        data: dataScores,
                        backgroundColor: 'rgba(13, 148, 136, 0.25)',
                        borderColor: '#0d9488',
                        pointBackgroundColor: '#0d9488',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#0d9488',
                        borderWidth: 2.5,
                        pointRadius: 5,
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 2.5,
                        tension: 0,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20,
                        left: 20,
                        right: 20
                    }
                },
                scales: {
                    r: {
                        min: 0,
                        max: 5,
                        beginAtZero: true,
                        angleLines: {
                            color: 'rgba(148, 163, 184, 0.25)', 
                            lineWidth: 1
                        },
                        grid: {
                            display: true,
                            color: 'rgba(148, 163, 184, 0.25)', 
                            circular: false, 
                            lineWidth: 1.5
                        },
                        pointLabels: {
                            display: true,
                            color: '#475569', 
                            font: {
                                size: 12, 
                                weight: '600',
                                family: "'Inter', sans-serif"
                            },
                            padding: 15 
                        },
                        ticks: {
                            stepSize: 1,
                            display: true, 
                            color: '#94a3b8', 
                            backdropColor: 'transparent', 
                            font: {size: 10, weight: '500', family: "'Inter', sans-serif"}
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 13, family: "'Inter', sans-serif", weight: '600' },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        boxPadding: 6,
                        usePointStyle: true
                    }
                }
            }
        });
    }

    const initialGroupId = {{ $chartGroups->first()?->id ?? 0 }};
    if (initialGroupId) {
        setTimeout(() => renderSpiderChart(initialGroupId), 100);
    }

    document.body.addEventListener('htmx:afterSwap', function() {
        if (initialGroupId) {
            setTimeout(() => renderSpiderChart(initialGroupId), 100);
        }
    });
</script>
<!-- Modal for Penilaian Spider Chart -->
@push('modals')
@php
    $jabatan = strtolower($Idp->karyawan->position?->nama_jabatan ?? '');
    $fields = [];
    if ($jabatan === 'department head' || $jabatan === 'section head') {
        $fields = ['head_managing_change' => 'Managing Change', 'head_empowerment' => 'Empowerment', 'head_fostering_teamwork' => 'Fostering Teamwork', 'head_analysis_decision' => 'Analysis & Decision', 'head_planning_controlling' => 'Planning & Controlling', 'head_integrity_ethics' => 'Integrity & Ethics', 'head_excellence' => 'Excellence', 'head_compassion' => 'Compassion', 'head_humility' => 'Humility'];
    } elseif ($jabatan === 'staff') {
        $fields = ['staff_leads_courageously' => 'Leads Courageously', 'staff_coach_develop' => 'Coach & Develop', 'staff_teamwork' => 'Teamwork', 'staff_trouble_shooting' => 'Trouble Shooting', 'staff_customer_satisfaction' => 'Customer Satisfact.', 'staff_cross_functional' => 'Cross Functional', 'staff_quality_focus' => 'Quality Focus', 'staff_continuous_improvement' => 'Continuous Improv.', 'staff_planning_monitoring' => 'Planning Monitor.', 'staff_personal_integrity' => 'Personal Integrity', 'staff_drive_for_result' => 'Drive For Result', 'staff_learning' => 'Learning'];
    } else {
        $fields = ['unit_directiveness' => 'Directiveness', 'unit_monitoring' => 'Monitoring', 'unit_teamwork' => 'Teamwork', 'unit_quality_customer' => 'Quality & Customer', 'unit_continuous_improvement' => 'Continuous Improv.', 'unit_personal_integrity' => 'Personal Integrity'];
    }
@endphp
<div x-data="{ 
    showModal: false,
    isSubmitting: false,
    formErrors: {},
    async submitForm(form) {
        this.isSubmitting = true; this.formErrors = {};
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if (response.ok || response.redirected) { window.location.reload(); }
            else if (response.status === 422) { const data = await response.json(); this.formErrors = data.errors || {}; }
            else { const data = await response.json().catch(() => ({})); alert(data.message || 'A server error occurred.'); }
        } catch (e) { alert('Failed to connect to the server.'); }
        finally { this.isSubmitting = false; }
    },
    @foreach ($fields as $dbKey => $label)
    '{{ $dbKey }}': {{ optional($Idp->penilaian)->$dbKey ?? 0 }},
    @endforeach
}" @open-penilaian-modal.window="showModal = true" class="relative z-[100]">
    <!-- Background overlay -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity cursor-pointer"
         @click="showModal = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0"
         @click.self="showModal = false">
        <div @click.stop
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-sm border border-gray-100 my-8 flex flex-col">
                <div class="bg-white px-4 pt-3 pb-2 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-[#1e293b] text-base" id="modal-title">
                            Input Skor Kompetensi
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Input skor skala 0-5.0</p>
                    </div>
                    <button type="button" @click="showModal = false" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
                
                <form action="{{ $Idp->id ? route('idp.update-penilaian', $Idp->id) : route('idp.update-employee-penilaian', $Idp->karyawan->id) }}" method="POST" hx-boost="false" @submit.prevent="submitForm($el)">
                    @csrf
                    <div class="bg-white px-4 py-3 max-h-[50vh] overflow-y-auto custom-scrollbar">
                        <div class="grid grid-cols-2 gap-x-3 gap-y-3">
                            
                            @foreach($fields as $dbKey => $label)
                                <div class="flex flex-col justify-end">
                                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">{{ $label }}</label>
                                    <input type="number" step="0.1" min="0" max="5" name="{{ $dbKey }}" value="{{ optional($Idp->penilaian)->$dbKey ?? '' }}" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="isSubmitting" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed">
                            <i class="fas text-[10px]" :class="isSubmitting ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                            <span x-text="isSubmitting ? 'Saving...' : 'Save'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

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
</style>
@endpush

@push('modals')
<div x-data="{ showModal: false, isSubmitting: false, formErrors: {},
    async submitForm(form) {
        this.isSubmitting = true; this.formErrors = {};
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if (response.ok || response.redirected) { window.location.reload(); }
            else if (response.status === 422) { const data = await response.json(); this.formErrors = data.errors || {}; }
            else { const data = await response.json().catch(() => ({})); alert(data.message || 'A server error occurred.'); }
        } catch (e) { alert('Failed to connect to the server.'); }
        finally { this.isSubmitting = false; }
    }
}" @open-idp-info-modal.window="showModal = true" class="relative z-[100]">
    <!-- Background overlay -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity cursor-pointer"
         @click="showModal = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0"
         @click.self="showModal = false">
        <div @click.stop
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto max-h-[90vh]">
            <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">IDP Information</h3>
                </div>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ $Idp->id ? route('idp.update-info', $Idp->id) : route('idp.update-employee-info', $Idp->karyawan->id) }}" method="POST" hx-boost="false" class="flex flex-col overflow-hidden" @submit.prevent="submitForm($el)">
                @csrf
                <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Year</label>
                        <input type="text" name="tahun" value="{{ $Idp->tahun ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="e.g. 2024">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Period</label>
                        <input type="text" name="periode" value="{{ $Idp->periode ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="e.g. 1 or 2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Status</label>
                        <select name="status_idp" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" >
                            <option value="" {{ empty($Idp->status_idp) ? 'selected' : '' }}>-- Select Status --</option>
                            <option value="in_progress" {{ ($Idp->status_idp ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ ($Idp->status_idp ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div> 
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Mentor</label>
                        <input type="text" name="mentor" value="{{ $Idp->mentor ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="Mentor Name">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Development Area</label>
                        <input type="text" name="development" value="{{ $Idp->development ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="Development Area">
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                    <button type="button" @click="showModal = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                    <button type="submit" :disabled="isSubmitting" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-teal-500 text-sm font-medium text-white hover:bg-teal-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed">
                        <i class="fas text-xs" :class="isSubmitting ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                        <span x-text="isSubmitting ? 'Saving...' : 'Save'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('modals')
<div x-data="{ showModal: false, isSubmitting: false, formErrors: {},
    async submitForm(form) {
        this.isSubmitting = true; this.formErrors = {};
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if (response.ok || response.redirected) { window.location.reload(); }
            else if (response.status === 422) { const data = await response.json(); this.formErrors = data.errors || {}; }
            else { const data = await response.json().catch(() => ({})); alert(data.message || 'A server error occurred.'); }
        } catch (e) { alert('Failed to connect to the server.'); }
        finally { this.isSubmitting = false; }
    }
}" @open-jadwal-modal.window="showModal = true" class="relative z-[100]">
    <!-- Background overlay -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity cursor-pointer"
         @click="showModal = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0"
         @click.self="showModal = false">
        <div @click.stop
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto max-h-[90vh]">
            <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Review Schedule</h3>
                </div>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ $Idp->id ? route('idp.update-jadwal', $Idp->id) : route('idp.update-employee-jadwal', $Idp->karyawan->id) }}" method="POST" hx-boost="false" class="flex flex-col" @submit.prevent="submitForm($el)">
                @csrf
                <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Target Setting</label>
                        <input type="date" name="target" value="{{ $Idp->target ? \Carbon\Carbon::parse($Idp->target)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Monitoring</label>
                        <input type="date" name="monitoring" value="{{ $Idp->monitoring ? \Carbon\Carbon::parse($Idp->monitoring)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Final Review</label>
                        <input type="date" name="final" value="{{ $Idp->final ? \Carbon\Carbon::parse($Idp->final)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                    <button type="button" @click="showModal = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                    <button type="submit" :disabled="isSubmitting" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-sky-500 text-sm font-medium text-white hover:bg-sky-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5 disabled:opacity-60 disabled:cursor-not-allowed">
                        <i class="fas text-xs" :class="isSubmitting ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                        <span x-text="isSubmitting ? 'Saving...' : 'Save'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@endsection






