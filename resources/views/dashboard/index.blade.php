@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard HC</h1>
    <p class="text-sm text-gray-500 mt-1">Executive overview of human capital metrics</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Karyawan (Biru) -->
    <div class="card-3d p-5 flex items-center gap-5 h-auto min-h-[100px]">
        <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-500 border border-sky-100 flex items-center justify-center text-lg shrink-0 shadow-sm">
            <i class="fas fa-users"></i>
        </div>
        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-900 leading-none">{{ $totalKaryawan }}</h3>
            <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wide">Total Employee</p>
        </div>
    </div>

    <!-- Card 2: Kader Ready R3 (Hijau) -->
    <div class="card-3d p-5 flex items-center gap-5 h-auto min-h-[100px]">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center text-lg shrink-0 shadow-sm">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-900 leading-none">{{ $r3Count }}</h3>
            <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wide">Kader Ready (R3)</p>
        </div>
    </div>

    <!-- Card 3: Kader Ready R5 (Ungu) -->
    <div class="card-3d p-5 flex items-center gap-5 h-auto min-h-[100px]">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 border border-indigo-100 flex items-center justify-center text-lg shrink-0 shadow-sm">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-900 leading-none">{{ $r5Count }}</h3>
            <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wide">Kader Ready (R5)</p>
        </div>
    </div>

    <!-- Card 4: HAV Star (Kuning Emas) -->
    <div class="card-3d p-5 flex items-center gap-5 h-auto min-h-[100px]">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center text-lg shrink-0 shadow-sm">
            <i class="fas fa-medal"></i>
        </div>
        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-900 leading-none">{{ $starCount }}</h3>
            <p class="text-[11px] text-gray-500 font-medium mt-1.5 uppercase tracking-wide">HAV Star</p>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <!-- Doughnut Chart -->
    <div class="card-3d p-6">
        <h3 class="font-bold text-gray-900">Employee Generation Demographics</h3>
        <p class="text-xs text-gray-500 mb-6">Distribution by generational group</p>
        <div class="relative h-48 w-full flex justify-center">
            <canvas id="genChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-4">
                <span class="text-2xl font-bold text-gray-900">{{ $totalKaryawan }}</span>
                <span class="text-[10px] text-gray-500 uppercase font-semibold">Total Employee</span>
            </div>
        </div>
        <div class="flex justify-center gap-6 mt-6 text-xs text-gray-600">
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#10b981]"></span> Milenial</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#0ea5e9]"></span> Gen Z</div>
            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#f59e0b]"></span> Gen X</div>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="card-3d p-6">
        <h3 class="font-bold text-gray-900">HAV Assessment Distribution</h3>
        <p class="text-xs text-gray-500 mb-6">Number of employees per HAV assessment category</p>
        <div class="h-56 w-full">
            <canvas id="havChart"></canvas>
        </div>
    </div>
</div>

<!-- Competency Assessment Keseluruhan -->
<div class="mb-4 mt-8">
    <h2 class="text-lg font-bold text-gray-900">Competency Assessment Overall</h2>
    <p class="text-xs text-gray-500 mt-1">Average competency assessment of all employees per job level &mdash; Head, Staff, Unit Head/Group Head</p>
</div>

<!-- Alpine JS Wrapper -->
<script>
    window.idpEmployeesData = @json($IDP_LEVEL_EMPLOYEES ?? []);
