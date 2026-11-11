
<?php $__env->startSection('title', 'Assessment Report - ' . $karyawan->name); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="<?php echo e(route('user-assessment-report.index')); ?>" class="hover:text-gray-700 transition-colors">Assessment Report</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <?php if(isset($karyawan->id)): ?>
                <a href="<?php echo e(route('user-assessment-report.show-employee', $karyawan->id)); ?>" class="hover:text-gray-700 transition-colors">Employee Assessment</a>
                <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
            <span class="text-gray-900 font-medium">Detail Assessment Report</span>
        </div>

    </div>
    <div class="flex gap-3">
        <a href="<?php echo e(isset($karyawan->id) ? route('user-assessment-report.show-employee', $karyawan->id) : route('user-assessment-report.index')); ?>" class="bg-[#0ea5e9] hover:bg-[#0284c7] shadow-sm hover:shadow-md rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-white">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Employee Profile Header -->
<?php echo $__env->make('components.employee-profile-header', ['employee' => $karyawan], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Assessment Info Full Width Row -->
<div class="mb-6">
    <!-- Card Assessment Info -->
    <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)]">
        
        <!-- Header & Icon Edit -->
        <div class="flex justify-between items-center border-b border-gray-50 pb-3 mb-4">
            <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-sm"></div> Assessment Info
            </h3>
            
            <!-- Tombol Edit (Arahkan ke modal atau halaman form edit) -->
            <?php if($activeReport): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_assessment')): ?>
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-assessment-info').classList.remove('hidden');"
               class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5" 
               title="<?php echo e($activeReport ? 'Edit Assessment Info' : 'Isi Assessment Info'); ?>">
                <i class="fas <?php echo e($activeReport ? 'fa-pen' : 'fa-plus'); ?>"></i>
            </a>
                <?php endif; ?>
            <?php else: ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_assessment')): ?>
                <a href="#" onclick="event.preventDefault(); document.getElementById('modal-assessment-info').classList.remove('hidden');"
               class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-3 py-1.5 rounded text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5" 
               title="<?php echo e($activeReport ? 'Edit Assessment Info' : 'Isi Assessment Info'); ?>">
                <i class="fas <?php echo e($activeReport ? 'fa-pen' : 'fa-plus'); ?>"></i>
            </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Isi Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Tahun -->
            <div class="flex items-center p-3 rounded-xl bg-sky-50 border border-sky-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-white text-sky-500 flex items-center justify-center mr-3 shadow-sm border border-sky-50">
                    <i class="far fa-calendar-alt text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-sky-600/80 tracking-wider uppercase mb-0.5">Year</p>
                    <p class="text-sm font-bold text-gray-800">
                        <?php echo e($activeReport ? $activeReport->tahun : '-'); ?>

                    </p>
                </div>
            </div>

            <!-- Periode -->
            <div class="flex items-center p-3 rounded-xl bg-purple-50 border border-purple-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-white text-purple-500 flex items-center justify-center mr-3 shadow-sm border border-purple-50">
                    <i class="far fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-purple-600/80 tracking-wider uppercase mb-0.5">Period</p>
                    <p class="text-sm font-bold text-gray-800">
                        <?php echo e($activeReport && $activeReport->periode ? $activeReport->periode : '-'); ?>

                    </p>
                </div>
            </div>

            <!-- Provider -->
            <div class="flex items-center p-3 rounded-xl bg-emerald-50 border border-emerald-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-white text-emerald-500 flex items-center justify-center mr-3 shadow-sm border border-emerald-50">
                    <i class="far fa-building text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-emerald-600/80 tracking-wider uppercase mb-0.5">Provider / Assessor</p>
                    <p class="text-sm font-bold text-gray-800">
                        <?php echo e($activeReport && $activeReport->provider ? $activeReport->provider : '-'); ?>

                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $__env->startPush('modals'); ?>
