@extends('layouts.app')
@section('title', 'Training Details')

@section('content')
    <div x-data="{
        showFormModal: {{ $errors->any() ? 'true' : 'false' }},
        showUploadModal: false,
        uploadEva: '',
        uploadId: null,
        formTitle: 'Add Training',
        formAction: '{{ route('training.store-history', $employee->id) }}',
        formMethod: 'POST',
        activeEvaTab: 'eva1',
        formData: { 
            id: '', 
            kategori: '', 
            nama: '', 
            tanggal: '', 
            eva1_status: '', 
            eva2_status: '', 
            eva3_status: '', 
            eva1_lampiran: '',
            eva2_lampiran: '',
            eva3_lampiran: ''
        },
        formErrors: {},
        isSubmitting: false,

        getOriginalFileName(path) {
            if (!path) return '';
            let name = path.split('/').pop();
            let underscoreIndex = name.indexOf('_');
            return underscoreIndex !== -1 ? name.substring(underscoreIndex + 1) : name;
        },

        async submitForm(formElement) {
            this.isSubmitting = true;
            try {
                const fd = new FormData(formElement);
                const response = await fetch(this.formAction, {
                    method: 'POST',
                    body: fd,
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest', 
                        'Accept': 'application/json' 
                    }
                });

                if (response.ok || response.redirected) {
                    window.location.reload();
                } else if (response.status === 422) {
                    const data = await response.json();
                    this.formErrors = data.errors;
                } else {
                    alert('A server error occurred.');
                }
            } catch (err) {
                alert('Failed to connect to the server.');
            } finally {
                this.isSubmitting = false;
            }
        },

        openModal(title, kategori, data = null) {
            this.formTitle = title;
            this.formData.kategori = kategori;
            this.formErrors = {};

            if (data) {
                this.formAction = '{{ url('training/history') }}/' + data.id;
                this.formMethod = 'PUT';
                this.formData.id = data.id;
                this.formData.nama = data.nama_training;
                this.formData.tanggal = data.tanggal_training ? data.tanggal_training.split(' ')[0] : '';
                this.formData.eva1_status = data.eva1_status || '';
                this.formData.eva2_status = data.eva2_status || '';
                this.formData.eva3_status = data.eva3_status || '';
                this.formData.eva1_lampiran = data.eva1_lampiran || '';
                this.formData.eva2_lampiran = data.eva2_lampiran || '';
                this.formData.eva3_lampiran = data.eva3_lampiran || '';
            } else {
                this.formAction = '{{ route('training.store-history', $employee->id) }}';
                this.formMethod = 'POST';
                this.formData.id = null;
                this.formData.nama = '';
                this.formData.tanggal = '';
                this.formData.eva1_status = '';
                this.formData.eva2_status = '';
                this.formData.eva3_status = '';
                this.formData.eva1_lampiran = '';
                this.formData.eva2_lampiran = '';
                this.formData.eva3_lampiran = '';
            }
            this.activeEvaTab = 'eva1';
            this.showFormModal = true;
        },

        openUploadModal(eva, id) {
            this.uploadEva = eva;
            this.uploadId = id;
            this.showUploadModal = true;
        }
    }">
        <div class="min-h-full -mx-8 -my-8 bg-white p-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
                        <a href="{{ route('training.index') }}" class="hover:text-gray-700 transition-colors">Training</a>
                        <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
                        <span class="text-gray-900 font-medium">Training Details</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('training.index') }}"
                        class="bg-white shadow-sm border border-gray-100 rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all hover:bg-gray-50 text-gray-700">
                        <i class="fas fa-arrow-left text-gray-400"></i> Back
                    </a>
                </div>
            </div>

            <!-- Employee Profile Header -->
            @include('components.employee-profile-header', ['employee' => $employee])

            <!-- 2. Grid Atas: Progress & Evaluation -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
                <!-- Bagian Kiri: Training Progress -->
                <div class="lg:col-span-5 bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]">
                    <h3 class="text-sm font-bold text-gray-900 mb-6">Training Progress</h3>

                    <div class="space-y-6">
                        <!-- Mandatory -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Mandatory</span>
                                <span class="text-sm font-bold text-[#22c55e]">{{ $achieveMandatory }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-[#22c55e] h-3 rounded-full transition-all duration-[450ms]"
                                    style="width: {{ $achieveMandatory }}%"></div>
                            </div>
                        </div>

                        <!-- Skill -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Skill</span>
                                <span class="text-sm font-bold text-[#0ea5e9]">{{ $achieveSkill }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-[#0ea5e9] h-3 rounded-full transition-all duration-[450ms]"
                                    style="width: {{ $achieveSkill }}%"></div>
                            </div>
                        </div>

                        <!-- Managerial -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Managerial</span>
                                <span class="text-sm font-bold text-[#f43f5e]">{{ $achieveManagerial }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-[#f43f5e] h-3 rounded-full transition-all duration-[450ms]"
                                    style="width: {{ $achieveManagerial }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Kanan: Training Evaluation -->
                <div class="lg:col-span-7 bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]"
                    x-data="{ showEditTarget: false }">
                    <form id="training-target-form" action="{{ route('training.update-target', $employee->id) }}"
                        method="POST" @submit.prevent>
                        @csrf
                    </form>

                    <div class="p-4 flex justify-between items-center border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Training Evaluation</h3>
                        @can('edit_training')
                            <button @click="if (!showEditTarget) { showEditTarget = true; $event.preventDefault(); }"
                                :type="showEditTarget ? 'submit' : 'button'" form="training-target-form"
                                class="bg-[#06b6d4] hover:bg-[#0891b2] text-white px-4 py-1.5 rounded text-xs font-semibold flex items-center gap-1.5 transition-colors">
                                <i class="fas" :class="showEditTarget ? 'fa-save' : 'fa-edit'"></i>
                                <span x-text="showEditTarget ? 'Save' : 'Edit Data'"></span>
                            </button>
                        @endcan
                    </div>

                    <div class="overflow-x-auto p-4 pt-0">
                        <table class="w-full text-center text-sm border-collapse mt-4 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <thead class="bg-gray-900 text-white text-[11px] uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 text-left">Category</th>
                                    <th class="px-4 py-3">Target</th>
                                    <th class="px-4 py-3">Completed</th>
                                    <th class="px-4 py-3">Achievement</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr>
                                    <td class="px-4 py-3 text-left font-semibold text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-[#22c55e]"></div> Mandatory
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span x-show="!showEditTarget">{{ $training->target_mandatory }}</span>
                                        <input x-show="showEditTarget" type="number" name="target_mandatory"
                                            form="training-target-form" value="{{ $training->target_mandatory }}"
                                            min="0"
                                            class="w-20 mx-auto rounded border-gray-300 px-2 py-1 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $completedMandatory }}</td>
                                    <td class="px-4 py-3 font-bold text-[#22c55e]">{{ $achieveMandatory }}%</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-left font-semibold text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-[#0ea5e9]"></div> Skill
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span x-show="!showEditTarget">{{ $training->target_skill }}</span>
                                        <input x-show="showEditTarget" type="number" name="target_skill"
                                            form="training-target-form" value="{{ $training->target_skill }}"
                                            min="0"
                                            class="w-20 mx-auto rounded border-gray-300 px-2 py-1 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $completedSkill }}</td>
                                    <td class="px-4 py-3 font-bold text-[#0ea5e9]">{{ $achieveSkill }}%</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-left font-semibold text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-[#f43f5e]"></div> Managerial
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span x-show="!showEditTarget">{{ $training->target_managerial }}</span>
                                        <input x-show="showEditTarget" type="number" name="target_managerial"
                                            form="training-target-form" value="{{ $training->target_managerial }}"
                                            min="0"
                                            class="w-20 mx-auto rounded border-gray-300 px-2 py-1 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $completedManagerial }}</td>
                                    <td class="px-4 py-3 font-bold text-[#f43f5e]">{{ $achieveManagerial }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Macro Helper untuk Tabel Training -->
            @php
                $sections = [
                    [
                        'title' => 'Mandatory Training',
                        'kategori' => 'mandatory',
                        'color' => '#22c55e',
                        'hover_bg' => 'hover:bg-green-50',
                        'items' => $riwayatMandatory,
                        'key_prefix' => 'rm'
                    ],
                    [
                        'title' => 'Skill Training',
                        'kategori' => 'skill',
                        'color' => '#0ea5e9',
                        'hover_bg' => 'hover:bg-sky-50',
                        'items' => $riwayatSkill,
                        'key_prefix' => 'rs'
                    ],
                    [
                        'title' => 'Managerial Training',
                        'kategori' => 'managerial',
                        'color' => '#f43f5e',
                        'hover_bg' => 'hover:bg-rose-50',
                        'items' => $riwayatManagerial,
                        'key_prefix' => 'rmn'
                    ]
                ];
            @endphp

            @foreach ($sections as $section)
                <!-- Tabel {{ $section['title'] }} -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-gray-900">{{ $section['title'] }}</h3>
                        @can('tambah_training')
                            <button @click="openModal('Add {{ $section['title'] }}', '{{ $section['kategori'] }}')"
                                class="bg-white border text-[{{ $section['color'] }}] border-[{{ $section['color'] }}] {{ $section['hover_bg'] }} px-3 py-1.5 rounded text-xs font-semibold flex items-center gap-1.5 transition-colors">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        @endcan
                    </div>

                    <div class="overflow-visible rounded-lg border border-gray-200">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead class="text-white text-[11px] uppercase tracking-wider bg-[{{ $section['color'] }}]">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Training Name</th>
                                    <th class="px-4 py-3 font-semibold text-center">Status</th>
                                    <th class="px-4 py-3 font-semibold text-center">Date</th>
                                    <th class="px-4 py-3 font-semibold text-center">Evaluation</th>
                                    <th class="px-4 py-3 font-semibold text-center">Attachment</th>
                                    <th class="px-4 py-3 font-semibold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($section['items'] as $item)
                                    <tr class="hover:bg-gray-50" 
                                        x-data="{ selectedEva: sessionStorage.getItem('selectedEva_{{ $section['key_prefix'] }}_{{ $item->id }}') || 'eva1' }" 
                                        x-init="$watch('selectedEva', val => sessionStorage.setItem('selectedEva_{{ $section['key_prefix'] }}_{{ $item->id }}', val))">
                                        
                                        <td class="px-4 py-4 text-gray-800 font-medium">{{ $item->nama_training }}</td>
                                        
                                        <!-- Status Evaluasi -->
                                        <td class="px-4 py-4 text-center">
                                            @foreach (['eva1', 'eva2', 'eva3'] as $eva)
                                                @php $statusField = "{$eva}_status"; @endphp
                                                <div x-show="selectedEva === '{{ $eva }}'" @if(!$loop->first) style="display: none;" @endif>
                                                    <form action="{{ route('training.update-status', $item->id) }}" method="POST" @submit.prevent>
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="relative inline-block w-full min-w-[110px]">
                                                            <select name="{{ $statusField }}" 
                                                                @change="fetch($el.form.action, { 
                                                                    method: 'POST', 
                                                                    body: new FormData($el.form), 
                                                                    headers: {'X-Requested-With': 'XMLHttpRequest'} 
                                                                }).then(r => r.text()).then(h => { 
                                                                    let doc = new DOMParser().parseFromString(h, 'text/html'); 
                                                                    let target = document.querySelector('.min-h-full'); 
                                                                    target.innerHTML = doc.querySelector('.min-h-full').innerHTML; 
                                                                    setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); 
                                                                })"
                                                                class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[{{ $section['color'] }}] focus:border-[{{ $section['color'] }}] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                                <option value="" disabled @selected(empty($item->$statusField) || $item->$statusField === 'pending')>-- Select Status --</option>
                                                                <option value="completed" @selected($item->$statusField === 'completed')>Completed</option>
                                                                <option value="in_progress" @selected($item->$statusField === 'in_progress')>In Progress</option>
                                                                <option value="remedial" @selected($item->$statusField === 'remedial')>Remedial</option>
                                                                <option value="not_started" @selected($item->$statusField === 'not_started')>Not Started</option>
                                                            </select>
                                                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                                <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="px-4 py-4 text-center text-gray-600">
                                            {{ \Carbon\Carbon::parse($item->tanggal_training)->format('d M Y') }}
                                        </td>

                                        <!-- Dropdown Pemilih Evaluasi -->
                                        <td class="px-4 py-4 text-center">
                                            <div class="relative inline-block w-full min-w-[100px]">
                                                <select x-model="selectedEva"
                                                    class="w-full relative z-20 text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[{{ $section['color'] }}] focus:border-[{{ $section['color'] }}] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                    <option value="eva1">EVA 1 {{ $item->eva1_lampiran ? '✓' : '(empty)' }}</option>
                                                    <option value="eva2">EVA 2 {{ $item->eva2_lampiran ? '✓' : '(empty)' }}</option>
                                                    <option value="eva3">EVA 3 {{ $item->eva3_lampiran ? '✓' : '(empty)' }}</option>
                                                </select>
                                            </div>
                                        </td>

                                        <!-- Lampiran -->
                                        <td class="px-4 py-4 text-center">
                                            @foreach (['eva1', 'eva2', 'eva3'] as $eva)
                                                @php $lampiranField = "{$eva}_lampiran"; @endphp
                                                <div x-show="selectedEva === '{{ $eva }}'" @if(!$loop->first) style="display: none;" @endif>
                                                    @if ($item->$lampiranField)
                                                        <a href="{{ Storage::disk('hrg')->url($item->$lampiranField) }}" target="_blank"
                                                            class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1">
                                                            <i class="fas fa-file-pdf text-red-500"></i> {{ strtoupper($eva) }} Attachment
                                                        </a>
                                                    @else
                                                        <button type="button"
                                                            @click="openUploadModal('{{ $eva }}', {{ $item->id }})"
                                                            class="text-xs border border-[{{ $section['color'] }}] text-[{{ $section['color'] }}] px-2 py-1 rounded {{ $section['hover_bg'] }}">
                                                            + Attachment
                                                        </button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>

                                        <!-- Aksi -->
                                        <td class="px-4 py-4 text-center align-middle">
                                            <div class="flex items-center justify-center gap-3">
                                                @can('edit_training')
                                                    <button type="button"
                                                        @click="openModal('Edit {{ $section['title'] }}', '{{ $section['kategori'] }}', @js($item))"
                                                        class="text-gray-400 hover:text-gray-700 transition-colors"
                                                        title="Edit training">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                @endcan

                                                @can('hapus_training')
                                                    <form action="{{ route('training.destroy-history', $item->id) }}"
                                                        method="POST" hx-boost="false"
                                                        onsubmit="return confirm('Are you sure you want to delete this history?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-gray-400 hover:text-red-500 transition-colors"
                                                            title="Delete training">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                            No data available {{ $section['title'] }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <!-- Training Form Modal -->
            <template x-teleport="body">
                <div x-show="showFormModal" style="display: none;" 
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 overflow-y-auto"
                    @click.self="showFormModal = false"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    
                    <div x-show="showFormModal" 
                        @click.stop
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden font-sans text-left w-full sm:max-w-md my-auto">

                        <form @submit.prevent="submitForm($el)" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" :value="formMethod">
                            <input type="hidden" name="kategori" :value="formData.kategori">

                            <!-- Modal Header -->
                            <div class="px-5 py-2.5 flex justify-between items-center border-b border-gray-100">
                                <h2 class="text-[15px] font-bold text-gray-800" x-text="formTitle">Add Training</h2>
                                <button @click="showFormModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-600 p-1 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="px-5 py-4 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                                <!-- Form Training Input -->
                                <div class="space-y-3.5">
                                    <!-- Training Name -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Training Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_training" x-model="formData.nama"
                                            placeholder="e.g. Advanced Negotiation Skills"
                                            :class="formErrors.nama_training ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:border-[#0ea5e9] focus:ring-[#0ea5e9]'"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white placeholder-gray-400 font-semibold">
                                        <p x-show="formErrors.nama_training" class="text-red-500 text-xs mt-1" 
                                            x-text="formErrors.nama_training ? formErrors.nama_training[0] : ''" style="display: none;"></p>
                                    </div>

                                    <!-- Date -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date</label>
                                        <div class="relative">
                                            <input type="date" name="tanggal_training"
                                                x-model="formData.tanggal"
                                                :class="formErrors.tanggal_training ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:border-[#0ea5e9] focus:ring-[#0ea5e9]'"
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white font-semibold">
                                        </div>
                                        <p x-show="formErrors.tanggal_training" class="text-red-500 text-xs mt-1" 
                                            x-text="formErrors.tanggal_training ? formErrors.tanggal_training[0] : ''" style="display: none;"></p>
                                    </div>
                                </div>

                                <hr class="border-gray-100">

                                <!-- Evaluation Tabs -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Evaluation</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach (['eva1' => 'EVA 1', 'eva2' => 'EVA 2', 'eva3' => 'EVA 3'] as $tabKey => $tabLabel)
                                            <button type="button" @click="activeEvaTab = '{{ $tabKey }}'"
                                                :class="activeEvaTab === '{{ $tabKey }}' ?
                                                    'bg-sky-50 text-sky-600 border-sky-300' :
                                                    'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'"
                                                class="rounded-lg border py-1.5 text-[12.5px] font-bold transition-colors">
                                                {{ $tabLabel }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Tab Contents -->
                                @foreach (['eva1', 'eva2', 'eva3'] as $tab)
                                    <div x-show="activeEvaTab === '{{ $tab }}'" @if(!$loop->first) style="display: none;" @endif
                                        class="bg-[#f7f9f8] border border-gray-100 rounded-xl p-4 mt-1">
                                        <h3 class="text-[13px] font-bold text-gray-800 mb-2">{{ strtoupper($tab) }} Evaluation</h3>
                                        
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ strtoupper($tab) }} Status</label>
                                            <select name="{{ $tab }}_status" x-model="formData.{{ $tab }}_status"
                                                :class="formErrors.{{ $tab }}_status ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:border-[#0ea5e9] focus:ring-[#0ea5e9]'"
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border bg-white">
                                                <option value="" disabled>-- Select Status --</option>
                                                <option value="pending">Pending</option>
                                                <option value="not_started">Not Started</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="completed">Completed</option>
                                                <option value="remedial">Remedial</option>
                                            </select>
                                            <p x-show="formErrors.{{ $tab }}_status" class="text-red-500 text-xs mt-1" 
                                                x-text="formErrors.{{ $tab }}_status ? formErrors.{{ $tab }}_status[0] : ''" style="display: none;"></p>
                                        </div>

                                        <div x-data="{ newFile: null }">
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Attachment</label>
                                            <input id="{{ $tab }}_file" type="file" name="{{ $tab }}_file" accept="application/pdf" class="sr-only" 
                                                @change="newFile = $event.target.files[0] ? $event.target.files[0].name : null">
                                            
                                            <!-- View Mode -->
                                            <div x-show="formData.{{ $tab }}_lampiran || newFile" style="display: none;" 
                                                class="flex items-center justify-between border border-gray-200 bg-white rounded-lg p-3">
                                                <div class="flex items-center gap-3 overflow-hidden">
                                                    <i class="fas fa-file-pdf text-emerald-600 text-xl shrink-0"></i>
                                                    <span class="text-sm font-bold text-gray-800 truncate" 
                                                        x-text="newFile ? newFile : getOriginalFileName(formData.{{ $tab }}_lampiran)"></span>
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <a x-show="formData.{{ $tab }}_lampiran && !newFile" 
                                                        :href="'{{ Storage::disk('hrg')->url('') }}' + formData.{{ $tab }}_lampiran" 
                                                        target="_blank" 
                                                        class="px-3 py-1.5 text-xs font-semibold text-gray-600 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                                        View PDF
                                                    </a>
                                                    <label for="{{ $tab }}_file" 
                                                        class="px-3 py-1.5 text-xs font-semibold text-gray-800 border border-gray-300 rounded hover:bg-gray-50 transition-colors cursor-pointer flex items-center gap-1.5">
                                                        <i class="fas fa-sync-alt text-[10px]"></i> Change File
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Upload Mode -->
                                            <label x-show="!formData.{{ $tab }}_lampiran && !newFile" for="{{ $tab }}_file"
                                                :class="formErrors.{{ $tab }}_file ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white'"
                                                class="border border-dashed rounded-lg p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-file-alt text-gray-400 text-xl mb-1.5"></i>
                                                <span class="text-sky-600 font-bold text-[12px] mb-0.5">Click to upload file</span>
                                                <span class="text-[10px] font-bold text-gray-400">PDF, maximum 10 MB</span>
                                            </label>
                                            <p x-show="formErrors.{{ $tab }}_file" class="text-red-500 text-xs mt-1" 
                                                x-text="formErrors.{{ $tab }}_file ? formErrors.{{ $tab }}_file[0] : ''" style="display: none;"></p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-5 py-2.5 border-t border-gray-100 flex justify-end gap-2.5 bg-gray-50/50">
                                <button type="button" @click="showFormModal = false"
                                    class="px-3.5 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-[13px] font-medium hover:bg-gray-100 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                    :disabled="isSubmitting"
                                    class="px-4 py-1.5 bg-[#0ea5e9] text-white rounded-lg text-[13px] font-bold hover:bg-[#0284c7] transition-all shadow-sm flex items-center justify-center gap-2"
                                    :class="{'opacity-70 cursor-wait': isSubmitting}">
                                    <span x-show="!isSubmitting">Save Changes</span>
                                    <span x-show="isSubmitting" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i> Saving...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>

            <!-- Quick Upload Modal -->
            <template x-teleport="body">
                <div x-show="showUploadModal" style="display: none;" 
                    class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/60 overflow-y-auto"
                    @click.self="showUploadModal = false"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    
                    <div x-show="showUploadModal" 
                        @click.stop
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden text-left w-full sm:max-w-md my-auto">
                        
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">
                                Upload Attachment <span x-text="uploadEva.toUpperCase()"></span>
                            </h3>
                            <button type="button" @click="showUploadModal = false"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                                aria-label="Close modal">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                        
                        <form :action="'{{ url('training/history') }}/' + uploadId + '/upload-eva'"
                            method="POST" enctype="multipart/form-data" hx-boost="false"
                            x-data="{ isUploading: false }" @submit="isUploading = true">
                            @csrf
                            <input type="hidden" name="eva" :value="uploadEva">

                            <div class="p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select PDF File (Max 10MB)
                                </label>
                                <input type="file" name="file" accept="application/pdf" required
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded p-2">
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                                <button type="button" @click="showUploadModal = false"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" :disabled="isUploading"
                                    class="px-5 py-2.5 bg-[#0ea5e9] text-white rounded-lg text-sm font-medium shadow-sm hover:bg-[#0284c7] transition-all flex items-center justify-center gap-2"
                                    :class="{'opacity-70 cursor-wait': isUploading}">
                                    <span x-show="!isUploading">Upload</span>
                                    <span x-show="isUploading" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i> Uploading...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
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
    </style>
@endpush