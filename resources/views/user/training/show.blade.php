@extends('layouts.app')
@section('title', 'Training Details')

@section('content')
    <script>
        function trainingDetails() {
            return {
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

                getStatusModalLabel(statusVal) {
                    let map = {
                        '': '-- Select Status --',
                        'pending': 'Pending',
                        'not_started': 'Not Started',
                        'in_progress': 'In Progress',
                        'completed': 'Completed',
                        'remedial': 'Remedial'
                    };
                    return map[statusVal] || '-- Select Status --';
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
                    this.syncTomSelect();
                },

                syncTomSelect() {
                    this.$nextTick(() => {
                        let modalEl = document.querySelector('[role="dialog"]');
                        ['eva1', 'eva2', 'eva3'].forEach(tab => {
                            let el = modalEl ? modalEl.querySelector('select[name="' + tab + '_status"]') : document.querySelector('select[name="' + tab + '_status"]');
                            if (el) {
                                if (!el.tomselect && typeof TomSelect !== 'undefined') {
                                    let ts = new TomSelect(el, {
                                        controlInput: null,
                                        allowEmptyOption: true
                                    });
                                    el._tomSelectHooked = true;
                                    ts.on('dropdown_open', () => {
                                        let controlRect = ts.control.getBoundingClientRect();
                                        let dropdownHeight = Math.max(ts.dropdown.scrollHeight || 0, ts.dropdown.offsetHeight || 0, 220);
                                        let spaceBelow = window.innerHeight - controlRect.bottom;
                                        let spaceAbove = controlRect.top;
                                        if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                                            ts.wrapper.classList.add('drop-up');
                                        } else {
                                            ts.wrapper.classList.remove('drop-up');
                                        }
                                    });
                                    ts.on('change', (val) => {
                                        this.formData[tab + '_status'] = val;
                                    });
                                    let currentVal = this.formData[tab + '_status'] || '';
                                    if (currentVal) {
                                        ts.setValue(currentVal, true);
                                    }
                                } else if (el.tomselect) {
                                    if (!el._tomSelectHooked) {
                                        el._tomSelectHooked = true;
                                        el.tomselect.on('change', (val) => {
                                            this.formData[tab + '_status'] = val;
                                        });
                                    }
                                    let currentVal = this.formData[tab + '_status'] || '';
                                    if (el.tomselect.getValue() !== currentVal) {
                                        el.tomselect.setValue(currentVal, true);
                                    }
                                }
                            }
                        });
                    });
                },

                openUploadModal(eva, id) {
                    this.uploadEva = eva;
                    this.uploadId = id;
                    this.showUploadModal = true;
                }
            };
        }
    </script>

    <div x-data="trainingDetails()" x-init="if (showFormModal) syncTomSelect()">
        <div class="min-h-full -mx-8 -my-8 bg-white p-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
                        <a href="{{ route('user-training.index') }}" class="hover:text-gray-700 transition-colors">Training</a>
                        <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
                        <span class="text-gray-900 font-medium">Training Details</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('user-training.index') }}"
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
                        method="POST">
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
                        <div class="flex items-center gap-2.5">
                            <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $section['color'] }};"></div>
                            <h3 class="text-sm font-bold text-gray-900">{{ $section['title'] }}</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                {{ count($section['items']) }}
                            </span>
                        </div>
                        @can('tambah_training')
                            <button @click="openModal('Add {{ $section['title'] }}', '{{ $section['kategori'] }}')"
                                style="color: {{ $section['color'] }}; border-color: {{ $section['color'] }};"
                                class="bg-white border {{ $section['hover_bg'] }} px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-2xs">
                                <i class="fas fa-plus text-[10px]"></i> Add
                            </button>
                        @endcan
                    </div>

                    <div class="overflow-visible rounded-lg border border-gray-200 bg-white">
                        <table class="w-full text-left text-sm border-collapse table-fixed">
                            <thead class="text-white text-[11px] uppercase tracking-wider font-semibold" style="background-color: {{ $section['color'] }};">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-left" style="width: 20%;">Training Name</th>
                                    <th class="px-4 py-3 font-semibold text-center" style="width: 20%;">Status</th>
                                    <th class="px-4 py-3 font-semibold text-center" style="width: 15%;">Date</th>
                                    <th class="px-4 py-3 font-semibold text-center" style="width: 18%;">Evaluation</th>
                                    <th class="px-4 py-3 font-semibold text-center" style="width: 17%;">Attachment</th>
                                    <th class="px-4 py-3 font-semibold text-center" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($section['items'] as $item)
                                    <tr class="hover:bg-gray-50/70 transition-colors" 
                                        :class="(openStatusDropdown || openEvaDropdown) ? 'relative z-30' : ''"
                                        x-data="{ 
                                            selectedEva: sessionStorage.getItem('selectedEva_{{ $section['key_prefix'] }}_{{ $item->id }}') || 'eva1',
                                            evaStatus: {
                                                eva1: '{{ $item->eva1_status ?? '' }}',
                                                eva2: '{{ $item->eva2_status ?? '' }}',
                                                eva3: '{{ $item->eva3_status ?? '' }}'
                                            },
                                            openStatusDropdown: false,
                                            openEvaDropdown: false,
                                            dropUpStatus: false,
                                            dropUpEva: false,
                                            isUpdatingStatus: false,

                                            statusOptions: [
                                                { value: '', label: '-- Select Status --' },
                                                { value: 'pending', label: 'Pending' },
                                                { value: 'not_started', label: 'Not Started' },
                                                { value: 'in_progress', label: 'In Progress' },
                                                { value: 'completed', label: 'Completed' },
                                                { value: 'remedial', label: 'Remedial' }
                                            ],

                                            evaOptions: [
                                                { value: 'eva1', label: 'EVA 1 {{ $item->eva1_lampiran ? '✓' : '(empty)' }}' },
                                                { value: 'eva2', label: 'EVA 2 {{ $item->eva2_lampiran ? '✓' : '(empty)' }}' },
                                                { value: 'eva3', label: 'EVA 3 {{ $item->eva3_lampiran ? '✓' : '(empty)' }}' }
                                            ],

                                            getCurrentStatusLabel() {
                                                let val = this.evaStatus[this.selectedEva] || '';
                                                let found = this.statusOptions.find(o => o.value === val);
                                                return found ? found.label : '-- Select Status --';
                                            },

                                            getCurrentEvaLabel() {
                                                let found = this.evaOptions.find(o => o.value === this.selectedEva);
                                                return found ? found.label : 'EVA 1';
                                            },

                                            toggleStatusDropdown(buttonEl) {
                                                this.openStatusDropdown = !this.openStatusDropdown;
                                                this.openEvaDropdown = false;
                                                if (this.openStatusDropdown) {
                                                    let rect = buttonEl.getBoundingClientRect();
                                                    let spaceBelow = window.innerHeight - rect.bottom;
                                                    let spaceAbove = rect.top;
                                                    this.dropUpStatus = spaceBelow < 220 && spaceAbove > spaceBelow;
                                                }
                                            },

                                            toggleEvaDropdown(buttonEl) {
                                                this.openEvaDropdown = !this.openEvaDropdown;
                                                this.openStatusDropdown = false;
                                                if (this.openEvaDropdown) {
                                                    let rect = buttonEl.getBoundingClientRect();
                                                    let spaceBelow = window.innerHeight - rect.bottom;
                                                    let spaceAbove = rect.top;
                                                    this.dropUpEva = spaceBelow < 180 && spaceAbove > spaceBelow;
                                                }
                                            },

                                            async changeStatus(newStatus) {
                                                if (this.evaStatus[this.selectedEva] === newStatus) {
                                                    this.openStatusDropdown = false;
                                                    return;
                                                }
                                                const prevStatus = this.evaStatus[this.selectedEva];
                                                this.evaStatus[this.selectedEva] = newStatus;
                                                this.openStatusDropdown = false;
                                                this.isUpdatingStatus = true;

                                                try {
                                                    let fd = new FormData();
                                                    fd.append('_token', '{{ csrf_token() }}');
                                                    fd.append('_method', 'PATCH');
                                                    fd.append(this.selectedEva + '_status', newStatus);

                                                    let res = await fetch('{{ route('training.update-status', $item->id) }}', {
                                                        method: 'POST',
                                                        body: fd,
                                                        headers: { 
                                                            'X-Requested-With': 'XMLHttpRequest',
                                                            'Accept': 'application/json'
                                                        }
                                                    });

                                                    if (res.ok || res.redirected) {
                                                        this.isUpdatingStatus = false;
                                                    } else {
                                                        alert('Gagal memperbarui status');
                                                        this.evaStatus[this.selectedEva] = prevStatus;
                                                        this.isUpdatingStatus = false;
                                                    }
                                                } catch (err) {
                                                    alert('Gagal terhubung ke server');
                                                    this.evaStatus[this.selectedEva] = prevStatus;
                                                    this.isUpdatingStatus = false;
                                                }
                                            }
                                        }" 
                                        x-init="$watch('selectedEva', val => sessionStorage.setItem('selectedEva_{{ $section['key_prefix'] }}_{{ $item->id }}', val))">
                                        
                                        <!-- Training Name -->
                                        <td class="px-4 py-3 text-gray-800 font-medium text-sm align-middle break-words">
                                            {{ $item->nama_training }}
                                        </td>
                                        
                                        <!-- Status Evaluasi Dropdown -->
                                        <td class="px-4 py-3 text-center align-middle">
                                            <div class="relative inline-block w-[160px]" 
                                                :class="openStatusDropdown ? 'z-50' : 'z-0'"
                                                @click.outside="openStatusDropdown = false">
                                                <!-- Dropdown Trigger Button -->
                                                <button type="button" 
                                                    @click="toggleStatusDropdown($el)"
                                                    :disabled="isUpdatingStatus"
                                                    class="group w-full bg-white rounded-lg border shadow-xs py-2 px-3 text-xs font-medium h-[38px] flex items-center justify-between text-gray-700 cursor-pointer transition-all duration-200 focus:outline-none hover:border-[#0ea5e9] hover:bg-[#f0f9ff]/40"
                                                    :class="openStatusDropdown ? 'border-[#0ea5e9] ring-2 ring-[#0ea5e9]/20 bg-[#f0f9ff]/30' : 'border-gray-200 hover:border-[#0ea5e9]'">
                    
                                                    <div class="flex items-center gap-1.5 truncate flex-1 pr-1">
                                                        <i x-show="isUpdatingStatus" class="fas fa-spinner fa-spin text-xs text-[#0ea5e9]" style="display: none;"></i>
                                                        <span class="truncate font-medium text-left" :class="isUpdatingStatus ? 'opacity-60' : ''" x-text="getCurrentStatusLabel()"></span>
                                                    </div>
                    
                                                    <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:text-[#0ea5e9] transition-transform duration-200 shrink-0" 
                                                        :class="openStatusDropdown ? 'rotate-180 text-[#0ea5e9]' : ''"></i>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div x-show="openStatusDropdown" 
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    style="display: none;"
                                                    :class="dropUpStatus ? 'bottom-full mb-1 origin-bottom' : 'top-full mt-1 origin-top'"
                                                    class="absolute left-0 w-full min-w-[160px] bg-white rounded-lg border border-gray-200 shadow-xl p-1 z-[9999] text-left">
                                                    
                                                    <template x-for="opt in statusOptions" :key="opt.value">
                                                        <button type="button" 
                                                            @click="changeStatus(opt.value)"
                                                            class="dropdown-option-btn w-full px-3 py-2 rounded-md transition-colors text-xs text-left flex items-center justify-between cursor-pointer"
                                                            :class="evaStatus[selectedEva] === opt.value ? 'is-active bg-[#e0f2fe] text-[#0284c7] font-medium' : 'text-gray-700'"
                                                            :style="evaStatus[selectedEva] === opt.value ? 'background-color: #e0f2fe !important; color: #0284c7 !important; font-weight: 500;' : ''">
                                                            <span x-text="opt.label"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="px-4 py-3 text-center align-middle text-gray-600 text-xs font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($item->tanggal_training)->format('d M Y') }}
                                        </td>

                                        <!-- Dropdown Pemilih Evaluasi -->
                                        <td class="px-4 py-3 text-center align-middle">
                                            <div class="relative inline-block w-[140px]" 
                                                :class="openEvaDropdown ? 'z-40' : 'z-10'"
                                                @click.outside="openEvaDropdown = false">
                                                <!-- Dropdown Trigger Button -->
                                                <button type="button" 
                                                    @click="toggleEvaDropdown($el)"
                                                    class="group w-full bg-white rounded-lg border shadow-xs py-2 px-3 text-xs font-medium h-[38px] flex items-center justify-between text-gray-700 cursor-pointer transition-all duration-200 focus:outline-none hover:border-[#0ea5e9] hover:bg-[#f0f9ff]/40"
                                                    :class="openEvaDropdown ? 'border-[#0ea5e9] ring-2 ring-[#0ea5e9]/20 bg-[#f0f9ff]/30' : 'border-gray-200 hover:border-[#0ea5e9]'">
                                                    <span class="truncate font-medium text-left flex-1 pr-1" x-text="getCurrentEvaLabel()"></span>
                                                    <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:text-[#0ea5e9] transition-transform duration-200 shrink-0" 
                                                        :class="openEvaDropdown ? 'rotate-180 text-[#0ea5e9]' : ''"></i>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <div x-show="openEvaDropdown" 
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    style="display: none;"
                                                    :class="dropUpEva ? 'bottom-full mb-1 origin-bottom' : 'top-full mt-1 origin-top'"
                                                    class="absolute left-0 w-full min-w-[140px] bg-white rounded-lg border border-gray-200 shadow-xl p-1 z-[9999] text-left">
                                                    
                                                    <template x-for="opt in evaOptions" :key="opt.value">
                                                        <button type="button" 
                                                            @click="selectedEva = opt.value; openEvaDropdown = false"
                                                            class="dropdown-option-btn w-full px-3 py-2 rounded-md transition-colors text-xs text-left flex items-center justify-between cursor-pointer"
                                                            :class="selectedEva === opt.value ? 'is-active bg-[#e0f2fe] text-[#0284c7] font-medium' : 'text-gray-700'"
                                                            :style="selectedEva === opt.value ? 'background-color: #e0f2fe !important; color: #0284c7 !important; font-weight: 500;' : ''">
                                                            <span x-text="opt.label"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Lampiran -->
                                        <td class="px-4 py-3 text-center align-middle">
                                            @foreach (['eva1', 'eva2', 'eva3'] as $eva)
                                                @php $lampiranField = "{$eva}_lampiran"; @endphp
                                                <div x-show="selectedEva === '{{ $eva }}'" @if(!$loop->first) style="display: none;" @endif class="flex items-center justify-center">
                                                    @if ($item->$lampiranField)
                                                        <a href="{{ Storage::disk('hrg')->url($item->$lampiranField) }}" target="_blank"
                                                            class="inline-flex items-center justify-center gap-1.5 px-3 h-[38px] rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 hover:text-rose-700 transition-colors shadow-2xs"
                                                            title="View {{ strtoupper($eva) }} PDF">
                                                            <i class="fas fa-file-pdf text-rose-500 text-sm"></i>
                                                            <span class="truncate">{{ strtoupper($eva) }} PDF</span>
                                                        </a>
                                                    @else
                                                        <button type="button"
                                                            @click="openUploadModal('{{ $eva }}', {{ $item->id }})"
                                                            style="color: {{ $section['color'] }};"
                                                            class="inline-flex items-center justify-center gap-1.5 px-3 h-[38px] rounded-lg text-xs font-medium border border-dashed border-gray-300 hover:border-current {{ $section['hover_bg'] }} transition-colors"
                                                            title="Upload {{ strtoupper($eva) }} Attachment">
                                                            <i class="fas fa-plus text-[10px]"></i>
                                                            <span>Attachment</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>

                                        <!-- Aksi -->
                                        <td class="px-4 py-3 text-center align-middle">
                                            <div class="flex items-center justify-center gap-1.5">
                                                @can('edit_training')
                                                    <button type="button"
                                                        @click="openModal('Edit {{ $section['title'] }}', '{{ $section['kategori'] }}', @js($item))"
                                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#0ea5e9] hover:bg-sky-50 transition-all"
                                                        title="Edit training">
                                                        <i class="fas fa-pen text-xs"></i>
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
                                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all"
                                                            title="Delete training">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-xs">
                                            <div class="flex flex-col items-center justify-center gap-1.5">
                                                <i class="fas fa-folder-open text-2xl text-gray-300"></i>
                                                <p>No {{ strtolower($section['title']) }} history found</p>
                                            </div>
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
                                             <button type="button" @click="activeEvaTab = '{{ $tabKey }}'; syncTomSelect();"
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
                                        
                                        <!-- Custom Dropdown Status Modal -->
                                        <div x-data="{ 
                                            openStatusModalDrop: false,
                                            dropUp: false,
                                            statusOptions: [
                                                { value: '', label: '-- Select Status --' },
                                                { value: 'pending', label: 'Pending' },
                                                { value: 'not_started', label: 'Not Started' },
                                                { value: 'in_progress', label: 'In Progress' },
                                                { value: 'completed', label: 'Completed' },
                                                { value: 'remedial', label: 'Remedial' }
                                            ],
                                            toggle(btnEl) {
                                                this.openStatusModalDrop = !this.openStatusModalDrop;
                                                if (this.openStatusModalDrop) {
                                                    let rect = btnEl.getBoundingClientRect();
                                                    let spaceBelow = window.innerHeight - rect.bottom;
                                                    let spaceAbove = rect.top;
                                                    this.dropUp = spaceBelow < 180 && spaceAbove > spaceBelow;
                                                }
                                            }
                                        }" class="mb-4">
                                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Status</label>
                                            <input type="hidden" name="{{ $tab }}_status" :value="formData.{{ $tab }}_status">
                                            <div class="relative" :class="openStatusModalDrop ? 'z-50' : 'z-10'" @click.outside="openStatusModalDrop = false">
                                                <button type="button" 
                                                    @click="toggle($el)"
                                                    class="group w-full bg-white rounded-lg border shadow-sm py-2 px-3 text-sm min-h-[40px] flex items-center justify-between text-gray-700 cursor-pointer transition-all duration-200 focus:outline-none hover:border-[#0ea5e9] hover:bg-[#f0f9ff]/40"
                                                    :class="formErrors.{{ $tab }}_status ? 'border-red-500 ring-1 ring-red-500' : (openStatusModalDrop ? 'border-[#0ea5e9] ring-2 ring-[#0ea5e9]/20 bg-[#f0f9ff]/30' : 'border-gray-300 hover:border-[#0ea5e9]')">
                                                    <span class="truncate font-normal" x-text="getStatusModalLabel(formData.{{ $tab }}_status)"></span>
                                                    <i class="fas fa-chevron-down text-[11px] text-[#0ea5e9] transition-transform duration-300 ml-2" 
                                                        :class="openStatusModalDrop ? 'rotate-180' : ''"></i>
                                                </button>

                                                <div x-show="openStatusModalDrop" 
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    style="display: none;"
                                                    :class="dropUp ? 'bottom-full mb-1 origin-bottom' : 'top-full mt-1 origin-top'"
                                                    class="absolute left-0 w-full bg-white rounded-lg border border-gray-200 shadow-xl p-1 z-[9999] text-left">
                                                    <template x-for="opt in statusOptions" :key="opt.value">
                                                        <button type="button" 
                                                            @click="formData.{{ $tab }}_status = opt.value; openStatusModalDrop = false"
                                                            class="dropdown-option-btn w-full px-3 py-2 rounded-md transition-colors text-sm text-left flex items-center justify-between cursor-pointer"
                                                            :class="formData.{{ $tab }}_status === opt.value ? 'is-active bg-[#e0f2fe] text-[#0284c7] font-medium' : 'text-gray-700'"
                                                            :style="formData.{{ $tab }}_status === opt.value ? 'background-color: #e0f2fe !important; color: #0284c7 !important; font-weight: 500;' : ''">
                                                            <span x-text="opt.label"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
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

        /* Custom dropdown active and hover highlight */
        .dropdown-option-btn.is-active {
            background-color: #e0f2fe !important;
            color: #0284c7 !important;
            font-weight: 500 !important;
        }
        .dropdown-option-btn:hover {
            background-color: #e0f2fe !important;
            color: #0284c7 !important;
        }
    </style>
@endpush
