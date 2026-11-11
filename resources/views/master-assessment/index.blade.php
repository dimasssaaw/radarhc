@extends('layouts.app')
@section('title', 'Master Data Assessment')

@section('content')
<div>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Data Assessment</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage categories and assessment criteria for competency evaluations</p>
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
            <span>Failed to save data. Please double-check:</span>
        </div>
        <ul class="list-disc list-inside ml-4 space-y-0.5 text-[11px] text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Main Content: Kategori & Aspek Penilaian -->
    <div class="space-y-6">
        
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white px-5 py-3.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#0284c7] flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 text-sm sm:text-base">Assessment Report Chart</h2>
                </div>
            </div>
            @can('tambah_master_assessment')
            <button type="button" onclick="openAddCategoryModal()" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3.5 py-2 rounded-lg text-xs font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center gap-1.5 shadow-sm">
                <i class="fas fa-plus"></i> Add Chart
            </button>
            @endcan
        </div>

        <!-- Categories & Aspects Cards -->
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-sky-200">
            <!-- Category Header Bar -->
            <div class="px-5 py-3.5 bg-slate-50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0284c7] flex items-center justify-center font-bold text-xs shrink-0">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-gray-900 text-sm">{{ $category->name }}</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold">
                            {{ $category->aspects_count ?? $category->aspects->count() }} Aspect
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-auto">
                    @can('tambah_master_assessment')
                    <button type="button" 
                        data-category-id="{{ $category->id }}"
                        data-category-name="{{ $category->name }}"
                        data-form-action="{{ route('master-assessment.aspect.store', $category->id) }}"
                        onclick="const form=document.getElementById('addAspectForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('addAspectCategorySubtitle').innerText='Kategori: '+this.dataset.categoryName;document.getElementById('addAspectName').value='';document.getElementById('addAspectTargetScore').value='5.0';document.getElementById('addAspectModal').classList.remove('hidden')"
                        class="bg-white hover:bg-gray-50 text-[#0ea5e9] border border-[#0ea5e9]/30 hover:border-[#0ea5e9] px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm flex items-center gap-1.5">
                        <i class="fas fa-plus"></i> Add Aspect
                    </button>
                    @endcan

                    @can('edit_master_assessment')
                    <button type="button"
                        data-id="{{ $category->id }}"
                        data-name="{{ $category->name }}"
                        data-form-action="{{ route('master-assessment.category.update', $category->id) }}"
                        onclick="const form=document.getElementById('editCategoryForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('editCatName').value=this.dataset.name;document.getElementById('editCategoryModal').classList.remove('hidden')"
                        class="text-gray-500 hover:text-[#0ea5e9] p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="Edit Kategori">
                        <i class="fas fa-pen text-xs"></i>
                    </button>
                    @endcan

                    @can('hapus_master_assessment')
                    <form action="{{ route('master-assessment.category.destroy', $category->id) }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ $category->name }}\' dan semua aspek penilaian di dalamnya?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Kategori">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>

            <!-- Aspects Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-400 font-semibold uppercase text-[10px]">
                            <th class="py-2.5 px-5 w-12 text-center">No</th>
                            <th class="py-2.5 px-4">Assessment Aspect Name </th>
                            <th class="py-2.5 px-4 text-center w-36">Target Score</th>
                            <th class="py-2.5 px-5 text-right w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($category->aspects as $index => $aspect)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-5 text-center text-gray-400 font-mono text-xs">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-gray-800 text-xs">{{ $aspect->name }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-[#0284c7] border border-sky-100">
                                    {{ number_format($aspect->target_score, 1) }} <span class="text-gray-400 text-[10px] ml-1 font-normal">/ 5.0</span>
                                </span>
                            </td>
                            <td class="py-3 px-5 text-right space-x-1">
                                @can('edit_master_assessment')
                                <button type="button"
                                    data-id="{{ $aspect->id }}"
                                    data-name="{{ $aspect->name }}"
                                    data-target-score="{{ $aspect->target_score }}"
                                    data-form-action="{{ route('master-assessment.aspect.update', $aspect->id) }}"
                                    onclick="const form=document.getElementById('editAspectForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('editAspectName').value=this.dataset.name;document.getElementById('editAspectTargetScore').value=this.dataset.targetScore;document.getElementById('editAspectModal').classList.remove('hidden')"
                                    class="text-gray-400 hover:text-[#0ea5e9] p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="Edit Aspek">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                @endcan

                                @can('hapus_master_assessment')
                                <form action="{{ route('master-assessment.aspect.destroy', $aspect->id) }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aspek penilaian \'{{ $aspect->name }}\'?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Aspek">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 px-4 text-center text-gray-400 text-xs">
                                <div class="flex flex-col items-center justify-center py-2">
                                    <i class="fas fa-clipboard-list text-gray-300 text-2xl mb-2"></i>
                                    <p class="font-medium text-gray-500">There are no assessment criteria in this category yet.</p>
                                    @can('tambah_master_assessment')
                                    <button type="button" 
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->name }}"
                                        onclick="const form=document.getElementById('addAspectForm');form.setAttribute('action',this.dataset.formAction);form.setAttribute('hx-post',this.dataset.formAction);htmx.process(form);document.getElementById('addAspectCategorySubtitle').innerText='Kategori: '+this.dataset.categoryName;document.getElementById('addAspectName').value='';document.getElementById('addAspectTargetScore').value='5.0';document.getElementById('addAspectModal').classList.remove('hidden')"
                                        data-form-action="{{ route('master-assessment.aspect.store', $category->id) }}"
                                        data-category-name="{{ $category->name }}"
                                        class="mt-2 text-xs text-[#0ea5e9] font-bold hover:underline">
                                        + Add Aspect Now
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-200">
            <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
            <h3 class="font-bold text-gray-700 text-base">No Assessment Chart Available Yet</h3>
            <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">Create a new chart to categorize the assessment evaluation aspects.</p>
            @can('tambah_master_assessment')
            <button type="button" onclick="openAddCategoryModal()" class="mt-4 bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                <i class="fas fa-plus mr-1"></i> Create Your First Chart
            </button>
            @endcan
        </div>
        @endforelse

    </div>

