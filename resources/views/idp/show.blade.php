@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="{{ route('idp.index') }}" class="hover:text-gray-700 transition-colors">IDP</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <span class="text-gray-900 font-medium">Detail Individual Development Plan</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('idp.index') }}" class="bg-[#0ea5e9] hover:bg-[#0284c7] shadow-sm hover:shadow-md rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-white">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

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
                   
                    
                    @can('tambah_idp')
                    @if($Idp->id)
                        <button type="button" x-data @click="$dispatch('open-idp-info-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Edit IDP">
                            <i class="fas fa-pen"></i>
                        </button>
                    @else
                        <button type="button" x-data @click="$dispatch('open-idp-info-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Tambah IDP">
                            <i class="fas fa-plus"></i>
                        </button>
                    @endif
                    @endcan
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
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Tahun</p>
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-teal-700 transition-colors">{{ $Idp->tahun ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Periode -->
                    <div class="group flex items-center gap-4 p-4 rounded-xl border border-white/50 bg-white/60 backdrop-blur-sm hover:bg-white/80 hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 border border-indigo-100 group-hover:bg-indigo-100 group-hover:border-indigo-200 group-hover:text-indigo-600 transition-colors shadow-sm">
                            <i class="fas fa-layer-group text-indigo-400 group-hover:text-indigo-600 transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-teal-600/70 uppercase mb-0.5">Periode</p>
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
                    <i class="far fa-calendar-check text-sky-500 text-base"></i> Jadwal Review
                </h3>
                <div>
                    @can('edit_idp')
                    @if($Idp->id)
                        <button type="button" x-data @click="$dispatch('open-jadwal-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Edit Jadwal">
                            <i class="fas fa-pen"></i>
                        </button>
                    @else
                        <button type="button" x-data @click="$dispatch('open-jadwal-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="Tambah Jadwal">
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
                            <p class="text-xs text-sky-600/80 font-medium flex items-center gap-1.5"><i class="far fa-clock text-sky-400"></i> {{ $Idp->target ? \Carbon\Carbon::parse($Idp->target)->format('d M Y') : 'Belum diset' }}</p>
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
                            <p class="text-xs text-sky-600/80 font-medium flex items-center gap-1.5"><i class="far fa-clock text-sky-400"></i> {{ $Idp->monitoring ? \Carbon\Carbon::parse($Idp->monitoring)->format('d M Y') : 'Belum diset' }}</p>
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
                            <p class="text-xs text-sky-600/80 font-medium flex items-center gap-1.5 group-hover:text-sky-700 transition-colors"><i class="far fa-clock text-sky-400 group-hover:text-indigo-300"></i> {{ $Idp->final ? \Carbon\Carbon::parse($Idp->final)->format('d M Y') : 'Belum diset' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column (Competency Assessment) -->
    <div class="lg:col-span-8 flex flex-col gap-6 w-full">
        <!-- Competency Assessment -->
        <div class="bg-gradient-to-br from-slate-50 to-gray-100/80 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-200 overflow-hidden flex flex-col relative transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-500"></div>
            
            <!-- Header -->
            <div class="px-6 py-5 flex justify-between items-center bg-slate-50/50 border-b border-gray-50">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="fas fa-spider text-teal-500 text-base"></i> Competency Assessment
                    </h3>
                    <p class="text-xs text-gray-500 mt-1 ml-6">IDP {{ ucwords($Idp->karyawan->position?->nama_jabatan) }}</p>
                </div>
                @can('edit_idp')
                <button x-data @click="$dispatch('open-penilaian-modal')" class="group relative overflow-hidden bg-white border border-gray-200 hover:border-teal-300 text-gray-700 hover:text-teal-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all duration-300 flex items-center gap-2 hover:shadow-md hover:-translate-y-0.5">
                    <span class="absolute inset-0 bg-teal-50 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center gap-2">
                        @if($Idp->penilaian)
                            <i class="fas fa-edit"></i> Edit
                        @else
                            <i class="fas fa-plus"></i> Tambah
                        @endif
                    </span>
                </button>
                @endcan
            </div>

            <!-- Chart Area -->
            <div class="bg-white/60 backdrop-blur-sm mx-0 sm:mx-6 my-6 rounded-2xl p-4 sm:p-8 relative border border-white/50 shadow-[0_2px_15px_rgba(0,0,0,0.02)] w-full block">
                <!-- Wrapper for Chart to control its exact size -->
                <div class="relative w-full min-w-[280px] sm:min-w-[400px] h-[400px] md:h-[500px] mx-auto overflow-hidden">
                    <canvas id="competencyChart" style="width: 100%; height: 100%;"></canvas>
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
                        <i class="fas fa-list-ul"></i> Competency Breakdown ({{ strtoupper($Idp->karyawan->position?->nama_jabatan) }} LEVEL)
                    </h4>
                    <span class="text-xs font-bold px-3 py-1 bg-teal-50 text-teal-700 rounded-full border border-teal-100" id="avgScore">Avg: 0.0 / 5.0</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="competencyCards">
                    <!-- JS will populate this -->
                </div>
            </div>
        </div>

    </div>
</div>

<script>
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

    function renderIdpChart() {
        const ctxEl = document.getElementById('competencyChart');
        if (!ctxEl) return;
        
        const jabatan = "{{ strtolower($Idp->karyawan->position?->nama_jabatan) }}";
        const penilaianStr = @json($Idp->penilaian);
        let penilaian = null;
        try {
            if (penilaianStr) penilaian = JSON.parse(penilaianStr);
        } catch(e) {
            penilaian = @json($Idp->penilaian);
        }
        
        let labels = [];
        let dataScores = [];
        let cardsHtml = '';
        let total = 0;
        let count = 0;

        if (jabatan === 'department head' || jabatan === 'section head') {
            const keys = ['Managing Change', 'Empowerment', 'Fostering Teamwork', 'Analysis & Decision Making', 'Planning & Controlling', 'Integrity & Ethics', 'Excellence', 'Compassion', 'Humility'];
            const dbKeys = ['head_managing_change', 'head_empowerment', 'head_fostering_teamwork', 'head_analysis_decision', 'head_planning_controlling', 'head_integrity_ethics', 'head_excellence', 'head_compassion', 'head_humility'];
            
            keys.forEach((key, index) => {
                labels.push(getMultilineLabel(key));
                let score = penilaian ? (penilaian[dbKeys[index]] || 0) : 0;
                let parsedScore = parseFloat(score);
                dataScores.push(score);
                total += parsedScore;
                count++;
                
                let scoreColor = parsedScore >= 4 ? 'text-teal-600' : (parsedScore >= 3 ? 'text-indigo-600' : 'text-amber-600');
                let scoreBg = parsedScore >= 4 ? 'bg-teal-50 border-teal-100' : (parsedScore >= 3 ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100');
                
                cardsHtml += `
                <div class="group flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:bg-slate-50 transition-all duration-300 hover:shadow-md hover:border-gray-200 hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-full shrink-0 bg-slate-50 flex items-center justify-center border border-gray-200 text-xs font-bold text-gray-400 group-hover:bg-white group-hover:text-teal-500 transition-colors">
                            ${index + 1}
                        </div>
                        <h5 class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition-colors line-clamp-2 leading-tight" title="${key}">${key}</h5>
                    </div>
                    <div class="shrink-0 px-2.5 py-1 rounded-lg border ${scoreBg} ${scoreColor} text-xs font-bold shadow-sm">
                        ${parsedScore.toFixed(1)}
                    </div>
                </div>
                `;
            });
        } else if (jabatan === 'staff') {
            const keys = ['Leads Courageously', 'Coach & Develop Others', 'Teamwork', 'Trouble Shooting', 'Customer Satisfaction', 'Cross Functional Capability', 'Quality Focus', 'Continuous Improvement', 'Planning Monitoring', 'Personal Integrity', 'Drive For Result', 'Learning'];
            const dbKeys = ['staff_leads_courageously', 'staff_coach_develop', 'staff_teamwork', 'staff_trouble_shooting', 'staff_customer_satisfaction', 'staff_cross_functional', 'staff_quality_focus', 'staff_continuous_improvement', 'staff_planning_monitoring', 'staff_personal_integrity', 'staff_drive_for_result', 'staff_learning'];
            
            keys.forEach((key, index) => {
                labels.push(getMultilineLabel(key));
                let score = penilaian ? (penilaian[dbKeys[index]] || 0) : 0;
                let parsedScore = parseFloat(score);
                dataScores.push(score);
                total += parsedScore;
                count++;
                
                let scoreColor = parsedScore >= 4 ? 'text-teal-600' : (parsedScore >= 3 ? 'text-indigo-600' : 'text-amber-600');
                let scoreBg = parsedScore >= 4 ? 'bg-teal-50 border-teal-100' : (parsedScore >= 3 ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100');
                
                cardsHtml += `
                <div class="group flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:bg-slate-50 transition-all duration-300 hover:shadow-md hover:border-gray-200 hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-full shrink-0 bg-slate-50 flex items-center justify-center border border-gray-200 text-xs font-bold text-gray-400 group-hover:bg-white group-hover:text-teal-500 transition-colors">
                            ${index + 1}
                        </div>
                        <h5 class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition-colors line-clamp-2 leading-tight" title="${key}">${key}</h5>
                    </div>
                    <div class="shrink-0 px-2.5 py-1 rounded-lg border ${scoreBg} ${scoreColor} text-xs font-bold shadow-sm">
                        ${parsedScore.toFixed(1)}
                    </div>
                </div>
                `;
            });
        } else {
            const keys = ['Directiveness', 'Monitoring', 'Teamwork', 'Quality & Customer Focus', 'Continuous Improvement', 'Personal Integrity'];
            const dbKeys = ['unit_directiveness', 'unit_monitoring', 'unit_teamwork', 'unit_quality_customer', 'unit_continuous_improvement', 'unit_personal_integrity'];
            
            keys.forEach((key, index) => {
                labels.push(getMultilineLabel(key));
                let score = penilaian ? (penilaian[dbKeys[index]] || 0) : 0;
                let parsedScore = parseFloat(score);
                dataScores.push(score);
                total += parsedScore;
                count++;
                
                let scoreColor = parsedScore >= 4 ? 'text-teal-600' : (parsedScore >= 3 ? 'text-indigo-600' : 'text-amber-600');
                let scoreBg = parsedScore >= 4 ? 'bg-teal-50 border-teal-100' : (parsedScore >= 3 ? 'bg-indigo-50 border-indigo-100' : 'bg-amber-50 border-amber-100');
                
                cardsHtml += `
                <div class="group flex items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:bg-slate-50 transition-all duration-300 hover:shadow-md hover:border-gray-200 hover:-translate-y-0.5">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-full shrink-0 bg-slate-50 flex items-center justify-center border border-gray-200 text-xs font-bold text-gray-400 group-hover:bg-white group-hover:text-teal-500 transition-colors">
                            ${index + 1}
                        </div>
                        <h5 class="text-xs font-bold text-gray-700 group-hover:text-gray-900 transition-colors line-clamp-2 leading-tight" title="${key}">${key}</h5>
                    </div>
                    <div class="shrink-0 px-2.5 py-1 rounded-lg border ${scoreBg} ${scoreColor} text-xs font-bold shadow-sm">
                        ${parsedScore.toFixed(1)}
                    </div>
                </div>
                `;
            });
        }

        const cardsContainer = document.getElementById('competencyCards');
        if(cardsContainer) {
            cardsContainer.innerHTML = cardsHtml;
        }
        
        const avgContainer = document.getElementById('avgScore');
        if(avgContainer && count > 0) {
            avgContainer.innerText = 'Avg: ' + (total/count).toFixed(2) + ' / 5.0';
        }

        if (window.idpChartInstance) {
            window.idpChartInstance.destroy();
        }

        const ctx = ctxEl.getContext('2d');
        window.idpChartInstance = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target Maksimal (5.0)',
                        data: Array(labels.length).fill(5),
                        backgroundColor: 'transparent',
                        borderColor: '#cbd5e1', // slate-300
                        borderWidth: 1.5,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Skor Aktual',
                        data: dataScores,
                        backgroundColor: 'rgba(13, 148, 136, 0.25)', // teal-600 with opacity
                        borderColor: '#0d9488', // teal-600
                        pointBackgroundColor: '#0d9488',
                        pointBorderColor: '#ffffff',
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#0d9488',
                        borderWidth: 2.5,
                        pointRadius: 5,
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 2.5,
                        tension: 0, // Dibuat 0 agar garis lurus (segi 9/poligon), tidak bulat
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
                        backgroundColor: 'rgba(15, 23, 42, 0.9)', // slate-900
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
    } // Eksekusi segera
    setTimeout(renderIdpChart, 50);

    // Untuk HTMX SPA swap
    document.body.addEventListener('htmx:afterSwap', function(event) {
        if (event.detail.target.id === 'competencyChart' || document.getElementById('competencyChart')) {
            setTimeout(renderIdpChart, 50);
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
    @foreach ($fields as $dbKey => $label)
    '{{ $dbKey }}': {{ optional($Idp->penilaian)->$dbKey ?? 0 }},
    @endforeach
}" @open-penilaian-modal.window="showModal = true" class="relative z-[9999]">
    <div x-show="showModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
            
            <!-- Modal panel -->
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-sm border border-gray-100 my-8 flex flex-col">
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
                
                <form action="{{ $Idp->id ? route('idp.update-penilaian', $Idp->id) : route('idp.update-employee-penilaian', $Idp->karyawan->id) }}" method="POST">
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
                            Batal
                        </button>
                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                            <i class="fas fa-save text-[10px]"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
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
<div x-data="{ showModal: false }" @open-idp-info-modal.window="showModal = true" class="relative z-[9999]">
    <div x-show="showModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
            
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto">
                <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">IDP Information</h3>
                    </div>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ $Idp->id ? route('idp.update-info', $Idp->id) : route('idp.update-employee-info', $Idp->karyawan->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Tahun</label>
                            <input type="text" name="tahun" value="{{ $Idp->tahun ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="cth. 2024">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Periode</label>
                            <input type="text" name="periode" value="{{ $Idp->periode ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="cth 1 atau 2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Status</label>
                            <select name="status_idp" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" >
                                <option value="" {{ empty($Idp->status_idp) ? 'selected' : '' }}>-- Pilih Status --</option>
                                <option value="in_progress" {{ ($Idp->status_idp ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ ($Idp->status_idp ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div> 
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Mentor</label>
                            <input type="text" name="mentor" value="{{ $Idp->mentor ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="Nama Mentor">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Development Area</label>
                            <input type="text" name="development" value="{{ $Idp->development ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm py-1.5 px-2.5 border" placeholder="Development Area">
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                        <button type="button" @click="showModal = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-teal-500 text-sm font-medium text-white hover:bg-teal-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5"><i class="fas fa-save text-xs"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('modals')
<div x-data="{ showModal: false }" @open-jadwal-modal.window="showModal = true" class="relative z-[9999]">
    <div x-show="showModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
            
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto">
                <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Jadwal Review</h3>
                    </div>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ $Idp->id ? route('idp.update-jadwal', $Idp->id) : route('idp.update-employee-jadwal', $Idp->karyawan->id) }}" method="POST">
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
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-sky-500 text-sm font-medium text-white hover:bg-sky-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5"><i class="fas fa-save text-xs"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@endsection