<!-- Modal Edit Assessment Info -->
<div id="modal-assessment-info" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-gray-900 text-lg">Assessment Info</h3>
            <button type="button" onclick="document.getElementById('modal-assessment-info').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="<?php echo e($activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php if($activeReport): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>
            
            <input type="hidden" name="id_karyawan" value="<?php echo e($karyawan->id); ?>">
            <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <input type="number" name="tahun" value="<?php echo e($activeReport->tahun ?? ''); ?>" 
                           class="w-full rounded-lg border border-gray-200 shadow-sm focus:border-[#0ea5e9] focus:ring focus:ring-[#0ea5e9]/20 text-sm py-2 px-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                    <input type="text" name="periode" value="<?php echo e($activeReport->periode ?? ''); ?>" 
                           class="w-full rounded-lg border border-gray-200 shadow-sm focus:border-[#0ea5e9] focus:ring focus:ring-[#0ea5e9]/20 text-sm py-2 px-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provider / Assessor</label>
                    <input type="text" name="provider" value="<?php echo e($activeReport->provider ?? ''); ?>" 
                           class="w-full rounded-lg border border-gray-200 shadow-sm focus:border-[#0ea5e9] focus:ring focus:ring-[#0ea5e9]/20 text-sm py-2 px-3 transition-colors">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-assessment-info').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] border border-transparent rounded-lg hover:bg-[#0284c7] transition-colors shadow-sm">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal KB -->
<div id="modal-kb" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Cognitive Ability</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Score input scale 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-kb').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="<?php echo e($activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store')); ?>" method="POST" class="px-4 py-3">
            <?php echo csrf_field(); ?>
            <?php if($activeReport): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($karyawan->id); ?>">
            <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">General Intelligence</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_kecerdasan_umum" value="<?php echo e($activeReport->kb_kecerdasan_umum ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Thinking Flexibility</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_fleksibilitas_berfikir" value="<?php echo e($activeReport->kb_fleksibilitas_berfikir ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Conceptual Ability</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_kemampuan_konseptual" value="<?php echo e($activeReport->kb_kemampuan_konseptual ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Analysis & Synthesis</label>
                    <input type="number" step="0.1" min="0" max="5" name="kb_analisa_sintesa" value="<?php echo e($activeReport->kb_analisa_sintesa ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-kb').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal SK -->