</div>
@endsection

@push('modals')
<!-- 1. Modal Tambah Kategori -->
<div id="addCategoryModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('addCategoryModal')"></div>
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-folder-plus text-[#0ea5e9]"></i> Add Assessment Chart
                </h3>
                <button type="button" onclick="closeModal('addCategoryModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('master-assessment.category.store') }}" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                <div class="p-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Chart Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]" placeholder="Contoh: Cognitive Ability, Work Attitude">
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addCategoryModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Save Chart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Modal Edit Kategori -->
<div id="editCategoryModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('editCategoryModal')"></div>
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-edit text-[#0ea5e9]"></i> Edit Assessment Chart
                </h3>
                <button type="button" onclick="closeModal('editCategoryModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="editCategoryForm" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                @method('PUT')
                <div class="p-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Chart Name <span class="text-red-500">*</span></label>
                        <input type="text" id="editCatName" name="name" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editCategoryModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Update Chart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. Modal Tambah Aspek Penilaian -->
<div id="addAspectModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('addAspectModal')"></div>
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="fas fa-plus-circle text-[#0ea5e9]"></i> Add Assessment Aspect
                    </h3>
                    <p id="addAspectCategorySubtitle" class="text-xs text-[#0ea5e9] font-semibold mt-0.5"></p>
                </div>
                <button type="button" onclick="closeModal('addAspectModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="addAspectForm" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Assessment Aspect Name <span class="text-red-500">*</span></label>
                        <input type="text" id="addAspectName" name="name" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 focus:ring-[#0ea5e9] focus:border-[#0ea5e9]" placeholder="Contoh: Sistematika Kerja, Logika Berpikir">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Score (1.0 - 5.0) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" min="1" max="5" id="addAspectTargetScore" name="target_score" value="5.0" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addAspectModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Save Aspect</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. Modal Edit Aspek Penilaian -->
<div id="editAspectModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-black/60 transition-opacity" aria-hidden="true" onclick="closeModal('editAspectModal')"></div>
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-md border border-gray-100 my-8 z-10 animate-fade-in-up">
            <div class="px-5 py-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i class="fas fa-edit text-[#0ea5e9]"></i> Edit Assessment Aspect
                </h3>
                <button type="button" onclick="closeModal('editAspectModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="editAspectForm" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                @csrf
                @method('PUT')
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Assessment Aspect Name <span class="text-red-500">*</span></label>
                        <input type="text" id="editAspectName" name="name" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Target Score (1.0 - 5.0) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" min="1" max="5" id="editAspectTargetScore" name="target_score" required class="w-full rounded-lg border-gray-300 text-xs border p-2.5 font-bold focus:ring-[#0ea5e9] focus:border-[#0ea5e9]">
                    </div>
                </div>
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editAspectModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#0ea5e9] hover:bg-[#0284c7] rounded-lg shadow-md shadow-sky-500/20 transition-all flex items-center gap-1.5">Update Aspect</button>
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
    }

    // Modal Kategori
    function openAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.remove('hidden');
    }

    function openEditCategoryModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || '';

        const form = document.getElementById('editCategoryForm');
        form.action = window.radarBaseUrl + '/master-assessment/category/' + id;
        document.getElementById('editCatName').value = name;

        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    // Modal Aspek
    function openAddAspectModal(btn) {
        const categoryId = btn.getAttribute('data-category-id');
        const categoryName = btn.getAttribute('data-category-name') || '';

        const form = document.getElementById('addAspectForm');
        form.action = window.radarBaseUrl + '/master-assessment/category/' + categoryId + '/aspect';
        document.getElementById('addAspectCategorySubtitle').innerText = 'Kategori: ' + categoryName;
        document.getElementById('addAspectName').value = '';
        document.getElementById('addAspectTargetScore').value = '5.0';

        document.getElementById('addAspectModal').classList.remove('hidden');
    }

    function openEditAspectModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || '';
        const targetScore = btn.getAttribute('data-target-score') || '5.0';

        const form = document.getElementById('editAspectForm');
        form.action = window.radarBaseUrl + '/master-assessment/aspect/' + id;
        document.getElementById('editAspectName').value = name;
        document.getElementById('editAspectTargetScore').value = targetScore;

        document.getElementById('editAspectModal').classList.remove('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['addCategoryModal', 'editCategoryModal', 'addAspectModal', 'editAspectModal'].forEach(closeModal);
        }
    });
</script>
@endpush