</script>
<div x-data="{ 
        levelDetail: null, 
        employeesData: window.idpEmployeesData,
        get currentEmployees() {
            return this.levelDetail ? (this.employeesData[this.levelDetail] || []) : [];
        }
    }">
    <!-- Radar Charts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @foreach($IDP_LEVEL_COMPETENCY ?? [] as $level => $data)
        <div class="card-3d p-6 flex flex-col hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">{{ $data['title'] }}</h3>
                    <p class="text-[10px] text-gray-400">{{ $data['total_employees'] }} employee • rata-rata {{ $data['overall_average'] }} / 5.0</p>
                </div>
                <button @click="levelDetail = '{{ $level }}'" class="btn-3d text-[10px] font-bold px-3 py-1.5 uppercase tracking-wide">View Details</button>
            </div>
            <div class="h-48 w-full mt-4 flex justify-center">
                <canvas id="radar-{{ $level }}"></canvas>
            </div>
            <div class="mt-6 flex-1 flex flex-col justify-end">
                <p class="text-[9px] font-bold text-gray-400 mb-3 uppercase tracking-wider">Area to Improve</p>
                <div class="card-3d-inner p-3 rounded-xl" id="gap-{{ $level }}">
                    <!-- JS Injected Gaps -->
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Level Detail -->
    <template x-teleport="body">
        <div x-cloak x-show="levelDetail" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-black/50"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col overflow-hidden" @click.outside="levelDetail = null">
                <!-- Header -->
                <div class="px-6 py-5 flex justify-between items-start bg-white">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Competency Assessment Detail</h2>
                        <p class="text-xs text-gray-500 mt-1">Level: <span class="capitalize" x-text="levelDetail === 'unit_head_group_head' ? 'Unit Head / Group Head' : (levelDetail === 'head' ? 'Department Head' : (levelDetail ? levelDetail.replace(/_/g, ' ') : ''))"></span></p>
                    </div>
                    <button @click="levelDetail = null" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors mt-1">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6 overflow-y-auto bg-white flex-1 pt-4">
                    <div class="bg-gray-50/50 border border-gray-100 rounded-xl">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="text-gray-500 text-[10px] font-bold tracking-wider uppercase border-b border-gray-100">
                                <tr>
                                <th class="px-6 py-4 rounded-tl-xl">Employee</th>
                                <th class="px-6 py-4">Department</th>
                                <th class="px-6 py-4">Individual Average</th>
                                <th class="px-6 py-4 rounded-tr-xl text-[#ef4444]">Biggest Weakness</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <template x-for="emp in currentEmployees" :key="emp.id">
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <template x-if="emp.foto">
                                                <img :src="emp.foto" class="w-8 h-8 rounded-full object-cover shadow-sm">
                                            </template>
                                            <template x-if="!emp.foto">
                                                <div class="w-8 h-8 rounded-full bg-gray-200/80 flex items-center justify-center text-gray-500 shadow-sm"><i class="fas fa-user text-[10px]"></i></div>
                                            </template>
                                            <span class="font-semibold text-gray-900 text-sm" x-text="emp.nama"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-medium" x-text="emp.department"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3 w-28 sm:w-32">
                                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-[#0ea5e9] rounded-full" :style="`width: ${(emp.average_score / 5) * 100}%`"></div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 w-8" x-text="parseFloat(emp.average_score).toFixed(2)"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded max-w-full relative group/tooltip" :class="emp.weakest_score > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-gray-50 text-gray-400 border border-gray-100'">
                                            <i class="fas fa-arrow-down text-[8px] shrink-0" x-show="emp.weakest_score > 0"></i>
                                            <span class="font-bold text-xs truncate max-w-[120px] sm:max-w-[160px] cursor-pointer" :class="{'font-normal': emp.weakest_score == 0}" x-text="emp.weakest_competency"></span>
                                            <span class="text-[10px] font-semibold text-red-400 shrink-0" x-show="emp.weakest_score > 0" x-text="`(${emp.weakest_score})`"></span>
                                            
                                            <!-- Custom Tailwind Tooltip (Sleek original dark pill style with clean multiline wrap) -->
                                            <div class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-800 text-white text-xs font-medium rounded-lg opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 shadow-xl border border-gray-700/60 z-50 pointer-events-none"
                                                 style="width: 260px; max-width: 280px; white-space: normal !important; word-break: break-word !important; line-height: 1.5; text-align: left;">
                                                <div style="font-size: 11px; line-height: 1.5; color: #f1f5f9; white-space: normal !important;" x-text="emp.all_weaknesses || emp.weakest_competency"></div>
                                                <!-- Caret pointing down into the middle of the badge -->
                                                <div class="absolute top-full -mt-1 w-2 h-2 bg-gray-800 rotate-45 border-r border-b border-gray-700/60" style="right: 45px;"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="currentEmployees.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic bg-white">No evaluation data yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </template>
</div>

<!-- Peringatan Stagnasi -->
<div class="card-3d p-6 mb-10">
    <h2 class="text-lg font-bold text-gray-900">Stagnation Warning</h2>
    <p class="text-xs text-gray-500 mt-1 mb-4">Employees who have been in the same readiness status for 2 years or more despite participating in development programs</p>
    
    <div class="flex flex-col gap-3">
        @forelse($stagnasiWarnings ?? [] as $karyawan)
            <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-4 flex items-center gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm">{{ $karyawan->nama }}</h3>
                    <p class="text-xs text-gray-500">{{ $karyawan->department ? $karyawan->department->nama_departemen : '-' }} &bull; {{ $karyawan->position?->nama_jabatan }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded uppercase tracking-wide border border-red-100">
                        Readiness: {{ $karyawan->readiness ?? 'Belum Dinilai' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="card-3d-inner rounded-xl p-6 text-center text-sm text-gray-500">
                No stagnation warnings yet.
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js Scripts -->
<script>
    (function() {
        // Setup Chart defaults
        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = "#9ca3af";

        // Clean up old instances if they exist (HTMX navigation)
        if (window.genChartInstance) window.genChartInstance.destroy();
        if (window.havChartInstance) window.havChartInstance.destroy();
        if (window.radarHeadInstance) window.radarHeadInstance.destroy();
        if (window.radarStaffInstance) window.radarStaffInstance.destroy();
        if (window.radarUnitHeadInstance) window.radarUnitHeadInstance.destroy();

        // Gen Chart (Doughnut)
        window.genChartInstance = new Chart(document.getElementById('genChart'), {
            type: 'doughnut',
            data: {
                labels: ['Milenial', 'Gen Z', 'Gen X'],
                datasets: [{
                    data: [{{ $genMilenial }}, {{ $genZ }}, {{ $genX }}],
                    backgroundColor: ['#10b981', '#0ea5e9', '#f59e0b'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
    
    // HAV Chart (Bar)
   window.havChartInstance = new Chart(document.getElementById('havChart'), {
        type: 'bar',
        data: {
           
            labels: [
                'Star', 
                ['High', 'Potential'], 
                'Potential', 
                ['Excellent', 'Career'], 
                ['Career', 'Person'], 
                ['Problem', 'Employee'], 
                'Deadwood'
            ],
            datasets: [{
                label: 'Jumlah Karyawan',
                data: [
                    {{ $havStats['star'] ?? 0 }}, 
                    {{ $havStats['high_potential'] ?? 0 }}, 
                    {{ $havStats['potential'] ?? 0 }}, 
                    {{ $havStats['excellent_career'] ?? 0 }}, 
                    {{ $havStats['career_person'] ?? 0 }}, 
                    {{ $havStats['problem_employee'] ?? 0 }}, 
                    {{ $havStats['deadwood'] ?? 0 }}
                ],
                backgroundColor: [
                    '#3b82f6',
                    '#15803d',
                    '#22c55e',
                    '#eab308',
                    '#f97316',
                    '#ef4444',
                    '#6b7280'  
                ],
                hoverBackgroundColor: [
                    '#2563eb', 
                    '#166534', 
                    '#16a34a', 
                    '#ca8a04', 
                    '#ea580c', 
                    '#dc2626', 
                    '#4b5563'  
                ],
                borderWidth: 0,
                borderRadius: 6, 
                barPercentage: 0.7,
                categoryPercentage: 0.85
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    titleFont: { size: 13, family: "'Outfit', sans-serif" },
                    bodyFont: { size: 14, weight: 'bold', family: "'Outfit', sans-serif" },
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            let label = context[0].label;
                            return Array.isArray(label) ? label.join(' ') : label;
                        },
                        label: function(context) {
                            return context.raw + ' Karyawan';
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { 
                        color: '#f3f4f6',
                        drawBorder: false
                    }, 
                    border: { display: false },
                    ticks: {
                        stepSize: 1, 
                        font: { size: 11, family: "'Outfit', sans-serif" }
                    }
                },
                x: { 
                    grid: { display: false }, 
                    border: { display: false }, 
                    ticks: { 
                        font: { 
                            size: 11, 
                            weight: '600', 
                            family: "'Outfit', sans-serif" 
                        }, 
                        maxRotation: 0,
                        minRotation: 0,
                        color: '#4b5563', 
                        padding: 8 
                    } 
                }
            },
            animation: {
                y: { duration: 1000, easing: 'easeOutQuart' }
            }
        }
    });

    // Radar Chart Options
    const radarOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                angleLines: { color: '#e5e7eb' },
                grid: { color: '#e5e7eb' },
                pointLabels: { font: { size: 7 }, color: '#6b7280' },
                ticks: { display: false, min: 0, max: 5 }
            }
        },
        plugins: { 
            legend: { display: false }, 
            tooltip: { 
                enabled: true,
                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                padding: 12,
                titleFont: { size: 13, family: "'Outfit', sans-serif" },
                bodyFont: { size: 13, family: "'Outfit', sans-serif" },
                displayColors: true,
                callbacks: {
                    title: function(context) {
                        return context[0].label;
                    },
                    label: function(context) {
                        return context.dataset.label + ': ' + parseFloat(context.raw).toFixed(2);
                    }
                }
            } 
        }
    };

    // Function to calculate gaps and populate HTML
    function calculateGap(labels, scores, targets, containerId) {
        let items = labels.map((label, index) => {
            let score = scores[index] || 0;
            let target = targets[index] || 5;
            return {
                name: label,
                score: score,
                target: target,
                gap: target - score
            };
        });
        
        // Sort by biggest gap (lowest score) first
        items.sort((a, b) => b.gap - a.gap);
        
        // Get top 3
        let top3 = items.slice(0, 3);
        
        let container = document.getElementById(containerId);
        if(!container) return;
        
        if(top3.length === 0 || (top3[0].score === 0 && top3[0].gap === 5)) {
             container.className = 'card-3d-inner p-3 rounded-xl flex items-center justify-center min-h-[80px]';
             container.innerHTML = '<div class="text-center text-gray-400 text-xs py-2">Belum ada data penilaian</div>';
             return;
        }

        container.className = 'bg-white border border-gray-100 shadow-sm p-3 rounded-xl';
        let html = '<div class="flex flex-col gap-2.5">';
        top3.forEach(item => {
            html += `<div class="flex items-center text-left py-1.5 px-2 rounded-lg hover:-translate-y-0.5 hover:shadow hover:bg-white hover:z-10 transition-all duration-200 cursor-default">
                        <span class="text-gray-700 font-medium text-xs truncate pr-2 flex-1">${item.name}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 text-xs font-medium">
                                ${item.score.toFixed(1)} <span class="text-gray-400">/</span> ${item.target.toFixed(1)}
                            </span>
                            <div class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-red-50/80 text-red-600 shrink-0 min-w-[36px]">
                                <span class="font-bold text-[10px]">-${item.gap.toFixed(1)}</span>
                            </div>
                        </div>
                    </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    // Prepare data from PHP
    const levelCompetency = @json($IDP_LEVEL_COMPETENCY ?? []);
    
    // Render dynamic radar charts
    for (const [level, data] of Object.entries(levelCompetency)) {
        if(window['radarInstance_' + level]) window['radarInstance_' + level].destroy();
        
        const labels = data.competencies.map(c => c.label);
        const scores = data.competencies.map(c => c.score);
        const targets = data.competencies.map(c => c.target);
        
        const canvas = document.getElementById('radar-' + level);
        if(canvas) {
            window['radarInstance_' + level] = new Chart(canvas, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Skor Aktual',
                            data: scores,
                            backgroundColor: 'rgba(56, 189, 248, 0.4)',
                            borderColor: '#0ea5e9',
                            borderWidth: 1.5,
                            pointBackgroundColor: '#0ea5e9',
                            pointRadius: 2
                        },
                        {
                            label: 'Target',
                            data: targets,
                            backgroundColor: 'transparent',
                            borderColor: '#eab308',
                            borderDash: [5, 5],
                            borderWidth: 1.5,
                            pointBackgroundColor: '#eab308',
                            pointRadius: 0
                        }
                    ]
                },
                options: radarOptions
            });
            
            calculateGap(labels, scores, targets, 'gap-' + level);
        }
    }
    })();
</script>
@endsection

