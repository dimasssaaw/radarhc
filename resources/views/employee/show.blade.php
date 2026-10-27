@extends('layouts.app')
@section('title', 'Detail Employee - ' . $employee->name)
@section('content')
<!-- Header -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="{{ route('employee.index') }}" class="hover:text-gray-700 transition-colors">Employee data</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <span class="text-gray-900 font-medium">Profile View</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('employee.index') }}" class="bg-white shadow-[0_2px_4px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_6px_rgba(0,0,0,0.05),0_2px_4px_rgba(0,0,0,0.1)] border border-gray-100 rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-gray-700">
            <i class="fas fa-arrow-left text-gray-400"></i> Back
        </a>
    </div>
</div>


<!-- Employee Profile Header -->
@include('components.employee-profile-header', ['employee' => $employee])

<div class="grid grid-cols-1 gap-6 mb-6">
    <!-- 2. Via Character -->
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Via Character</h2>
            @can('tambah_karyawan')
            <button onclick="document.getElementById('addViaCharacterModal').classList.remove('hidden')" class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i> Add Data
            </button>
            @endcan
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] uppercase text-white font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3 bg-[#117666] w-1/2 text-center">OVERUSE STRENGTH</th>
                        <th class="px-6 py-3 bg-[#9b1c43] w-1/2 text-center">UNDERUSE STRENGTH</th>
                        @canany(['edit_karyawan', 'hapus_karyawan'])
                        <th class="px-6 py-3 bg-gray-100 text-gray-500 w-[20%] text-center">ACTIONS</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($employee->viaCharacters ?? [] as $via)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 bg-[#f0f9f8] text-center">{{ $via->overuse_strength }}</td>
                        <td class="px-6 py-4 bg-[#fdf2f5] text-center">{{ $via->underuse_strength }}</td>
                        @canany(['edit_karyawan', 'hapus_karyawan'])
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button onclick="document.getElementById('editViaCharacterModal{{ $via->id }}').classList.remove('hidden')" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 rounded hover:bg-emerald-50" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                            <form action="{{ route('employee-via-character.destroy', $via->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50" title="Delete"><i class="fas fa-trash-alt text-xs"></i></button>
                            </form>
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ (auth()->user()->can('edit_karyawan') || auth()->user()->can('hapus_karyawan')) ? 3 : 2 }}" class="px-6 py-6 text-center text-gray-400 italic text-xs">No data available Via Character</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Strength & Weakness -->
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Strength & Weakness</h2>
            @can('tambah_karyawan')
            <button onclick="document.getElementById('addStrengthModal').classList.remove('hidden')" class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i> Add Data
            </button>
            @endcan
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] uppercase text-white font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3 bg-[#1e4cd5] w-1/2 text-center">STRENGTH</th>
                        <th class="px-6 py-3 bg-[#a85002] w-1/2 text-center">WEAKNESS</th>
                        @canany(['edit_karyawan', 'hapus_karyawan'])
                        <th class="px-6 py-3 bg-gray-100 text-gray-500 w-[20%] text-center">ACTIONS</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($employee->strengths ?? [] as $st)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 bg-[#f2f5fd] text-center">{{ $st->strength }}</td>
                        <td class="px-6 py-4 bg-[#fdf5f0] text-center">{{ $st->weakness }}</td>
                        @canany(['edit_karyawan', 'hapus_karyawan'])
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button onclick="document.getElementById('editStrengthModal{{ $st->id }}').classList.remove('hidden')" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 rounded hover:bg-emerald-50" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                            <form action="{{ route('employee-strength.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50" title="Delete"><i class="fas fa-trash-alt text-xs"></i></button>
                            </form>
                        </td>
                        @endcanany
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ (auth()->user()->can('edit_karyawan') || auth()->user()->can('hapus_karyawan')) ? 3 : 2 }}" class="px-6 py-6 text-center text-gray-400 italic text-xs">No data available Strength & Weakness</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 4. SCARF Profile -->
<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] mb-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-gray-900">SCARF Profile</h2>
        @can('edit_karyawan')
        <button onclick="document.getElementById('editScarfModal').classList.remove('hidden')" class="text-xs font-bold text-emerald-700 flex items-center gap-1 hover:text-emerald-800 transition-colors p-1.5 rounded-lg hover:bg-emerald-50">
            <i class="fas fa-cog"></i> Manage
        </button>
        @endcan
    </div>
    <div class="space-y-5 pr-8">
        @php
            $scarf = $employee->scarf;
            $s_status = $scarf ? $scarf->status : 0;
            $s_certainty = $scarf ? $scarf->certainty : 0;
            $s_autonomy = $scarf ? $scarf->autonomy : 0;
            $s_relatedness = $scarf ? $scarf->relatedness : 0;
            $s_fairness = $scarf ? $scarf->fairness : 0;
        @endphp
        <!-- Status -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Status</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#1b7356] h-2.5 rounded-full transition-all duration-1000" style="width: {{ $s_status }}%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right">{{ $s_status }}%</span>
        </div>
        <!-- Certainty -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Certainty</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#fbb93a] h-2.5 rounded-full transition-all duration-1000" style="width: {{ $s_certainty }}%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right">{{ $s_certainty }}%</span>
        </div>
        <!-- Autonomy -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Autonomy</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#12a1b9] h-2.5 rounded-full transition-all duration-1000" style="width: {{ $s_autonomy }}%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right">{{ $s_autonomy }}%</span>
        </div>
        <!-- Relatedness -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Relatedness</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#c22e23] h-2.5 rounded-full transition-all duration-1000" style="width: {{ $s_relatedness }}%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right">{{ $s_relatedness }}%</span>
        </div>
        <!-- Fairness -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Fairness</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#606a66] h-2.5 rounded-full transition-all duration-1000" style="width: {{ $s_fairness }}%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right">{{ $s_fairness }}%</span>
        </div>
    </div>
</div>

<!-- 5. Mentoring & Coaching Matrix -->
@php
    $mentorings = \App\Models\Mentoring::where('id_karyawan', $employee->id)
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
        

        return [(string) $m->id => [
            'id' => $m->id,
            'label' => $periodLabel,
            'tahun' => $m->tahun,
            'periode' => $m->periode,
            'competency' => $comp,
            'commitment' => $comm,
            'box' => $box,
            'mentor' => $m->mentor ?? '-',
            'url' => route('mentoring.show', $m->id),
        ]];
    });

    $firstMentoringId = $mentorings->first()?->id ? (string) $mentorings->first()->id : '';
@endphp

<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] mb-8 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200"
     x-data="{
        selectedId: '{{ $firstMentoringId }}',
        items: {{ json_encode($mentoringItems) }},
        get current() {
            return this.items[this.selectedId] || null;
        },
        get activeBox() {
            return this.current ? this.current.box : null;
        }
     }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-900">Mentoring & Coaching Matrix</h2>
            </div>
            
            <!-- Subtitle info competency & commitment -->
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
                <template x-if="current?.mentor && current?.mentor !== '-'">
                    <span class="text-gray-400 text-xs hidden md:inline">&bull; Mentor: <span class="font-semibold text-gray-700" x-text="current.mentor"></span></span>
                </template>
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
    
    <div class="w-full rounded-xl overflow-hidden grid grid-cols-2 shadow-xs border border-gray-100 font-bold text-[10px] tracking-wide text-white">
        <!-- Top Left: Yellow (Comp: Rendah, Comm: Tinggi) -->
        <div class="aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 transition-all duration-300"
             :class="(activeBox && activeBox !== 'TL') ? 'opacity-30 bg-[#facd42] text-[#8a6800]' : 'opacity-100 z-10'"
             :style="activeBox === 'TL' 
                ? 'background: linear-gradient(135deg, #fde065 0%, #facd42 60%, #f3c332 100%); box-shadow: 3px 3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.5), inset 0 -1.5px 2px rgba(0,0,0,0.06); color: #7c5c00;' 
                : 'background-color: #facd42; color: #8a6800;'">
            <div class="relative z-10" :style="activeBox === 'TL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.6); font-weight: 800;' : ''">DIRECTING - MODERATE</div>
            <div class="relative z-10" :style="activeBox === 'TL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.6); font-weight: 800;' : ''">MENTORING - MODERATE</div>
            <div class="relative z-10" :style="activeBox === 'TL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.6); font-weight: 800;' : ''">COACHING - MODERATE</div>
        </div>
        
        <!-- Top Right: Blue (Comp: Rendah, Comm: Rendah) -->
        <div class="aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 transition-all duration-300"
             :class="(activeBox && activeBox !== 'TR') ? 'opacity-30 bg-[#4485f5] text-white' : 'opacity-100 z-10'"
             :style="activeBox === 'TR' 
                ? 'background: linear-gradient(135deg, #5390f7 0%, #4485f5 60%, #397ceb 100%); box-shadow: -3px 3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.35), inset 0 -1.5px 2px rgba(0,0,0,0.08); color: #ffffff;' 
                : 'background-color: #4485f5; color: #ffffff;'">
            <div class="relative z-10" :style="activeBox === 'TR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25); font-weight: 800;' : ''">DIRECTING - LOW</div>
            <div class="relative z-10" :style="activeBox === 'TR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25); font-weight: 800;' : ''">MENTORING - LOW</div>
            <div class="relative z-10" :style="activeBox === 'TR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25); font-weight: 800;' : ''">COACHING - HIGH</div>
        </div>
        
        <!-- Bottom Left: Green (Comp: Tinggi, Comm: Tinggi) -->
        <div class="aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 transition-all duration-300"
             :class="(activeBox && activeBox !== 'BL') ? 'opacity-30 bg-[#78c87f] text-[#205724]' : 'opacity-100 z-10'"
             :style="activeBox === 'BL' 
                ? 'background: linear-gradient(135deg, #87d38e 0%, #78c87f 60%, #6abd72 100%); box-shadow: 3px -3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.4), inset 0 -1.5px 2px rgba(0,0,0,0.06); color: #144918;' 
                : 'background-color: #78c87f; color: #205724;'">
            <div class="relative z-10" :style="activeBox === 'BL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.5); font-weight: 800;' : ''">DIRECTING - HIGH</div>
            <div class="relative z-10" :style="activeBox === 'BL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.5); font-weight: 800;' : ''">MENTORING - HIGH</div>
            <div class="relative z-10" :style="activeBox === 'BL' ? 'text-shadow: 0 1px 1px rgba(255,255,255,0.5); font-weight: 800;' : ''">COACHING - LOW</div>
        </div>
        
        <!-- Bottom Right: Red/Coral (Comp: Tinggi, Comm: Rendah) -->
        <div class="aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 transition-all duration-300"
             :class="(activeBox && activeBox !== 'BR') ? 'opacity-30 bg-[#f05051] text-white' : 'opacity-100 z-10'"
             :style="activeBox === 'BR' 
                ? 'background: linear-gradient(135deg, #f75d5e 0%, #f05051 60%, #e84546 100%); box-shadow: -3px -3px 12px rgba(0,0,0,0.1), inset 0 1.5px 2px rgba(255,255,255,0.35), inset 0 -1.5px 2px rgba(0,0,0,0.08); color: #ffffff;' 
                : 'background-color: #f05051; color: #ffffff;'">
            <div class="relative z-10" :style="activeBox === 'BR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25); font-weight: 800;' : ''">DIRECTING - MODERATE</div>
            <div class="relative z-10" :style="activeBox === 'BR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25); font-weight: 800;' : ''">MENTORING - HIGH</div>
            <div class="relative z-10" :style="activeBox === 'BR' ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.25); font-weight: 800;' : ''">COACHING - MODERATE</div>
        </div>
    </div>
