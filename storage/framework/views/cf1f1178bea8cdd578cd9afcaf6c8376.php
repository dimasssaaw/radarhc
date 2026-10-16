
<?php $__env->startSection('title', 'Detail Training'); ?>
<?php $__env->startSection('content'); ?>
    <div x-data="{
        showFormModal: <?php echo e($errors->any() ? 'true' : 'false'); ?>,
        showUploadModal: false,
        uploadEva: '',
        uploadId: null,
        formTitle: 'Tambah Training',
        formAction: '<?php echo e(route('training.store-history', $employee->id)); ?>',
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
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (response.ok || response.redirected) {
                    window.location.reload();
                } else if (response.status === 422) {
                    const data = await response.json();
                    this.formErrors = data.errors;
                } else {
                    alert('Terjadi kesalahan di server.');
                }
            } catch (err) {
                alert('Gagal terhubung ke server.');
            } finally {
                this.isSubmitting = false;
            }
        },
        openModal(title, kategori, data = null) {
            this.formTitle = title;
            this.formData.kategori = kategori;
            this.formErrors = {};
            if (data) {
                this.formAction = '<?php echo e(url('training/history')); ?>/' + data.id;
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
                this.formAction = '<?php echo e(route('training.store-history', $employee->id)); ?>';
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
                        <a href="<?php echo e(route('user-training.index')); ?>" class="hover:text-gray-700 transition-colors">Training</a>
                        <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
                        <span class="text-gray-900 font-medium">Detail Training</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('user-training.index')); ?>"
                        class="bg-white shadow-sm border border-gray-100 rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all hover:bg-gray-50 text-gray-700">
                        <i class="fas fa-arrow-left text-gray-400"></i> Back
                    </a>
                </div>
            </div>

            <!-- Employee Profile Header -->
            <?php echo $__env->make('components.employee-profile-header', ['employee' => $employee], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- 2. Grid Atas: Progress & Evaluation -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

                <!-- Bagian Kiri: Training Progress -->
                <div
                    class="lg:col-span-5 bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]">
                    <h3 class="text-sm font-bold text-gray-900 mb-6">Training Progress</h3>

                    <div class="space-y-6">
                        <!-- Mandatory -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Mandatory</span>
                                <span class="text-sm font-bold text-[#22c55e]"><?php echo e($achieveMandatory); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-[#22c55e] h-3 rounded-full transition-all duration-[450ms]"
                                    style="width: <?php echo e($achieveMandatory); ?>%"></div>
                            </div>
                        </div>

                        <!-- Skill -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Skill</span>
                                <span class="text-sm font-bold text-[#0ea5e9]"><?php echo e($achieveSkill); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-[#0ea5e9] h-3 rounded-full transition-all duration-[450ms]"
                                    style="width: <?php echo e($achieveSkill); ?>%"></div>
                            </div>
                        </div>

                        <!-- Manajerial -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Manajerial</span>
                                <span class="text-sm font-bold text-[#f43f5e]"><?php echo e($achieveManagerial); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-[#f43f5e] h-3 rounded-full transition-all duration-[450ms]"
                                    style="width: <?php echo e($achieveManagerial); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Kanan: Training Evaluation -->
                <div class="lg:col-span-7 bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]"
                    x-data="{ showEditTarget: false }">
                    <form id="training-target-form" action="<?php echo e(route('training.update-target', $employee->id)); ?>"
                        method="POST" @submit.prevent>
                        <?php echo csrf_field(); ?>
                    </form>
                    <div class="p-4 flex justify-between items-center border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900">Training Evaluation</h3>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_training')): ?>
<button @click="if (!showEditTarget) { showEditTarget = true; $event.preventDefault(); }"
                            :type="showEditTarget ? 'submit' : 'button'" form="training-target-form"
                            class="bg-[#06b6d4] hover:bg-[#0891b2] text-white px-4 py-1.5 rounded text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <i class="fas" :class="showEditTarget ? 'fa-save' : 'fa-edit'"></i>
                            <span x-text="showEditTarget ? 'Simpan' : 'Edit Data'"></span>
                        </button>
<?php endif; ?>
                    </div>

                    <div class="overflow-x-auto p-4 pt-0">
                        <table
                            class="w-full text-center text-sm border-collapse mt-4 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
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
                                        <span x-show="!showEditTarget"><?php echo e($training->target_mandatory); ?></span>
                                        <input x-show="showEditTarget" type="number" name="target_mandatory"
                                            form="training-target-form" value="<?php echo e($training->target_mandatory); ?>"
                                            min="0"
                                            class="w-20 mx-auto rounded border-gray-300 px-2 py-1 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-gray-600"><?php echo e($completedMandatory); ?></td>
                                    <td class="px-4 py-3 font-bold text-[#22c55e]"><?php echo e($achieveMandatory); ?>%</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-left font-semibold text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-[#0ea5e9]"></div> Skill
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span x-show="!showEditTarget"><?php echo e($training->target_skill); ?></span>
                                        <input x-show="showEditTarget" type="number" name="target_skill"
                                            form="training-target-form" value="<?php echo e($training->target_skill); ?>"
                                            min="0"
                                            class="w-20 mx-auto rounded border-gray-300 px-2 py-1 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-gray-600"><?php echo e($completedSkill); ?></td>
                                    <td class="px-4 py-3 font-bold text-[#0ea5e9]"><?php echo e($achieveSkill); ?>%</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-left font-semibold text-gray-800 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-[#f43f5e]"></div> Manajerial
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span x-show="!showEditTarget"><?php echo e($training->target_managerial); ?></span>
                                        <input x-show="showEditTarget" type="number" name="target_managerial"
                                            form="training-target-form" value="<?php echo e($training->target_managerial); ?>"
                                            min="0"
                                            class="w-20 mx-auto rounded border-gray-300 px-2 py-1 text-center text-sm">
                                    </td>
                                    <td class="px-4 py-3 text-gray-600"><?php echo e($completedManagerial); ?></td>
                                    <td class="px-4 py-3 font-bold text-[#f43f5e]"><?php echo e($achieveManagerial); ?>%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Modal Edit Target -->
                    <div x-show="false" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showEditTarget" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/60"
                                aria-hidden="true">
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div x-show="showEditTarget" @click.away="showEditTarget = false"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="inline-block px-4 pt-4 pb-3 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-5">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-cyan-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="fas fa-edit text-cyan-600"></i>
                                    </div>
                                    <div class="mt-2 text-center sm:mt-0 sm:ml-3 sm:text-left w-full">
                                        <h3 class="text-base font-medium leading-6 text-gray-900" id="modal-title">Edit
                                            Target Training</h3>
                                        <div class="mt-3">
                                            <form action="<?php echo e(route('training.update-target', $employee->id)); ?>"
                                                method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="space-y-3">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Target
                                                            Mandatory</label>
                                                        <input type="number" name="target_mandatory"
                                                            value="<?php echo e($training->target_mandatory); ?>"
                                                            class="w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm"
                                                            min="0">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Target
                                                            Skill</label>
                                                        <input type="number" name="target_skill"
                                                            value="<?php echo e($training->target_skill); ?>"
                                                            class="w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm"
                                                            min="0">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Target
                                                            Manajerial</label>
                                                        <input type="number" name="target_managerial"
                                                            value="<?php echo e($training->target_managerial); ?>"
                                                            class="w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm"
                                                            min="0">
                                                    </div>
                                                </div>
                                                <div class="mt-4 sm:flex sm:flex-row-reverse">
                                                    <button type="submit"
                                                        class="inline-flex justify-center w-full px-3 py-1.5 text-sm font-medium text-white bg-cyan-600 border border-transparent rounded-md shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:ml-3 sm:w-auto">Simpan
                                                        Perubahan</button>
                                                    <button type="button" @click="showEditTarget = false"
                                                        class="inline-flex justify-center w-full px-3 py-1.5 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:mt-0 sm:w-auto">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Tabel Mandatory Training -->
            <div
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Mandatory Training</h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_training')): ?>
<button @click="openModal('Tambah Mandatory Training', 'mandatory')"
                        class="bg-white border border-[#22c55e] text-[#22c55e] hover:bg-green-50 px-3 py-1.5 rounded text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
<?php endif; ?>
                </div>
                <div class="overflow-visible rounded-lg border border-gray-200">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-[#22c55e] text-white text-[11px] uppercase tracking-wider">
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
                            <?php $__empty_1 = true; $__currentLoopData = $riwayatMandatory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50" x-data="{ selectedEva: sessionStorage.getItem('selectedEva_rm_<?php echo e($rm->id); ?>') || 'eva1' }" x-init="$watch('selectedEva', val => sessionStorage.setItem('selectedEva_rm_<?php echo e($rm->id); ?>', val))">
                                    <td class="px-4 py-4 text-gray-800 font-medium"><?php echo e($rm->nama_training); ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <div x-show="selectedEva === 'eva1'">
                                            <form action="<?php echo e(route('training.update-status', $rm->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva1_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#22c55e] focus:border-[#22c55e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rm->eva1_status) || $rm->eva1_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rm->eva1_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rm->eva1_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rm->eva1_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rm->eva1_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div x-show="selectedEva === 'eva2'" style="display: none;">
                                            <form action="<?php echo e(route('training.update-status', $rm->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva2_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#22c55e] focus:border-[#22c55e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rm->eva2_status) || $rm->eva2_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rm->eva2_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rm->eva2_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rm->eva2_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rm->eva2_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div x-show="selectedEva === 'eva3'" style="display: none;">
                                            <form action="<?php echo e(route('training.update-status', $rm->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva3_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#22c55e] focus:border-[#22c55e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rm->eva3_status) || $rm->eva3_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rm->eva3_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rm->eva3_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rm->eva3_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rm->eva3_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-600">
                                        <?php echo e(\Carbon\Carbon::parse($rm->tanggal_training)->format('d M Y')); ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="relative inline-block w-full min-w-[100px]">
        <select x-model="selectedEva"
            class="w-full relative z-20 text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#22c55e] focus:border-[#22c55e] pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
            <option value="eva1">EVA 1 <?php echo e($rm->eva1_lampiran ? '✓' : '(empty)'); ?></option>
            <option value="eva2">EVA 2 <?php echo e($rm->eva2_lampiran ? '✓' : '(empty)'); ?></option>
            <option value="eva3">EVA 3 <?php echo e($rm->eva3_lampiran ? '✓' : '(empty)'); ?></option>
        </select>
    </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div x-show="selectedEva === 'eva1'">
                                            <?php if($rm->eva1_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rm->eva1_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 1</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva1', <?php echo e($rm->id); ?>)"
                                                    class="text-xs border border-[#22c55e] text-[#22c55e] px-2 py-1 rounded hover:bg-green-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                        <div x-show="selectedEva === 'eva2'" style="display: none;">
                                            <?php if($rm->eva2_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rm->eva2_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 2</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva2', <?php echo e($rm->id); ?>)"
                                                    class="text-xs border border-[#22c55e] text-[#22c55e] px-2 py-1 rounded hover:bg-green-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                        <div x-show="selectedEva === 'eva3'" style="display: none;">
                                            <?php if($rm->eva3_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rm->eva3_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 3</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva3', <?php echo e($rm->id); ?>)"
                                                    class="text-xs border border-[#22c55e] text-[#22c55e] px-2 py-1 rounded hover:bg-green-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center align-middle">
                                        <div class="flex items-center justify-center gap-3">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_training')): ?>
<button type="button"
                                                @click="openModal('Edit Mandatory Training', 'mandatory', <?php echo \Illuminate\Support\Js::from($rm)->toHtml() ?>)"
                                                class="text-gray-400 hover:text-gray-700 transition-colors"
                                                title="Edit training"><i class="fas fa-pen"></i></button>
<?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hapus_training')): ?>
<form action="<?php echo e(route('training.destroy-history', $rm->id)); ?>"
                                                method="POST" hx-boost="false"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');"
                                                class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Hapus training"><i class="fas fa-trash"></i></button>
                                            </form>
<?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No data available
                                        Mandatory Training</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Tabel Skill Training -->
            <div
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Skill Training</h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_training')): ?>
<button @click="openModal('Tambah Skill Training', 'skill')"
                        class="bg-white border border-[#0ea5e9] text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
<?php endif; ?>
                </div>
                <div class="overflow-visible rounded-lg border border-gray-200">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-[#0ea5e9] text-white text-[11px] uppercase tracking-wider">
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
                            <?php $__empty_1 = true; $__currentLoopData = $riwayatSkill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <!-- Struktur baris sama dengan Mandatory, variabel disesuaikan -->
                                <tr class="hover:bg-gray-50" x-data="{ selectedEva: sessionStorage.getItem('selectedEva_rs_<?php echo e($rs->id); ?>') || 'eva1' }" x-init="$watch('selectedEva', val => sessionStorage.setItem('selectedEva_rs_<?php echo e($rs->id); ?>', val))">
                                    <td class="px-4 py-4 text-gray-800 font-medium"><?php echo e($rs->nama_training); ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <div x-show="selectedEva === 'eva1'">
                                            <form action="<?php echo e(route('training.update-status', $rs->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva1_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#0ea5e9] focus:border-[#0ea5e9] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rs->eva1_status) || $rs->eva1_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rs->eva1_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rs->eva1_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rs->eva1_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rs->eva1_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div x-show="selectedEva === 'eva2'" style="display: none;">
                                            <form action="<?php echo e(route('training.update-status', $rs->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva2_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#0ea5e9] focus:border-[#0ea5e9] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rs->eva2_status) || $rs->eva2_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rs->eva2_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rs->eva2_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rs->eva2_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rs->eva2_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div x-show="selectedEva === 'eva3'" style="display: none;">
                                            <form action="<?php echo e(route('training.update-status', $rs->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva3_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#0ea5e9] focus:border-[#0ea5e9] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rs->eva3_status) || $rs->eva3_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rs->eva3_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rs->eva3_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rs->eva3_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rs->eva3_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-600">
                                        <?php echo e(\Carbon\Carbon::parse($rs->tanggal_training)->format('d M Y')); ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="relative inline-block w-full min-w-[100px]">
                                            <select x-model="selectedEva"
                                                class="w-full relative z-20 text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#0ea5e9] focus:border-[#0ea5e9] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                <option value="eva1">EVA 1 <?php echo e($rs->eva1_lampiran ? '✓' : '(empty)'); ?></option>
                                                <option value="eva2">EVA 2 <?php echo e($rs->eva2_lampiran ? '✓' : '(empty)'); ?></option>
                                                <option value="eva3">EVA 3 <?php echo e($rs->eva3_lampiran ? '✓' : '(empty)'); ?></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div x-show="selectedEva === 'eva1'">
                                            <?php if($rs->eva1_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rs->eva1_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 1</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva1', <?php echo e($rs->id); ?>)"
                                                    class="text-xs border border-[#0ea5e9] text-[#0ea5e9] px-2 py-1 rounded hover:bg-sky-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                        <div x-show="selectedEva === 'eva2'" style="display: none;">
                                            <?php if($rs->eva2_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rs->eva2_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 2</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva2', <?php echo e($rs->id); ?>)"
                                                    class="text-xs border border-[#0ea5e9] text-[#0ea5e9] px-2 py-1 rounded hover:bg-sky-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                        <div x-show="selectedEva === 'eva3'" style="display: none;">
                                            <?php if($rs->eva3_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rs->eva3_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 3</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva3', <?php echo e($rs->id); ?>)"
                                                    class="text-xs border border-[#0ea5e9] text-[#0ea5e9] px-2 py-1 rounded hover:bg-sky-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center align-middle">
                                        <div class="flex items-center justify-center gap-3">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_training')): ?>
<button type="button"
                                                @click="openModal('Edit Skill Training', 'skill', <?php echo \Illuminate\Support\Js::from($rs)->toHtml() ?>)"
                                                class="text-gray-400 hover:text-gray-700 transition-colors"
                                                title="Edit training"><i class="fas fa-pen"></i></button>
<?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hapus_training')): ?>
<form action="<?php echo e(route('training.destroy-history', $rs->id)); ?>"
                                                method="POST" hx-boost="false"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');"
                                                class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Hapus training"><i class="fas fa-trash"></i></button>
                                            </form>
<?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No data available Skill
                                        Training</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. Tabel Managerial Training -->
            <div
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_30px_rgb(0,0,0,0.10)]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Managerial Training</h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_training')): ?>
<button @click="openModal('Tambah Managerial Training', 'managerial')"
                        class="bg-white border border-[#f43f5e] text-[#f43f5e] hover:bg-rose-50 px-3 py-1.5 rounded text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
<?php endif; ?>
                </div>
                <div class="overflow-visible rounded-lg border border-gray-200">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-[#f43f5e] text-white text-[11px] uppercase tracking-wider">
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
                            <?php $__empty_1 = true; $__currentLoopData = $riwayatManagerial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rmn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50" x-data="{ selectedEva: sessionStorage.getItem('selectedEva_rmn_<?php echo e($rmn->id); ?>') || 'eva1' }" x-init="$watch('selectedEva', val => sessionStorage.setItem('selectedEva_rmn_<?php echo e($rmn->id); ?>', val))">
                                    <td class="px-4 py-4 text-gray-800 font-medium"><?php echo e($rmn->nama_training); ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <div x-show="selectedEva === 'eva1'">
                                            <form action="<?php echo e(route('training.update-status', $rmn->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva1_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#f43f5e] focus:border-[#f43f5e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rmn->eva1_status) || $rmn->eva1_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rmn->eva1_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rmn->eva1_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rmn->eva1_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rmn->eva1_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div x-show="selectedEva === 'eva2'" style="display: none;">
                                            <form action="<?php echo e(route('training.update-status', $rmn->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva2_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#f43f5e] focus:border-[#f43f5e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rmn->eva2_status) || $rmn->eva2_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rmn->eva2_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rmn->eva2_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rmn->eva2_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rmn->eva2_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div x-show="selectedEva === 'eva3'" style="display: none;">
                                            <form action="<?php echo e(route('training.update-status', $rmn->id)); ?>" method="POST" @submit.prevent>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="relative inline-block w-full min-w-[110px]">
                                                    <select name="eva3_status" @change="fetch($el.form.action, { method: 'POST', body: new FormData($el.form), headers: {'X-Requested-With': 'XMLHttpRequest'} }).then(r => r.text()).then(h => { let doc = new DOMParser().parseFromString(h, 'text/html'); let target = document.querySelector('.min-h-full'); target.innerHTML = doc.querySelector('.min-h-full').innerHTML; setTimeout(() => { if(typeof initTomSelect !== 'undefined') initTomSelect(); }, 50); })"
                                                        class="w-full text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#f43f5e] focus:border-[#f43f5e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                        <option value="" disabled <?php if(empty($rmn->eva3_status) || $rmn->eva3_status === 'pending'): echo 'selected'; endif; ?>>-- Pilih Status --</option>
                                                        <option value="completed" <?php if($rmn->eva3_status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                                                        <option value="in_progress" <?php if($rmn->eva3_status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
                                                        <option value="remedial" <?php if($rmn->eva3_status === 'remedial'): echo 'selected'; endif; ?>>Remedial</option>
                                                        <option value="not_started" <?php if($rmn->eva3_status === 'not_started'): echo 'selected'; endif; ?>>Not Started</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                        <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-600">
                                        <?php echo e(\Carbon\Carbon::parse($rmn->tanggal_training)->format('d M Y')); ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="relative inline-block w-full min-w-[100px]">
                                            <select x-model="selectedEva"
                                                class="w-full relative z-20 text-xs border border-gray-200 rounded-lg text-gray-700 bg-gray-50 focus:ring-[#f43f5e] focus:border-[#f43f5e] appearance-none pr-7 pl-2 py-1.5 cursor-pointer transition-shadow hover:bg-white hover:border-gray-300">
                                                <option value="eva1">EVA 1 <?php echo e($rmn->eva1_lampiran ? '✓' : '(empty)'); ?></option>
                                                <option value="eva2">EVA 2 <?php echo e($rmn->eva2_lampiran ? '✓' : '(empty)'); ?></option>
                                                <option value="eva3">EVA 3 <?php echo e($rmn->eva3_lampiran ? '✓' : '(empty)'); ?></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div x-show="selectedEva === 'eva1'">
                                            <?php if($rmn->eva1_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rmn->eva1_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 1</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva1', <?php echo e($rmn->id); ?>)"
                                                    class="text-xs border border-[#f43f5e] text-[#f43f5e] px-2 py-1 rounded hover:bg-rose-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                        <div x-show="selectedEva === 'eva2'" style="display: none;">
                                            <?php if($rmn->eva2_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rmn->eva2_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 2</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva2', <?php echo e($rmn->id); ?>)"
                                                    class="text-xs border border-[#f43f5e] text-[#f43f5e] px-2 py-1 rounded hover:bg-rose-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                        <div x-show="selectedEva === 'eva3'" style="display: none;">
                                            <?php if($rmn->eva3_lampiran): ?>
                                                <a href="<?php echo e(Storage::disk('hrg')->url($rmn->eva3_lampiran)); ?>" target="_blank"
                                                    class="text-xs text-gray-600 hover:text-blue-600 flex items-center justify-center gap-1"><i
                                                        class="fas fa-file-pdf text-red-500"></i> Lampiran EVA 3</a>
                                            <?php else: ?>
                                                <button type="button"
                                                    @click="openUploadModal('eva3', <?php echo e($rmn->id); ?>)"
                                                    class="text-xs border border-[#f43f5e] text-[#f43f5e] px-2 py-1 rounded hover:bg-rose-50">+
                                                    Lampiran</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center align-middle">
                                        <div class="flex items-center justify-center gap-3">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_training')): ?>
<button type="button"
                                                @click="openModal('Edit Managerial Training', 'managerial', <?php echo \Illuminate\Support\Js::from($rmn)->toHtml() ?>)"
                                                class="text-gray-400 hover:text-gray-700 transition-colors"
                                                title="Edit training"><i class="fas fa-pen"></i></button>
<?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hapus_training')): ?>
<form action="<?php echo e(route('training.destroy-history', $rmn->id)); ?>"
                                                method="POST" hx-boost="false"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');"
                                                class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Hapus training"><i class="fas fa-trash"></i></button>
                                            </form>
<?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No data available
                                        Managerial Training</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="_method" :value="formMethod">
                            <input type="hidden" name="kategori" :value="formData.kategori">

                            <!-- Header -->
                            <div class="px-5 py-2.5 flex justify-between items-center border-b border-gray-100 bg-white">
                                <h2 class="text-[15px] font-bold text-gray-800" x-text="formTitle">Tambah Training</h2>
                                <button @click="showFormModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-600 p-1 transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="px-5 py-4 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar bg-white">

                                <!-- Form Training -->
                                <div class="space-y-3.5">
                                    <!-- Nama Training -->
                                    <div>
                                        <label class="block text-[12.5px] font-bold text-gray-600 mb-1">Nama Training</label>
                                        <input type="text" name="nama_training" x-model="formData.nama"
                                            placeholder="cth. Advanced Negotiation Skills"
                                            :class="formErrors.nama_training ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-200 focus:ring-sky-500 focus:border-sky-500'"
                                            class="w-full border rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-1 placeholder-gray-400 font-semibold bg-white">
                                        <p x-show="formErrors.nama_training" class="text-red-500 text-xs mt-1" x-text="formErrors.nama_training ? formErrors.nama_training[0] : ''" style="display: none;"></p>
                                    </div>

                                    <!-- Tanggal -->
                                    <div>
                                        <label class="block text-[12.5px] font-bold text-gray-600 mb-1">Tanggal</label>
                                        <div class="relative">
                                            <input type="date" name="tanggal_training"
                                                x-model="formData.tanggal"
                                                :class="formErrors.tanggal_training ? 'border-red-500 focus:ring-red-500' : 'border-gray-200 focus:ring-sky-500'"
                                                class="w-full border rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-1 bg-white">
                                        </div>
                                        <p x-show="formErrors.tanggal_training" class="text-red-500 text-xs mt-1" x-text="formErrors.tanggal_training ? formErrors.tanggal_training[0] : ''" style="display: none;"></p>
                                    </div>
                                </div>

                                <hr class="border-gray-100">

                                <!-- Evaluasi Cards -->
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-800 mb-2.5">Evaluasi</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button type="button" @click="activeEvaTab = 'eva1'"
                                            :class="activeEvaTab === 'eva1' ?
                                                'bg-sky-50 text-sky-600 border-sky-300' :
                                                'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'"
                                            class="rounded-lg border py-1.5 text-[12.5px] font-bold transition-colors">
                                            EVA 1
                                        </button>
                                        <button type="button" @click="activeEvaTab = 'eva2'"
                                            :class="activeEvaTab === 'eva2' ?
                                                'bg-sky-50 text-sky-600 border-sky-300' :
                                                'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'"
                                            class="rounded-lg border py-1.5 text-[12.5px] font-bold transition-colors">
                                            EVA 2
                                        </button>
                                        <button type="button" @click="activeEvaTab = 'eva3'"
                                            :class="activeEvaTab === 'eva3' ?
                                                'bg-sky-50 text-sky-600 border-sky-300' :
                                                'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'"
                                            class="rounded-lg border py-1.5 text-[12.5px] font-bold transition-colors">
                                            EVA 3
                                        </button>
                                    </div>
                                </div>

                                <!-- Tab EVA 1 -->
                                <div x-show="activeEvaTab === 'eva1'"
                                    class="bg-[#f7f9f8] border border-gray-100 rounded-xl p-4 mt-1">
                                    <h3 class="text-[13px] font-bold text-gray-800 mb-2">Evaluasi EVA 1</h3>
                                    
                                    <div class="mb-4">
                                        <label class="block text-[12px] font-bold text-gray-500 mb-1.5">Status EVA 1</label>
                                        <select name="eva1_status" x-model="formData.eva1_status"
                                            :class="formErrors.eva1_status ? 'border-red-500 focus:ring-red-500' : 'border-gray-200 focus:ring-sky-500'"
                                            class="native-select w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-1">
                                            <option value="" disabled>-- Pilih Status --</option>
                                            <option value="pending">Pending</option>
                                            <option value="not_started">Not Started</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="remedial">Remedial</option>
                                        </select>
                                        <p x-show="formErrors.eva1_status" class="text-red-500 text-xs mt-1" x-text="formErrors.eva1_status ? formErrors.eva1_status[0] : ''" style="display: none;"></p>
                                    </div>

                                    <div x-data="{ newFile: null }">
                                        <label class="block text-[12px] font-bold text-gray-500 mb-1.5">Lampiran</label>
                                        <input id="eva1_file" type="file" name="eva1_file" accept="application/pdf" class="sr-only" @change="newFile = $event.target.files[0] ? $event.target.files[0].name : null">
                                        
                                        <!-- View Mode -->
                                        <div x-show="formData.eva1_lampiran || newFile" style="display: none;" class="flex items-center justify-between border border-gray-200 bg-white rounded-lg p-3">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <i class="fas fa-file-pdf text-emerald-600 text-xl shrink-0"></i>
                                                <span class="text-sm font-bold text-gray-800 truncate" x-text="newFile ? newFile : getOriginalFileName(formData.eva1_lampiran)"></span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <a x-show="formData.eva1_lampiran && !newFile" :href="'<?php echo e(Storage::disk('hrg')->url('')); ?>' + formData.eva1_lampiran" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-gray-600 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                                    Lihat PDF
                                                </a>
                                                <label for="eva1_file" class="px-3 py-1.5 text-xs font-semibold text-gray-800 border border-gray-300 rounded hover:bg-gray-50 transition-colors cursor-pointer flex items-center gap-1.5">
                                                    <i class="fas fa-sync-alt text-[10px]"></i> Ganti File
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Upload Mode -->
                                        <label x-show="!formData.eva1_lampiran && !newFile" for="eva1_file"
                                            :class="formErrors.eva1_file ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white'"
                                            class="border border-dashed rounded-lg p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-file-alt text-gray-400 text-xl mb-1.5"></i>
                                            <span class="text-sky-600 font-bold text-[12px] mb-0.5">Klik untuk upload file</span>
                                            <span class="text-[10px] font-bold text-gray-400">PDF, maksimal 10 MB</span>
                                        </label>
                                        <p x-show="formErrors.eva1_file" class="text-red-500 text-xs mt-1" x-text="formErrors.eva1_file ? formErrors.eva1_file[0] : ''" style="display: none;"></p>
                                    </div>
                                </div>

                                <!-- Tab EVA 2 -->
                                <div x-show="activeEvaTab === 'eva2'" style="display: none;"
                                    class="bg-[#f7f9f8] border border-gray-100 rounded-xl p-4 mt-1">
                                    <h3 class="text-[13px] font-bold text-gray-800 mb-2">Evaluasi EVA 2</h3>
                                    
                                    <div class="mb-4">
                                        <label class="block text-[12px] font-bold text-gray-500 mb-1.5">Status EVA 2</label>
                                        <select name="eva2_status" x-model="formData.eva2_status"
                                            :class="formErrors.eva2_status ? 'border-red-500 focus:ring-red-500' : 'border-gray-200 focus:ring-sky-500'"
                                            class="native-select w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-1">
                                            <option value="" disabled>-- Pilih Status --</option>
                                            <option value="pending">Pending</option>
                                            <option value="not_started">Not Started</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="remedial">Remedial</option>
                                        </select>
                                        <p x-show="formErrors.eva2_status" class="text-red-500 text-xs mt-1" x-text="formErrors.eva2_status ? formErrors.eva2_status[0] : ''" style="display: none;"></p>
                                    </div>

                                    <div x-data="{ newFile: null }">
                                        <label class="block text-[12px] font-bold text-gray-500 mb-1.5">Lampiran</label>
                                        <input id="eva2_file" type="file" name="eva2_file" accept="application/pdf" class="sr-only" @change="newFile = $event.target.files[0] ? $event.target.files[0].name : null">
                                        
                                        <!-- View Mode -->
                                        <div x-show="formData.eva2_lampiran || newFile" style="display: none;" class="flex items-center justify-between border border-gray-200 bg-white rounded-lg p-3">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <i class="fas fa-file-pdf text-emerald-600 text-xl shrink-0"></i>
                                                <span class="text-sm font-bold text-gray-800 truncate" x-text="newFile ? newFile : getOriginalFileName(formData.eva2_lampiran)"></span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <a x-show="formData.eva2_lampiran && !newFile" :href="'<?php echo e(Storage::disk('hrg')->url('')); ?>' + formData.eva2_lampiran" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-gray-600 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                                    Lihat PDF
                                                </a>
                                                <label for="eva2_file" class="px-3 py-1.5 text-xs font-semibold text-gray-800 border border-gray-300 rounded hover:bg-gray-50 transition-colors cursor-pointer flex items-center gap-1.5">
                                                    <i class="fas fa-sync-alt text-[10px]"></i> Ganti File
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Upload Mode -->
                                        <label x-show="!formData.eva2_lampiran && !newFile" for="eva2_file"
                                            :class="formErrors.eva2_file ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white'"
                                            class="border border-dashed rounded-lg p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-file-alt text-gray-400 text-xl mb-1.5"></i>
                                            <span class="text-sky-600 font-bold text-[12px] mb-0.5">Klik untuk upload file</span>
                                            <span class="text-[10px] font-bold text-gray-400">PDF, maksimal 10 MB</span>
                                        </label>
                                        <p x-show="formErrors.eva2_file" class="text-red-500 text-xs mt-1" x-text="formErrors.eva2_file ? formErrors.eva2_file[0] : ''" style="display: none;"></p>
                                    </div>
                                </div>

                                <!-- Tab EVA 3 -->
                                <div x-show="activeEvaTab === 'eva3'" style="display: none;"
                                    class="bg-[#f7f9f8] border border-gray-100 rounded-xl p-4 mt-1">
                                    <h3 class="text-[13px] font-bold text-gray-800 mb-2">Evaluasi EVA 3</h3>
                                    
                                    <div class="mb-4">
                                        <label class="block text-[12px] font-bold text-gray-500 mb-1.5">Status EVA 3</label>
                                        <select name="eva3_status" x-model="formData.eva3_status"
                                            :class="formErrors.eva3_status ? 'border-red-500 focus:ring-red-500' : 'border-gray-200 focus:ring-sky-500'"
                                            class="native-select w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-1">
                                            <option value="" disabled>-- Pilih Status --</option>
                                            <option value="pending">Pending</option>
                                            <option value="not_started">Not Started</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="remedial">Remedial</option>
                                        </select>
                                        <p x-show="formErrors.eva3_status" class="text-red-500 text-xs mt-1" x-text="formErrors.eva3_status ? formErrors.eva3_status[0] : ''" style="display: none;"></p>
                                    </div>

                                    <div x-data="{ newFile: null }">
                                        <label class="block text-[12px] font-bold text-gray-500 mb-1.5">Lampiran</label>
                                        <input id="eva3_file" type="file" name="eva3_file" accept="application/pdf" class="sr-only" @change="newFile = $event.target.files[0] ? $event.target.files[0].name : null">
                                        
                                        <!-- View Mode -->
                                        <div x-show="formData.eva3_lampiran || newFile" style="display: none;" class="flex items-center justify-between border border-gray-200 bg-white rounded-lg p-3">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <i class="fas fa-file-pdf text-emerald-600 text-xl shrink-0"></i>
                                                <span class="text-sm font-bold text-gray-800 truncate" x-text="newFile ? newFile : getOriginalFileName(formData.eva3_lampiran)"></span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <a x-show="formData.eva3_lampiran && !newFile" :href="'<?php echo e(Storage::disk('hrg')->url('')); ?>' + formData.eva3_lampiran" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-gray-600 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                                                    Lihat PDF
                                                </a>
                                                <label for="eva3_file" class="px-3 py-1.5 text-xs font-semibold text-gray-800 border border-gray-300 rounded hover:bg-gray-50 transition-colors cursor-pointer flex items-center gap-1.5">
                                                    <i class="fas fa-sync-alt text-[10px]"></i> Ganti File
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Upload Mode -->
                                        <label x-show="!formData.eva3_lampiran && !newFile" for="eva3_file"
                                            :class="formErrors.eva3_file ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white'"
                                            class="border border-dashed rounded-lg p-5 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-file-alt text-gray-400 text-xl mb-1.5"></i>
                                            <span class="text-sky-600 font-bold text-[12px] mb-0.5">Klik untuk upload file</span>
                                            <span class="text-[10px] font-bold text-gray-400">PDF, maksimal 10 MB</span>
                                        </label>
                                        <p x-show="formErrors.eva3_file" class="text-red-500 text-xs mt-1" x-text="formErrors.eva3_file ? formErrors.eva3_file[0] : ''" style="display: none;"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-5 py-2.5 border-t border-gray-100 flex justify-end gap-2.5 bg-gray-50/50">
                                <button type="button" @click="showFormModal = false"
                                    class="px-3.5 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-[13px] font-medium hover:bg-gray-100 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    :disabled="isSubmitting"
                                    class="px-4 py-1.5 bg-[#0ea5e9] text-white rounded-lg text-[13px] font-bold hover:bg-[#0284c7] transition-all shadow-sm flex items-center justify-center gap-2"
                                    :class="{'opacity-70 cursor-wait': isSubmitting}">
                                    <span x-show="!isSubmitting">Simpan Perubahan</span>
                                    <span x-show="isSubmitting" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
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
                        class="relative bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden text-left w-full sm:max-w-md my-auto">
                        
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Upload Lampiran <span
                                    x-text="uploadEva.toUpperCase()"></span></h3>
                            <button type="button" @click="showUploadModal = false"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                                aria-label="Tutup modal">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                        <form :action="'<?php echo e(url('training/history')); ?>/' + uploadId + '/upload-eva'"
                            method="POST" enctype="multipart/form-data" hx-boost="false"
                            x-data="{ isUploading: false }" @submit="isUploading = true">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="eva" :value="uploadEva">
                            <div class="p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File PDF (Max
                                    10MB)</label>
                                <input type="file" name="file" accept="application/pdf" required
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded p-2">
                            </div>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                                <button type="button" @click="showUploadModal = false"
                                    class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</button>
                                <button type="submit" :disabled="isUploading"
                                    class="px-5 py-2.5 bg-[#0ea5e9] text-white rounded-lg text-sm font-medium shadow-sm hover:bg-[#0284c7] transition-all flex items-center justify-center gap-2"
                                    :class="{'opacity-70 cursor-wait': isUploading}">
                                    <span x-show="!isUploading">Upload</span>
                                    <span x-show="isUploading" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Mengunggah...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('modals'); ?>
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
    <?php $__env->stopPush(); ?>






<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views/user/training/show.blade.php ENDPATH**/ ?>