<div id="modal-sk" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Work Attitude</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Score input scale 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-sk').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="<?php echo e($activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store')); ?>" method="POST" class="px-4 py-3">
            <?php echo csrf_field(); ?>
            <?php if($activeReport): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($karyawan->id); ?>">
            <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Work Systematics</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_sistematika_kerja" value="<?php echo e($activeReport->sk_sistematika_kerja ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Work Endurance</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_daya_tahan" value="<?php echo e($activeReport->sk_daya_tahan ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Work Flexibility</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_fleksibilitas" value="<?php echo e($activeReport->sk_fleksibilitas ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Attention to Detail</label>
                    <input type="number" step="0.1" min="0" max="5" name="sk_perhatian_detail" value="<?php echo e($activeReport->sk_perhatian_detail ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-sk').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal KP -->
<div id="modal-kp" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Personality</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Score input scale 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-kp').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="<?php echo e($activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store')); ?>" method="POST" class="px-4 py-3">
            <?php echo csrf_field(); ?>
            <?php if($activeReport): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($karyawan->id); ?>">
            <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Emotional Stability</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_stabilitas_emosi" value="<?php echo e($activeReport->kp_stabilitas_emosi ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Leadership</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_kepemimpinan" value="<?php echo e($activeReport->kp_kepemimpinan ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Adaptability</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_penyesuaian_diri" value="<?php echo e($activeReport->kp_penyesuaian_diri ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Integrity</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_integritas" value="<?php echo e($activeReport->kp_integritas ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Self Confidence</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_kepercayaan_diri" value="<?php echo e($activeReport->kp_kepercayaan_diri ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Work Motivation</label>
                    <input type="number" step="0.1" min="0" max="5" name="kp_motivasi_kerja" value="<?php echo e($activeReport->kp_motivasi_kerja ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-kp').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal MS -->
<div id="modal-ms" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-fade-in-up">
        <div class="px-4 pt-3 pb-2 flex justify-between items-start bg-white border-b border-gray-100">
            <div>
                <h3 class="font-bold text-[#1e293b] text-base">Managerial Skill</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Score input scale 0-5.0</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-ms').classList.add('hidden')" class="w-6 h-6 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form action="<?php echo e($activeReport ? route('assessment-report.update', $activeReport->id) : route('assessment-report.store')); ?>" method="POST" class="px-4 py-3">
            <?php echo csrf_field(); ?>
            <?php if($activeReport): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($karyawan->id); ?>">
            <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
            
            <div class="grid grid-cols-2 gap-x-3 gap-y-3 max-h-[50vh] overflow-y-auto custom-scrollbar pr-1 pb-1">
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Integrity & Ethics</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_integrity_ethics" value="<?php echo e($activeReport->ms_integrity_ethics ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Fostering Team Work</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_fostering_team_work" value="<?php echo e($activeReport->ms_fostering_team_work ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Excellence</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_excellence" value="<?php echo e($activeReport->ms_excellence ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Empowerment</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_empowerment" value="<?php echo e($activeReport->ms_empowerment ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Compassion</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_compassion" value="<?php echo e($activeReport->ms_compassion ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Managing Changes</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_managing_changes" value="<?php echo e($activeReport->ms_managing_changes ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Humility</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_humility" value="<?php echo e($activeReport->ms_humility ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Planning & Controlling</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_planning_controlling" value="<?php echo e($activeReport->ms_planning_controlling ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
                <div class="col-span-2 flex flex-col justify-end">
                    <label class="block text-[11px] leading-tight font-semibold text-[#334155] mb-1">Analysis & Decision Making</label>
                    <input type="number" step="0.1" min="0" max="5" name="ms_analysis_decision" value="<?php echo e($activeReport->ms_analysis_decision ?? ''); ?>" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 font-medium py-1 px-2 text-sm text-gray-800" placeholder="4.5">
                </div>
            </div>
            
            <div class="mt-3 flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-ms').classList.add('hidden')" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-[#0ea5e9] border border-transparent rounded-md hover:bg-[#0284c7] transition-colors shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-save text-[10px]"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopPush(); ?>

<?php
    $themeMap = [
        'KB' => [
            'cardBg' => 'bg-[#eef8f1]',
            'cardBorder' => 'border-[#d1ebd7]',
            'accent' => '#147a42',
            'chartBg' => 'rgba(20, 122, 66, 0.2)',
            'chartBorder' => 'rgba(20, 122, 66, 1)',
        ],
        'SK' => [
            'cardBg' => 'bg-[#fcf7ec]',
            'cardBorder' => 'border-[#f0e4cc]',
            'accent' => '#b58514',
            'chartBg' => 'rgba(181, 133, 20, 0.2)',
            'chartBorder' => 'rgba(181, 133, 20, 1)',
        ],
        'KP' => [
            'cardBg' => 'bg-[#eff1f9]',
            'cardBorder' => 'border-[#dce0f2]',
            'accent' => '#35459e',
            'chartBg' => 'rgba(53, 69, 158, 0.2)',
            'chartBorder' => 'rgba(53, 69, 158, 1)',
        ],
        'MS' => [
            'cardBg' => 'bg-[#eef8f8]',
            'cardBorder' => 'border-[#d1eeee]',
            'accent' => '#0c7c85',
            'chartBg' => 'rgba(12, 124, 133, 0.2)',
            'chartBorder' => 'rgba(12, 124, 133, 1)',
        ],
    ];
    $fallbackThemes = [
        [
            'cardBg' => 'bg-[#eef8f1]',
            'cardBorder' => 'border-[#d1ebd7]',
            'accent' => '#147a42',
            'chartBg' => 'rgba(20, 122, 66, 0.2)',
            'chartBorder' => 'rgba(20, 122, 66, 1)',
        ],
        [
            'cardBg' => 'bg-[#fcf7ec]',
            'cardBorder' => 'border-[#f0e4cc]',
            'accent' => '#b58514',
            'chartBg' => 'rgba(181, 133, 20, 0.2)',
            'chartBorder' => 'rgba(181, 133, 20, 1)',
        ],
        [
            'cardBg' => 'bg-[#eff1f9]',
            'cardBorder' => 'border-[#dce0f2]',
            'accent' => '#35459e',
            'chartBg' => 'rgba(53, 69, 158, 0.2)',
            'chartBorder' => 'rgba(53, 69, 158, 1)',
        ],
        [
            'cardBg' => 'bg-[#eef8f8]',
            'cardBorder' => 'border-[#d1eeee]',
            'accent' => '#0c7c85',
            'chartBg' => 'rgba(12, 124, 133, 0.2)',
            'chartBorder' => 'rgba(12, 124, 133, 1)',
        ],
    ];
?>

<!-- Assessment Charts Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $codeKey = strtoupper((string)($category->code ?? ''));
        $theme = $themeMap[$codeKey] ?? $fallbackThemes[$loop->index % count($fallbackThemes)];
        $chartCanvasId = 'chartCategory_' . $category->id;
    ?>
    <div class="<?php echo e($theme['cardBg']); ?> rounded-2xl p-6 relative border <?php echo e($theme['cardBorder']); ?> transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-gray-900 text-lg"><?php echo e($category->name); ?></h3>
        </div>

        <div class="flex justify-center mb-6 h-52 relative z-10">
            <canvas id="<?php echo e($chartCanvasId); ?>"></canvas>
        </div>

        <div class="<?php echo e($category->aspects->count() > 4 ? 'grid grid-cols-2 gap-x-4 gap-y-3' : 'space-y-3'); ?> z-20 relative">
            <?php $__empty_2 = true; $__currentLoopData = $category->aspects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aspect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <?php
                    $score = $activeReport ? $activeReport->getAspectScore($aspect) : null;
                ?>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600 text-xs sm:text-sm"><?php echo e($aspect->name); ?></span>
                    <span class="font-bold" style="color: <?php echo e($theme['accent']); ?>">
                        <?php echo e($score !== null && $score > 0 ? number_format($score, 1) : ($activeReport ? number_format($score ?? 0, 1) : '0.0')); ?> / <?php echo e(number_format($aspect->target_score ?? 5.0, 0)); ?>

                    </span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <div class="col-span-2 text-center text-xs text-gray-400 py-3">
                    Belum ada aspek di kategori ini.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-span-2 bg-white rounded-2xl p-12 text-center border border-gray-200">
        <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-3"></i>
        <h3 class="font-bold text-gray-700 text-base">Belum Ada Kategori Assessment</h3>
        <p class="text-xs text-gray-400 mt-1">Belum ada aspek penilaian yang diatur pada master data.</p>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0, 0, 0, 0.08)' },
                    grid: { color: 'rgba(0, 0, 0, 0.08)' },
                    suggestedMin: 0,
                    suggestedMax: 5,
                    ticks: {
                        stepSize: 1,
                        display: false
                    },
                    pointLabels: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 10,
                            weight: 'bold'
                        },
                        color: '#64748b'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (items) => items[0].label,
                        label: (item) => 'Score: ' + item.raw
                    }
                }
            }
        };

        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $codeKey = strtoupper((string)($category->code ?? ''));
            $theme = $themeMap[$codeKey] ?? $fallbackThemes[$loop->index % count($fallbackThemes)];
            $canvasId = 'chartCategory_' . $category->id;
            $aspectNames = $category->aspects->pluck('name')->toArray();
            $aspectScores = $category->aspects->map(fn($a) => $activeReport ? $activeReport->getAspectScore($a) : 0)->toArray();
        ?>

        const el_<?php echo e($category->id); ?> = document.getElementById('<?php echo e($canvasId); ?>');
        if (el_<?php echo e($category->id); ?>) {
            new Chart(el_<?php echo e($category->id); ?>.getContext('2d'), {
                type: 'radar',
                data: {
                    labels: <?php echo json_encode($aspectNames); ?>,
                    datasets: [{
                        data: <?php echo json_encode($aspectScores); ?>,
                        backgroundColor: '<?php echo e($theme['chartBg']); ?>',
                        borderColor: '<?php echo e($theme['chartBorder']); ?>',
                        borderWidth: 2,
                        pointBackgroundColor: '<?php echo e($theme['chartBorder']); ?>',
                        pointRadius: <?php echo e($activeReport ? 3 : 0); ?>

                    }]
                },
                options: commonOptions
            });
        }
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    })();
</script>

<style>
/* Custom Scrollbar for Modals */
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
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views\user\assessment_report\show.blade.php ENDPATH**/ ?>