</div>

@push('modals')
<!-- Modal Add Via Character -->
<div id="addViaCharacterModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Add Via Character</h3>
            <button type="button" onclick="document.getElementById('addViaCharacterModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('employee-via-character.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id_karyawan" value="{{ $employee->id }}">
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

<!-- Modal Add Strength & Weakness -->
<div id="addStrengthModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Add Strength & Weakness</h3>
            <button type="button" onclick="document.getElementById('addStrengthModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('employee-strength.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="id_karyawan" value="{{ $employee->id }}">
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

<!-- Modals Edit Via Character -->
@foreach($employee->viaCharacters ?? [] as $via)
<div id="editViaCharacterModal{{ $via->id }}" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
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
            <input type="hidden" name="id_karyawan" value="{{ $employee->id }}">
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

<!-- Modals Edit Strength & Weakness -->
@foreach($employee->strengths ?? [] as $st)
<div id="editStrengthModal{{ $st->id }}" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
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
            <input type="hidden" name="id_karyawan" value="{{ $employee->id }}">
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

<!-- Modal Edit SCARF Profile -->
<div id="editScarfModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <!-- Mengubah max-w-md menjadi max-w-xs agar lebih kecil dan pas untuk input angka -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xs mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <!-- Header diperkecil paddingnya -->
        <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-base font-bold text-gray-900">Manage SCARF Profile</h3>
            <button type="button" onclick="document.getElementById('editScarfModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-md"></i>
            </button>
        </div>
        
        <form action="{{ route('employee-scarf.update', $employee->id) }}" method="POST" class="p-5">
            @csrf
            
            @php
                $scarf = $employee->scarf;
            @endphp
            
            <!-- Jarak antar elemen dirapatkan menjadi space-y-3 -->
            <div class="space-y-3 mb-5">
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Status (0-100%)</label>
                    <input type="number" step="any" name="status" min="0" max="100" value="{{ $scarf->status ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Certainty (0-100%)</label>
                    <input type="number" step="any" name="certainty" min="0" max="100" value="{{ $scarf->certainty ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Autonomy (0-100%)</label>
                    <input type="number" step="any" name="autonomy" min="0" max="100" value="{{ $scarf->autonomy ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Relatedness (0-100%)</label>
                    <input type="number" step="any" name="relatedness" min="0" max="100" value="{{ $scarf->relatedness ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Fairness (0-100%)</label>
                    <input type="number" step="any" name="fairness" min="0" max="100" value="{{ $scarf->fairness ?? 0 }}" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>

            <!-- Footer tombol -->
            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editScarfModal').classList.add('hidden')" class="px-4 py-1.5 text-[13px] font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-1.5 text-[13px] font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Save</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

