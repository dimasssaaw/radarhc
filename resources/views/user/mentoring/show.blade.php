@extends('layouts.app')
@section('title', 'Mentoring Details')
@section('content')
<!-- Header -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="{{ route('user-mentoring.index') }}" class="hover:text-gray-700 transition-colors">Mentoring Data</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            @if(isset($mentoring->id_karyawan))
                <a href="{{ route('user-mentoring.show-employee', $mentoring->id_karyawan) }}" class="hover:text-gray-700 transition-colors">Employee Mentoring</a>
                <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            @endif
            <span class="text-gray-900 font-medium">Detail Mentoring Plan</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ isset($mentoring->id_karyawan) ? route('user-mentoring.show-employee', $mentoring->id_karyawan) : route('user-mentoring.index') }}" class="bg-[#0ea5e9] hover:bg-[#0284c7] shadow-sm hover:shadow-md rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-white">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Employee Profile Header -->
@include('components.employee-profile-header', ['employee' => $mentoring->karyawan])

@php $canEdit = auth()->user()->can('edit_mentoring'); @endphp

<!-- Main Mentoring Grid layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pb-12">
    
    @if(!$canEdit)
    <!-- Top Row (Progress Tracking) for Non-Editors -->
    <div class="lg:col-span-12">
        <!-- Progress Tracking -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-tasks text-emerald-500"></i> Progress Tracking
                </h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Target</p>
                    <p class="font-medium text-gray-900">{{ $mentoring->target ? \Carbon\Carbon::parse($mentoring->target)->format('d M Y') : '-' }}</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Monitoring</p>
                    <p class="font-medium text-gray-900">{{ $mentoring->monitoring ? \Carbon\Carbon::parse($mentoring->monitoring)->format('d M Y') : '-' }}</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Final</p>
                    <p class="font-medium text-gray-900">{{ $mentoring->final ? \Carbon\Carbon::parse($mentoring->final)->format('d M Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Left Column (Session Details) -->
    <div class="lg:col-span-4 flex flex-col gap-6">
        <!-- Session Details Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-info-circle text-[#0ea5e9]"></i> Session Details
                </h3>
                @can('tambah_mentoring')
                <button @click="$dispatch('open-session-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="{{ $mentoring->id ? 'Edit Session Details' : 'Add Session Details' }}">
                    <i class="fas {{ $mentoring->id ? 'fa-pen' : 'fa-plus' }}"></i>
                </button>
                @endcan
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Year</span>
                    <span class="font-semibold text-gray-900 text-sm">{{ $mentoring->tahun ?? '-' }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Period</span>
                    <span class="font-semibold text-gray-900 text-sm">{{ $mentoring->periode ?? '-' }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Project</span>
                    <span class="font-semibold text-gray-900 text-sm text-right max-w-[150px] truncate" title="{{ $mentoring->project }}">{{ $mentoring->project ?? '-' }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Status</span>
                    @if(empty($mentoring->status_mentoring))
                        <span class="font-semibold text-gray-900 text-sm">-</span>
                    @else
                        @php
                            $statusClasses = [
                                'completed' => 'bg-green-100 text-green-700',
                                'in_progress' => 'bg-blue-100 text-blue-700'
                            ];
                            $sClass = $statusClasses[$mentoring->status_mentoring] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $sClass }}">
                            {{ ucfirst(str_replace('_', ' ', $mentoring->status_mentoring)) }}
                        </span>
                    @endif
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Mentor</span>
                    <span class="font-semibold text-gray-900 text-sm">{{ $mentoring->mentor ?? '-' }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Development Area</span>
                    <span class="font-semibold text-gray-900 text-sm text-right max-w-[150px] truncate" title="{{ $mentoring->development }}">{{ $mentoring->development ?? '-' }}</span>
                </div>
            </div>
        </div>

        @can('edit_mentoring')
        <!-- Evaluation Metrics Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200"
            x-data="{ 
                mentoringId: '{{ $mentoring->id ?? '' }}',
                employeeId: '{{ $mentoring->karyawan->id ?? '' }}',
                competency: '{{ $mentoring->competency ?? '' }}', 
                commitment: '{{ $mentoring->commitment ?? '' }}',
                updateMetric(type, val) {
                    if (type === 'competency') this.competency = val;
                    if (type === 'commitment') this.commitment = val;
                    
                    let url = this.mentoringId 
                        ? '{{ url("mentoring") }}/' + this.mentoringId + '/metrics'
                        : '{{ url("mentoring/employee") }}/' + this.employeeId + '/metrics';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            competency: this.competency,
                            commitment: this.commitment
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('Metrics updated', data);
                        if (data.mentoring && data.mentoring.id) {
                            this.mentoringId = data.mentoring.id;
                        }
                    })
                    .catch(err => console.error(err));
                }
            }">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-bar text-emerald-800"></i> Evaluation Metrics
            </h3>
            
            <!-- Competency -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-800">Competency</span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold"
                        :class="competency === 'Tinggi' ? 'bg-emerald-100 text-emerald-800' : (competency === 'Rendah' ? 'bg-gray-200 text-gray-700' : 'bg-gray-100 text-gray-500')"
                        x-text="competency === 'Tinggi' ? 'High' : (competency === 'Rendah' ? 'Low' : (competency || '-'))"></span>
                </div>
                <div class="flex rounded-lg bg-gray-50 border border-gray-200 p-1">
                    @can('edit_mentoring')
                    <button @click="updateMetric('competency', 'Rendah')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="competency === 'Rendah' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        Low
                    </button>
                    @endcan
                    @can('edit_mentoring')
                    <button @click="updateMetric('competency', 'Tinggi')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="competency === 'Tinggi' ? 'bg-white shadow text-emerald-700' : 'text-gray-500 hover:text-gray-700'">
                        High
                    </button>
                    @endcan
                </div>
            </div>
            
            <!-- Commitment -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-800">Commitment</span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold"
                        :class="commitment === 'Tinggi' ? 'bg-amber-100 text-amber-800' : (commitment === 'Rendah' ? 'bg-gray-200 text-gray-700' : 'bg-gray-100 text-gray-500')"
                        x-text="commitment === 'Tinggi' ? 'High' : (commitment === 'Rendah' ? 'Low' : (commitment || '-'))"></span>
                </div>
                <div class="flex rounded-lg bg-gray-50 border border-gray-200 p-1">
                    @can('edit_mentoring')
                    <button @click="updateMetric('commitment', 'Rendah')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="commitment === 'Rendah' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        Low
                    </button>
                    @endcan
                    @can('edit_mentoring')
                    <button @click="updateMetric('commitment', 'Tinggi')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="commitment === 'Tinggi' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        High
                    </button>
                    @endcan
                </div>
            </div>
        </div>
        @endcan
    </div>
    
    <!-- Right Column (GROW Model) -->
    <div class="lg:col-span-8 flex flex-col gap-6">
        @if($canEdit)
        <!-- Progress Tracking -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-tasks text-emerald-500"></i> Progress Tracking
                </h3>
                <button @click="$dispatch('open-progress-modal')" class="text-emerald-500 hover:bg-emerald-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="{{ $mentoring->id ? 'Edit Progress Tracking' : 'Add Progress Tracking' }}">
                    <i class="fas {{ $mentoring->id ? 'fa-pen' : 'fa-plus' }}"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Target</p>
                    <p class="font-medium text-gray-900">{{ $mentoring->target ? \Carbon\Carbon::parse($mentoring->target)->format('d M Y') : '-' }}</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Monitoring</p>
                    <p class="font-medium text-gray-900">{{ $mentoring->monitoring ? \Carbon\Carbon::parse($mentoring->monitoring)->format('d M Y') : '-' }}</p>
                </div>
                
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Final</p>
                    <p class="font-medium text-gray-900">{{ $mentoring->final ? \Carbon\Carbon::parse($mentoring->final)->format('d M Y') : '-' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- GROW Model -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            <div class="bg-[#fbbf24] text-gray-900 font-bold text-center py-3 rounded-lg mb-6 shadow-sm flex items-center justify-center relative">
                GROW MODEL
            </div>
            
            <div class="space-y-3">
                <!-- GOALS -->
                @php
                    $actionUrl = $mentoring->id 
                        ? route('mentoring.update-grow', $mentoring->id) 
                        : route('mentoring.update-employee-grow', $mentoring->karyawan->id ?? 0);
                @endphp
                <div x-data='{ editing: false, value: {{ json_encode($mentoring->grow_goals ?? "") }}, original: {{ json_encode($mentoring->grow_goals ?? "") }} }' class="flex flex-col md:flex-row bg-[#d1fae5] rounded-lg overflow-hidden border border-[#a7f3d0] group">
                    <div class="bg-[#059669] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                        GOALS
                    </div>
                    <div x-show="!editing" @can('edit_mentoring') @click="editing = true; $nextTick(() => $refs.input.focus());" @endcan class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                        <span x-text="value || 'What do you want?'" :class="!value ? 'text-gray-400 italic' : ''"></span>
                        <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#059669] transition-opacity flex items-center gap-1 text-xs font-semibold">
                            <i class="fas fa-edit"></i> Edit
                        </div>
                    </div>
                    @can('edit_mentoring')
                    <form x-show="editing" style="display: none;" action="{{ $actionUrl }}" method="POST" class="p-3 flex-1 flex flex-col gap-2 bg-white/60">
                        @csrf
                        <textarea name="grow_goals" x-model="value" x-ref="input" rows="2" class="w-full rounded border-gray-300 focus:border-[#059669] focus:ring focus:ring-[#059669]/20 text-sm p-2" placeholder="What do you want?"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="editing = false; value = original;" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white hover:bg-gray-100 border border-gray-200 rounded transition-colors shadow-sm">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#059669] hover:bg-[#047857] rounded shadow-sm transition-colors">Save</button>
                        </div>
                    </form>
                    @endcan
                </div>
                
                <!-- REALITAS -->
                <div x-data='{ editing: false, value: {{ json_encode($mentoring->grow_realitas ?? "") }}, original: {{ json_encode($mentoring->grow_realitas ?? "") }} }' class="flex flex-col md:flex-row bg-[#dbeafe] rounded-lg overflow-hidden border border-[#bfdbfe] group">
                    <div class="bg-[#3b82f6] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                        REALITAS
                    </div>
                    <div x-show="!editing" @can('edit_mentoring') @click="editing = true; $nextTick(() => $refs.input.focus());" @endcan class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                        <span x-text="value || 'Where are you now?'" :class="!value ? 'text-gray-400 italic' : ''"></span>
                        <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#3b82f6] transition-opacity flex items-center gap-1 text-xs font-semibold">
                            <i class="fas fa-edit"></i> Edit
                        </div>
                    </div>
                    @can('edit_mentoring')
                    <form x-show="editing" style="display: none;" action="{{ $actionUrl }}" method="POST" class="p-3 flex-1 flex flex-col gap-2 bg-white/60">
                        @csrf
                        <textarea name="grow_realitas" x-model="value" x-ref="input" rows="2" class="w-full rounded border-gray-300 focus:border-[#3b82f6] focus:ring focus:ring-[#3b82f6]/20 text-sm p-2" placeholder="Where are you now?"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="editing = false; value = original;" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white hover:bg-gray-100 border border-gray-200 rounded transition-colors shadow-sm">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#3b82f6] hover:bg-[#2563eb] rounded shadow-sm transition-colors">Save</button>
                        </div>
                    </form>
                    @endcan
                </div>
                
                <!-- OPTIONS -->
                <div x-data='{ editing: false, value: {{ json_encode($mentoring->grow_options ?? "") }}, original: {{ json_encode($mentoring->grow_options ?? "") }} }' class="flex flex-col md:flex-row bg-[#ede9fe] rounded-lg overflow-hidden border border-[#ddd6fe] group">
                    <div class="bg-[#8b5cf6] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                        OPTIONS
                    </div>
                    <div x-show="!editing" @can('edit_mentoring') @click="editing = true; $nextTick(() => $refs.input.focus());" @endcan class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                        <span x-text="value || 'What could you do?'" :class="!value ? 'text-gray-400 italic' : ''"></span>
                        <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#8b5cf6] transition-opacity flex items-center gap-1 text-xs font-semibold">
                            <i class="fas fa-edit"></i> Edit
                        </div>
                    </div>
                    @can('edit_mentoring')
                    <form x-show="editing" style="display: none;" action="{{ $actionUrl }}" method="POST" class="p-3 flex-1 flex flex-col gap-2 bg-white/60">
                        @csrf
                        <textarea name="grow_options" x-model="value" x-ref="input" rows="2" class="w-full rounded border-gray-300 focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 text-sm p-2" placeholder="What could you do?"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="editing = false; value = original;" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white hover:bg-gray-100 border border-gray-200 rounded transition-colors shadow-sm">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#8b5cf6] hover:bg-[#7c3aed] rounded shadow-sm transition-colors">Save</button>
                        </div>
                    </form>
                    @endcan
                </div>
                
                <!-- WRAP UP -->
                <div x-data='{ editing: false, value: {{ json_encode($mentoring->grow_wrap_up ?? "") }}, original: {{ json_encode($mentoring->grow_wrap_up ?? "") }} }' class="flex flex-col md:flex-row bg-[#fee2e2] rounded-lg overflow-hidden border border-[#fecaca] group">
                    <div class="bg-[#ef4444] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                        WRAP UP
                    </div>
                    <div x-show="!editing" @can('edit_mentoring') @click="editing = true; $nextTick(() => $refs.input.focus());" @endcan class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                        <span x-text="value || 'What will you do?'" :class="!value ? 'text-gray-400 italic' : ''"></span>
                        <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#ef4444] transition-opacity flex items-center gap-1 text-xs font-semibold">
                            <i class="fas fa-edit"></i> Edit
                        </div>
                    </div>
                    @can('edit_mentoring')
                    <form x-show="editing" style="display: none;" action="{{ $actionUrl }}" method="POST" class="p-3 flex-1 flex flex-col gap-2 bg-white/60">
                        @csrf
                        <textarea name="grow_wrap_up" x-model="value" x-ref="input" rows="2" class="w-full rounded border-gray-300 focus:border-[#ef4444] focus:ring focus:ring-[#ef4444]/20 text-sm p-2" placeholder="What will you do?"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="editing = false; value = original;" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white hover:bg-gray-100 border border-gray-200 rounded transition-colors shadow-sm">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#ef4444] hover:bg-[#dc2626] rounded shadow-sm transition-colors">Save</button>
                        </div>
                    </form>
                    @endcan
                </div>
                
                <!-- INSIGHT -->
                <div x-data='{ editing: false, value: {{ json_encode($mentoring->grow_insight ?? "") }}, original: {{ json_encode($mentoring->grow_insight ?? "") }} }' class="flex flex-col md:flex-row bg-[#ffedd5] rounded-lg overflow-hidden border border-[#fed7aa] group">
                    <div class="bg-[#f97316] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                        INSIGHT
                    </div>
                    <div x-show="!editing" @can('edit_mentoring') @click="editing = true; $nextTick(() => $refs.input.focus());" @endcan class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                        <span x-text="value || 'Key takeaways and learning insights...'" :class="!value ? 'text-gray-400 italic' : ''"></span>
                        <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#f97316] transition-opacity flex items-center gap-1 text-xs font-semibold">
                            <i class="fas fa-edit"></i> Edit
                        </div>
                    </div>
                    @can('edit_mentoring')
                    <form x-show="editing" style="display: none;" action="{{ $actionUrl }}" method="POST" class="p-3 flex-1 flex flex-col gap-2 bg-white/60">
                        @csrf
                        <textarea name="grow_insight" x-model="value" x-ref="input" rows="2" class="w-full rounded border-gray-300 focus:border-[#f97316] focus:ring focus:ring-[#f97316]/20 text-sm p-2" placeholder="Key takeaways and learning insights..."></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="editing = false; value = original;" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white hover:bg-gray-100 border border-gray-200 rounded transition-colors shadow-sm">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#f97316] hover:bg-[#ea580c] rounded shadow-sm transition-colors">Save</button>
                        </div>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit GROW Model (Alpine.js Animated) -->
@push('modals')
<div x-data="{ show: false }" 
     @open-grow-modal.window="show = true" 
     x-show="show" 
     style="display: none;" 
     class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto">
     
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 transition-opacity" 
         @click="show = false"></div>

    <!-- Modal Card -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 z-10 p-4 relative transform transition-all flex flex-col max-h-[75vh] md:max-h-[500px]">
         
        <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2 shrink-0">
            <i class="fas fa-edit text-[#fbbf24]"></i> Edit GROW Model
        </h3>
        
        @php
            $actionUrl = $mentoring->id 
                ? route('mentoring.update-grow', $mentoring->id) 
                : route('mentoring.update-employee-grow', $mentoring->karyawan->id ?? 0);
        @endphp
        
        <form action="{{ $actionUrl }}" method="POST" class="flex flex-col overflow-hidden">
            @csrf
            
            <div class="space-y-3 overflow-y-auto pr-2 pb-2 grow custom-scrollbar">
                <!-- GOALS -->
                <div class="bg-[#d1fae5] border border-[#a7f3d0] rounded-lg px-2.5 py-1.5">
                    <label class="block text-[10px] font-bold text-[#059669] mb-0.5">GOALS (What do you want?)</label>
                    <textarea name="grow_goals" rows="2" class="w-full border-0 bg-white/80 rounded shadow-sm focus:ring-2 focus:ring-[#059669] text-sm text-gray-800 p-1.5 resize-y min-h-[44px]">{{ $mentoring->grow_goals }}</textarea>
                </div>
                
                <!-- REALITAS -->
                <div class="bg-[#dbeafe] border border-[#bfdbfe] rounded-lg px-2.5 py-1.5">
                    <label class="block text-[10px] font-bold text-[#2563eb] mb-0.5">REALITAS (Where are you now?)</label>
                    <textarea name="grow_realitas" rows="2" class="w-full border-0 bg-white/80 rounded shadow-sm focus:ring-2 focus:ring-[#2563eb] text-sm text-gray-800 p-1.5 resize-y min-h-[44px]">{{ $mentoring->grow_realitas }}</textarea>
                </div>
                
                <!-- OPTIONS -->
                <div class="bg-[#ede9fe] border border-[#ddd6fe] rounded-lg px-2.5 py-1.5"> 
                    <label class="block text-[10px] font-bold text-[#7c3aed] mb-0.5">OPTIONS (What could you do?)</label>
                    <textarea name="grow_options" rows="2" class="w-full border-0 bg-white/80 rounded shadow-sm focus:ring-2 focus:ring-[#7c3aed] text-sm text-gray-800 p-1.5 resize-y min-h-[44px]">{{ $mentoring->grow_options }}</textarea>
                </div>
                
                <!-- WRAP UP -->
                <div class="bg-[#fee2e2] border border-[#fecaca] rounded-lg px-2.5 py-1.5">
                    <label class="block text-[10px] font-bold text-[#dc2626] mb-0.5">WRAP UP (What will you do?)</label>
                    <textarea name="grow_wrap_up" rows="2" class="w-full border-0 bg-white/80 rounded shadow-sm focus:ring-2 focus:ring-[#dc2626] text-sm text-gray-800 p-1.5 resize-y min-h-[44px]">{{ $mentoring->grow_wrap_up }}</textarea>
                </div>
                
                <!-- INSIGHT -->
                <div class="bg-[#ffedd5] border border-[#fed7aa] rounded-lg px-2.5 py-1.5">
                    <label class="block text-[10px] font-bold text-[#ea580c] mb-0.5">INSIGHT (Key takeaways)</label>
                    <textarea name="grow_insight" rows="2" class="w-full border-0 bg-white/80 rounded shadow-sm focus:ring-2 focus:ring-[#ea580c] text-sm text-gray-800 p-1.5 resize-y min-h-[44px]">{{ $mentoring->grow_insight }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-100 shrink-0">
                <button type="button" @click="show = false" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-1.5 bg-[#fbbf24] hover:bg-[#f59e0b] text-gray-900 rounded-lg text-sm font-bold shadow-sm transition-colors">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endpush

</div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; 
    }
</style>

@push('modals')
<!-- Session Details Modal -->
<div x-data="{ open: false }" @open-session-modal.window="open = true" class="relative z-[100]">
    <!-- Latar Belakang Hitam Transparan (Tanpa Blur) -->
    <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="open" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0">
        <div @click.away="open = false" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto max-h-[90vh]">
                <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Session Details</h3>
                    </div>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ !empty($mentoring->id) ? route('mentoring.update-info', $mentoring->id) : route('mentoring.update-employee-info', $mentoring->karyawan->id) }}" method="POST" class="flex flex-col overflow-hidden">
                    @csrf
                    
                    <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                        <!-- Year -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Year</label>
                            <input type="number" name="tahun" value="{{ $mentoring->tahun ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                        </div>
                        
                        <!-- Period -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Period</label>
                            <input type="text" name="periode" value="{{ $mentoring->periode ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                        </div>
                        
                        <!-- Project -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Project</label>
                            <input type="text" name="project" value="{{ $mentoring->project ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border" placeholder="Project Name">
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Status</label>
                            <select name="status_mentoring" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                                <option value="" {{ empty($mentoring->status_mentoring) ? 'selected' : '' }}>-</option>
                                <option value="in_progress" {{ in_array($mentoring->status_mentoring ?? '', ['in_progress', 'pending']) ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ ($mentoring->status_mentoring ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <!-- Mentor -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Mentor</label>
                            <input type="text" name="mentor" value="{{ $mentoring->mentor ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                        </div>
                        
                        <!-- Development Area -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Development Area</label>
                            <textarea name="development" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border resize-y">{{ $mentoring->development ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                        <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-sky-500 text-sm font-medium text-white hover:bg-sky-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5"><i class="fas fa-save text-xs"></i> Save</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<!-- Progress Tracking Modal -->
<div x-data="{ open: false }" @open-progress-modal.window="open = true" class="relative z-[100]">
    <!-- Latar Belakang Hitam Transparan (Tanpa Blur) -->
    <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="open" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0">
        <div @click.away="open = false" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto max-h-[90vh]">
            
            <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Progress Tracking</h3>
                </div>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ !empty($mentoring->id) ? route('mentoring.update-progress', $mentoring->id) : route('mentoring.update-employee-progress', $mentoring->karyawan->id) }}" method="POST" class="flex flex-col">
                @csrf
                
                <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <!-- Target Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Target Setting</label>
                        <input type="date" name="target" value="{{ $mentoring->target ? \Carbon\Carbon::parse($mentoring->target)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Monitoring Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Monitoring</label>
                        <input type="date" name="monitoring" value="{{ $mentoring->monitoring ? \Carbon\Carbon::parse($mentoring->monitoring)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Final Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Final Review</label>
                        <input type="date" name="final" value="{{ $mentoring->final ? \Carbon\Carbon::parse($mentoring->final)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                    <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-sky-500 text-sm font-medium text-white hover:bg-sky-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5"><i class="fas fa-save text-xs"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@endsection

