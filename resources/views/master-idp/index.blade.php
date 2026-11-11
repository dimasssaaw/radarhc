@extends('layouts.app')
@section('title', 'Master Data IDP')

@section('content')
<div x-data="{ 
    activePosition: '{{ session('active_position', strtolower($positions->first()?->nama_jabatan ?? '')) }}'
}">

    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Data IDP</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage spider charts and lists of competencies for each position</p>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if (session('success'))
    <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl mb-6 border border-emerald-200 flex items-center justify-between text-xs font-semibold shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-6 border border-red-200 flex items-center justify-between text-xs font-semibold shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-500 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if (isset($errors) && $errors->any())
    <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-6 border border-red-200 text-xs font-semibold shadow-sm animate-fade-in">
        <div class="flex items-center gap-2 mb-1.5">
            <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
            <span>Failed to save data. Please check the following errors:</span>
        </div>
        <ul class="list-disc list-inside ml-4 space-y-0.5 text-[11px] text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Position Tabs Bar -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar border-b border-gray-200 pb-3 mb-6">
        @foreach($positions as $pos)
        @php
            $posSlug = strtolower($pos->nama_jabatan);
            $chartCount = $groups->where('target_position', $posSlug)->count();
        @endphp
        <button type="button"
            @click="activePosition = '{{ $posSlug }}'"
            :class="activePosition === '{{ $posSlug }}' ? 'bg-[#0ea5e9] text-white shadow-sm font-bold' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 font-medium'"
            class="px-4 py-2 rounded-xl text-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm flex items-center gap-2 shrink-0">
            <span>{{ $pos->nama_jabatan }}</span>
            <span :class="activePosition === '{{ $posSlug }}' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600'" class="px-2 py-0.5 rounded-full text-[10px] font-bold">
                {{ $chartCount }} Chart
            </span>
        </button>
        @endforeach
    </div>

    <!-- Position Content Panels -->
    @foreach($positions as $pos)
    @php
        $posSlug = strtolower($pos->nama_jabatan);
        $posGroups = $groups->where('target_position', $posSlug);
    @endphp
    <div x-show="activePosition === '{{ $posSlug }}'" class="space-y-6">
        
        <!-- Position Action Header -->
        <div class="flex items-center justify-between bg-white px-5 py-3.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md">
            <div>
                <h2 class="font-bold text-gray-900 text-sm sm:text-base">Position: {{ $pos->nama_jabatan }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Total {{ $posGroups->count() }} Spider chart for this position</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openCopyGroupModal('{{ $posSlug }}', '{{ addslashes($pos->nama_jabatan) }}')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 rounded-lg text-xs font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-copy text-sky-600"></i> Salin dari Posisi Lain
                </button>
                <button type="button" data-position="{{ $posSlug }}" onclick="document.getElementById('addChartTargetPosition').value=this.dataset.position;document.getElementById('addChartGroupModal').classList.remove('hidden')" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3.5 py-2 rounded-lg text-xs font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <i class="fas fa-plus"></i> Add Spider Chart
                </button>
            </div>
        </div>

        <!-- Charts for this position -->
        @forelse($posGroups as $group)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-sky-200">
            <!-- Chart Bar -->
            <div class="px-5 py-3.5 bg-slate-50 border-b border-gray-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="px-2.5 py-0.5 rounded-md bg-sky-100 text-sky-800 text-[11px] font-bold">
                        Chart #{{ $loop->iteration }}
                    </span>
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                        <i class="fas fa-bullseye text-[#0ea5e9]"></i> {{ $group->title }}
                    </h3>
                    <span class="text-xs text-gray-400">({{ $group->competencies_count }} Kompetensi)</span>
                </div>

                <div class="flex items-center gap-1.5">
                    <button type="button" 
                        onclick="const form=document.getElementById('addCompetencyForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('addCompGroupSubtitle').innerText='Ke Chart: '+this.dataset.groupTitle;document.getElementById('addCompName').value='';document.getElementById('addCompTargetScore').value='5.0';document.getElementById('addCompetencyModal').classList.remove('hidden')"
                        data-group-id="{{ $group->id }}"
                        data-group-title="{{ $group->title }}"
                        data-form-action="{{ route('master-idp.competency.store', $group->id) }}"
                        class="bg-white hover:bg-sky-50 text-[#0ea5e9] border border-sky-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-plus text-[10px]"></i> Add Competency
                    </button>
                    <button type="button"
                        onclick="document.getElementById('editGroupForm').action=this.dataset.formAction;document.getElementById('editGroupTitle').value=this.dataset.title;document.getElementById('editGroupTargetPosition').value=this.dataset.targetPosition;document.getElementById('editGroupDescription').value=this.dataset.description;document.getElementById('editChartGroupModal').classList.remove('hidden')"
                        data-id="{{ $group->id }}"
                        data-form-action="{{ route('master-idp.group.update', $group->id) }}"
                        data-title="{{ $group->title }}"
                        data-target-position="{{ $group->target_position }}"
                        data-description="{{ $group->description }}"
                        class="text-gray-400 hover:text-[#0ea5e9] p-1.5 rounded-lg hover:bg-sky-50 transition-colors" title="Edit Judul Chart">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <form action="{{ route('master-idp.group.destroy', $group->id) }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="inline-block" onsubmit="return confirm('Hapus chart {{ addslashes($group->title) }} ini? Seluruh kompetensi di dalamnya akan ikut terhapus.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Chart">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Competencies Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="text-xs text-gray-500 bg-slate-50/70 border-b border-gray-100 font-semibold">
                        <tr>
                            <th class="px-5 py-2.5 text-center w-12 whitespace-nowrap">No</th>
                            <th class="px-5 py-2.5 whitespace-nowrap">Competency Name</th>
                            <th class="px-5 py-2.5 text-center whitespace-nowrap">Target Score</th>
                            <th class="px-5 py-2.5 text-center w-24 whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse($group->competencies as $comp)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-2.5 text-center text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-5 py-2.5 font-bold text-gray-800">
                                <span>{{ $comp->name }}</span>
                            </td>
                            <td class="px-5 py-2.5 text-center">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    {{ number_format($comp->target_score, 1) }}
                                </span>
                            </td>
                            <td class="px-5 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button"
                                        onclick="const form=document.getElementById('editCompetencyForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('editCompName').value=this.dataset.name;document.getElementById('editCompTargetScore').value=this.dataset.targetScore;document.getElementById('editCompetencyModal').classList.remove('hidden')"
                                        data-id="{{ $comp->id }}"
                                        data-form-action="{{ route('master-idp.competency.update', $comp->id) }}"
                                        data-name="{{ $comp->name }}"
                                        data-target-score="{{ $comp->target_score ?? 5.0 }}"
                                        class="text-gray-400 hover:text-[#0ea5e9] p-1 rounded hover:bg-sky-50 transition-colors" title="Edit Kompetensi">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form action="{{ route('master-idp.competency.destroy', $comp->id) }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="inline-block" onsubmit="return confirm('Hapus kompetensi {{ addslashes($comp->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1 rounded hover:bg-red-50 transition-colors" title="Hapus Kompetensi">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-4 text-center text-gray-400 text-xs">
                                Belum ada kompetensi. 
                                <button type="button" 
                                    onclick="const form=document.getElementById('addCompetencyForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('addCompGroupSubtitle').innerText='Ke Chart: '+this.dataset.groupTitle;document.getElementById('addCompName').value='';document.getElementById('addCompTargetScore').value='5.0';document.getElementById('addCompetencyModal').classList.remove('hidden')"
                                    data-group-id="{{ $group->id }}"
                                    data-group-title="{{ $group->title }}"
                                    data-form-action="{{ route('master-idp.competency.store', $group->id) }}"
                                    class="text-[#0ea5e9] font-bold hover:underline ml-1">
                                    + Tambah Kompetensi
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-8 text-center border border-dashed border-gray-300">
            <p class="text-xs text-gray-500 mb-3">There is no Spider Chart for the position yet <strong>{{ $pos->nama_jabatan }}</strong></p>
            <button type="button" data-position="{{ $posSlug }}" onclick="document.getElementById('addChartTargetPosition').value=this.dataset.position;document.getElementById('addChartGroupModal').classList.remove('hidden')" class="px-3.5 py-2 bg-[#0ea5e9] hover:bg-[#0284c7] text-white text-xs font-bold rounded-lg shadow-sm transition-all inline-flex items-center gap-1.5">
                <i class="fas fa-plus"></i> Add Spider Chart
            </button>
        </div>
        @endforelse

    </div>
    @endforeach

</div>
@endsection

@push('modals')
<!-- ================= MODALS (Full Dark Transparent Overlay) ================= -->

<!-- 1. Modal Tambah Spider Chart -->
<div id="addChartGroupModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay: Full-screen semi-transparent black -->
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('addChartGroupModal')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-bullseye text-[#0ea5e9]"></i> Add New Spider Chart
                </h3>
                <button type="button" onclick="closeModal('addChartGroupModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('master-idp.group.store') }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                <div class="p-5 space-y-3.5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Position <span class="text-red-500">*</span></label>
                        <select id="addChartTargetPosition" name="target_position" required class="native-select w-full rounded-xl border border-gray-300 bg-white text-xs p-2.5 font-medium focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9]">
                            <option value="">-- Select Position --</option>
                            @foreach($positions as $pos)
                                <option value="{{ strtolower($pos->nama_jabatan) }}">{{ $pos->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Spider Chart Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]" placeholder="Contoh: Keahlian Teknis, Core Values">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Description (Optional)</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]" placeholder="Keterangan singkat kelompok chart..."></textarea>
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addChartGroupModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Simpan Chart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Modal Edit Spider Chart -->
<div id="editChartGroupModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('editChartGroupModal')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-edit text-[#0ea5e9]"></i> Edit Judul Spider Chart
                </h3>
                <button type="button" onclick="closeModal('editChartGroupModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="editGroupForm" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                @method('PUT')
                <div class="p-5 space-y-3.5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Jabatan <span class="text-red-500">*</span></label>
                        <select id="editGroupTargetPosition" name="target_position" required class="native-select w-full rounded-xl border border-gray-300 bg-white text-xs p-2.5 font-medium focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9]">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($positions as $pos)
                                <option value="{{ strtolower($pos->nama_jabatan) }}">{{ $pos->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Judul Spider Chart <span class="text-red-500">*</span></label>
                        <input type="text" id="editGroupTitle" name="title" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea id="editGroupDescription" name="description" rows="2" class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]"></textarea>
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editChartGroupModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Update Chart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. Modal Tambah Kompetensi -->
<div id="addCompetencyModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('addCompetencyModal')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="fas fa-plus-circle text-[#0ea5e9]"></i> Add New Competencies
                    </h3>
                    <p id="addCompGroupSubtitle" class="text-xs text-[#0ea5e9] font-semibold mt-0.5"></p>
                </div>
                <button type="button" onclick="closeModal('addCompetencyModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="addCompetencyForm" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Competency Name <span class="text-red-500">*</span></label>
                        <input type="text" id="addCompName" name="name" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]" placeholder="Contoh: Problem Solving, Digital Mindset">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Score (1.0 - 5.0) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" min="1" max="5" id="addCompTargetScore" name="target_score" value="5.0" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addCompetencyModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Save Competency</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. Modal Edit Kompetensi -->
<div id="editCompetencyModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('editCompetencyModal')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-edit text-[#0ea5e9]"></i> Edit Competency
                </h3>
                <button type="button" onclick="closeModal('editCompetencyModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="editCompetencyForm" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                @method('PUT')
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Competency Name <span class="text-red-500">*</span></label>
                        <input type="text" id="editCompName" name="name" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Score (1.0 - 5.0) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" min="1" max="5" id="editCompTargetScore" name="target_score" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editCompetencyModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Update Competency</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 5. Modal Salin Spider Chart dari Posisi Lain -->
<div id="copyChartGroupModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('copyChartGroupModal')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-copy text-[#0ea5e9]"></i> Salin Spider Chart dari Posisi Lain
                </h3>
                <button type="button" onclick="closeModal('copyChartGroupModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('master-idp.group.copy') }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                <input type="hidden" id="copyTargetPosition" name="target_position" value="">
                <div class="p-5 space-y-3.5">
                    <div class="bg-sky-50 border border-sky-100 rounded-xl p-3 text-xs text-sky-800">
                        <i class="fas fa-info-circle text-[#0ea5e9] mr-1"></i>
                        Akan disalin ke jabatan: <span id="copyTargetPositionLabel" class="font-bold text-sky-950"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Chart Sumber <span class="text-red-500">*</span></label>
                        <select id="copySourceGroupId" name="source_group_id" required data-dropdown-parent="body" data-custom-render="chart" class="w-full text-xs font-medium focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                            <option value="">-- Pilih Chart Yang Ingin Disalin --</option>
                            @php
                                $matchedSlugs = [];
                            @endphp
                            @foreach($positions as $pOpt)
                                @php
                                    $pSlug = strtolower($pOpt->nama_jabatan);
                                    $matchedSlugs[] = $pSlug;
                                    $pGroups = $groups->where('target_position', $pSlug);
                                @endphp
                                @if($pGroups->isNotEmpty())
                                    <optgroup label="Posisi: {{ $pOpt->nama_jabatan }}">
                                        @foreach($pGroups as $grp)
                                            <option value="{{ $grp->id }}"
                                                    data-pos="{{ $pOpt->nama_jabatan }}"
                                                    data-title="{{ $grp->title }}"
                                                    data-aspek="{{ $grp->competencies_count }}">
                                                {{ $grp->title }} ({{ $grp->competencies_count }} Aspek)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                            @php
                                $orphanGroups = $groups->filter(fn ($g) => !in_array(strtolower($g->target_position), $matchedSlugs));
                            @endphp
                            @if($orphanGroups->isNotEmpty())
                                <optgroup label="Posisi Lainnya">
                                    @foreach($orphanGroups as $grp)
                                        <option value="{{ $grp->id }}"
                                                data-pos="{{ ucwords($grp->target_position) }}"
                                                data-title="{{ $grp->title }}"
                                                data-aspek="{{ $grp->competencies_count }}">
                                            {{ $grp->title }} ({{ $grp->competencies_count }} Aspek)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1.5">Seluruh aspek kompetensi dan target nilai di dalam chart ini akan otomatis disalin ke jabatan tujuan.</p>
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('copyChartGroupModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-clone"></i> Salin Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.radarBaseUrl = "{{ url('/') }}";

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
        const copySelectEl = document.getElementById('copySourceGroupId');
        if (copySelectEl && copySelectEl.tomselect) {
            copySelectEl.tomselect.close();
        }
    }

    function openCopyGroupModal(targetPosSlug, targetPosName) {
        document.getElementById('copyTargetPosition').value = targetPosSlug;
        document.getElementById('copyTargetPositionLabel').innerText = targetPosName;
        const copySelectEl = document.getElementById('copySourceGroupId');
        if (copySelectEl && copySelectEl.tomselect) {
            copySelectEl.tomselect.clear();
            copySelectEl.tomselect.close();
        }
        document.getElementById('copyChartGroupModal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const copyModal = document.getElementById('copyChartGroupModal');
        if (copyModal) {
            copyModal.addEventListener('scroll', function() {
                const el = document.getElementById('copySourceGroupId');
                if (el && el.tomselect && el.tomselect.isOpen) {
                    el.tomselect.positionDropdown();
                }
            });
        }
    });

    function openAddGroupModal(position = '') {
        const selectEl = document.getElementById('addChartTargetPosition');
        if (selectEl && position) {
            selectEl.value = position;
        }
        document.getElementById('addChartGroupModal').classList.remove('hidden');
    }

    function openEditGroupModal(btn) {
        const id = btn.getAttribute('data-id');
        const title = btn.getAttribute('data-title') || '';
        const targetPosition = btn.getAttribute('data-target-position') || '';
        const description = btn.getAttribute('data-description') || '';

        const form = document.getElementById('editGroupForm');
        form.action = window.radarBaseUrl + '/master-idp/group/' + id;
        document.getElementById('editGroupTitle').value = title;
        document.getElementById('editGroupTargetPosition').value = targetPosition;
        document.getElementById('editGroupDescription').value = description;
        document.getElementById('editChartGroupModal').classList.remove('hidden');
    }

    function openAddCompetencyModal(btn) {
        const groupId = btn.getAttribute('data-group-id');
        const groupTitle = btn.getAttribute('data-group-title') || '';

        const form = document.getElementById('addCompetencyForm');
        form.action = window.radarBaseUrl + '/master-idp/group/' + groupId + '/competency';
        document.getElementById('addCompGroupSubtitle').innerText = 'Ke Chart: ' + groupTitle;
        document.getElementById('addCompName').value = '';
        document.getElementById('addCompTargetScore').value = '5.0';

        document.getElementById('addCompetencyModal').classList.remove('hidden');
    }

    function openEditCompetencyModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || '';
        const targetScore = btn.getAttribute('data-target-score') || '5.0';

        const form = document.getElementById('editCompetencyForm');
        form.action = window.radarBaseUrl + '/master-idp/competency/' + id;
        document.getElementById('editCompName').value = name;
        document.getElementById('editCompTargetScore').value = targetScore;

        document.getElementById('editCompetencyModal').classList.remove('hidden');
    }
</script>
@endpush
