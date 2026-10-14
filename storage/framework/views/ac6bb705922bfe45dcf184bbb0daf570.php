<?php $__env->startSection('title', 'Detail Employee - ' . $employee->name); ?>
<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="<?php echo e(route('profile.index')); ?>" class="hover:text-gray-700 transition-colors">Profile data</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <span class="text-gray-900 font-medium">Profile View</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="<?php echo e(route('profile.index')); ?>" class="bg-white shadow-[0_2px_4px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_6px_rgba(0,0,0,0.05),0_2px_4px_rgba(0,0,0,0.1)] border border-gray-100 rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-gray-700">
            <i class="fas fa-arrow-left text-gray-400"></i> Back
        </a>
    </div>
</div>

<!-- Employee Profile Header -->
<?php echo $__env->make('components.employee-profile-header', ['employee' => $employee], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="grid grid-cols-1 gap-6 mb-6">
    <!-- 2. Via Character -->
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Via Character</h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_karyawan')): ?>
            <button onclick="document.getElementById('addViaCharacterModal').classList.remove('hidden')" class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i> Add Data
            </button>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] uppercase text-white font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3 bg-[#117666] w-1/2 text-center">OVERUSE STRENGTH</th>
                        <th class="px-6 py-3 bg-[#9b1c43] w-1/2 text-center">UNDERUSE STRENGTH</th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit_karyawan', 'hapus_karyawan'])): ?>
                        <th class="px-6 py-3 bg-gray-100 text-gray-500 w-[20%] text-center">ACTIONS</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $employee->viaCharacters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $via): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 bg-[#f0f9f8] text-center"><?php echo e($via->overuse_strength); ?></td>
                        <td class="px-6 py-4 bg-[#fdf2f5] text-center"><?php echo e($via->underuse_strength); ?></td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit_karyawan', 'hapus_karyawan'])): ?>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button onclick="document.getElementById('editViaCharacterModal<?php echo e($via->id); ?>').classList.remove('hidden')" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 rounded hover:bg-emerald-50" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                            <form action="<?php echo e(route('employee-via-character.destroy', $via->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50" title="Hapus"><i class="fas fa-trash-alt text-xs"></i></button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e((auth()->user()->can('edit_karyawan') || auth()->user()->can('hapus_karyawan')) ? 3 : 2); ?>" class="px-6 py-6 text-center text-gray-400 italic text-xs">No data available Via Character</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Strength & Weakness -->
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Strength & Weakness</h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_karyawan')): ?>
            <button onclick="document.getElementById('addStrengthModal').classList.remove('hidden')" class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm flex items-center gap-2 transition-colors">
                <i class="fas fa-plus"></i> Add Data
            </button>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] uppercase text-white font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-3 bg-[#1e4cd5] w-1/2 text-center">STRENGTH</th>
                        <th class="px-6 py-3 bg-[#a85002] w-1/2 text-center">WEAKNESS</th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit_karyawan', 'hapus_karyawan'])): ?>
                        <th class="px-6 py-3 bg-gray-100 text-gray-500 w-[20%] text-center">ACTIONS</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $employee->strengths ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 bg-[#f2f5fd] text-center"><?php echo e($st->strength); ?></td>
                        <td class="px-6 py-4 bg-[#fdf5f0] text-center"><?php echo e($st->weakness); ?></td>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit_karyawan', 'hapus_karyawan'])): ?>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button onclick="document.getElementById('editStrengthModal<?php echo e($st->id); ?>').classList.remove('hidden')" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1 rounded hover:bg-emerald-50" title="Edit"><i class="fas fa-edit text-xs"></i></button>
                            <form action="<?php echo e(route('employee-strength.destroy', $st->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50" title="Hapus"><i class="fas fa-trash-alt text-xs"></i></button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e((auth()->user()->can('edit_karyawan') || auth()->user()->can('hapus_karyawan')) ? 3 : 2); ?>" class="px-6 py-6 text-center text-gray-400 italic text-xs">No data available Strength & Weakness</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 4. SCARF Profile -->
<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] mb-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-gray-900">SCARF Profile</h2>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_karyawan')): ?>
        <button onclick="document.getElementById('editScarfModal').classList.remove('hidden')" class="text-xs font-bold text-emerald-700 flex items-center gap-1 hover:text-emerald-800 transition-colors p-1.5 rounded-lg hover:bg-emerald-50">
            <i class="fas fa-cog"></i> Manage
        </button>
        <?php endif; ?>
    </div>
    <div class="space-y-5 pr-8">
        <?php
            $scarf = $employee->scarf;
            $s_status = $scarf ? $scarf->status : 0;
            $s_certainty = $scarf ? $scarf->certainty : 0;
            $s_autonomy = $scarf ? $scarf->autonomy : 0;
            $s_relatedness = $scarf ? $scarf->relatedness : 0;
            $s_fairness = $scarf ? $scarf->fairness : 0;
        ?>
        <!-- Status -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Status</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#1b7356] h-2.5 rounded-full transition-all duration-1000" style="width: <?php echo e($s_status); ?>%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right"><?php echo e($s_status); ?>%</span>
        </div>
        <!-- Certainty -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Certainty</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#fbb93a] h-2.5 rounded-full transition-all duration-1000" style="width: <?php echo e($s_certainty); ?>%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right"><?php echo e($s_certainty); ?>%</span>
        </div>
        <!-- Autonomy -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Autonomy</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#12a1b9] h-2.5 rounded-full transition-all duration-1000" style="width: <?php echo e($s_autonomy); ?>%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right"><?php echo e($s_autonomy); ?>%</span>
        </div>
        <!-- Relatedness -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Relatedness</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#c22e23] h-2.5 rounded-full transition-all duration-1000" style="width: <?php echo e($s_relatedness); ?>%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right"><?php echo e($s_relatedness); ?>%</span>
        </div>
        <!-- Fairness -->
        <div class="flex items-center">
            <span class="w-32 text-sm font-bold text-gray-900">Fairness</span>
            <div class="flex-1 bg-gray-100 rounded-full h-2.5 mx-4 overflow-hidden relative shadow-inner">
                <div class="bg-[#606a66] h-2.5 rounded-full transition-all duration-1000" style="width: <?php echo e($s_fairness); ?>%"></div>
            </div>
            <span class="w-12 text-sm font-bold text-gray-900 text-right"><?php echo e($s_fairness); ?>%</span>
        </div>
    </div>
</div>

<!-- 5. Mentoring & Coaching Matrix -->
<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] mb-8 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
    <h2 class="text-lg font-bold text-gray-900 mb-6">Mentoring & Coaching Matrix</h2>
    
    <?php
        $latestMentoring = \App\Models\Mentoring::where('id_karyawan', $employee->id)->latest()->first();
        $comp = $latestMentoring->competency ?? null;
        $comm = $latestMentoring->commitment ?? null;
        
        $activeBox = null;
        if ($comp === 'Rendah' && $comm === 'Tinggi') $activeBox = 'TL';
        if ($comp === 'Rendah' && $comm === 'Rendah') $activeBox = 'TR';
        if ($comp === 'Tinggi' && $comm === 'Tinggi') $activeBox = 'BL';
        if ($comp === 'Tinggi' && $comm === 'Rendah') $activeBox = 'BR';
    ?>
    
    <div class="w-full rounded-xl overflow-hidden grid grid-cols-2 shadow-sm border border-gray-100 font-bold text-[10px] tracking-wide text-white">
        <!-- Top Left: Yellow (Comp: Rendah, Comm: Tinggi) -->
        <div class="bg-[#facd42] aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 text-[#8a6800] transition-opacity duration-300 <?php echo e(($activeBox && $activeBox !== 'TL') ? 'opacity-30' : 'opacity-100'); ?>">
            <div>DIRECTING - MODERAT</div>
            <div>MENTORING - MODERAT</div>
            <div>COACHING - MODERAT</div>
        </div>
        
        <!-- Top Right: Blue (Comp: Rendah, Comm: Rendah) -->
        <div class="bg-[#4485f5] aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 transition-opacity duration-300 <?php echo e(($activeBox && $activeBox !== 'TR') ? 'opacity-30' : 'opacity-100'); ?>">
            <div>DIRECTING - RENDAH</div>
            <div>MENTORING - RENDAH</div>
            <div>COACHING - RENDAH</div>
        </div>
        
        <!-- Bottom Left: Green (Comp: Tinggi, Comm: Tinggi) -->
        <div class="bg-[#78c87f] aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 text-[#205724] transition-opacity duration-300 <?php echo e(($activeBox && $activeBox !== 'BL') ? 'opacity-30' : 'opacity-100'); ?>">
            <div>DIRECTING - TINGGI</div>
            <div>MENTORING - TINGGI</div>
            <div>COACHING - TINGGI</div>
        </div>
        
        <!-- Bottom Right: Red/Coral (Comp: Tinggi, Comm: Rendah) -->
        <div class="bg-[#f05051] aspect-[2.5/1] flex flex-col justify-center items-center text-center p-4 transition-opacity duration-300 <?php echo e(($activeBox && $activeBox !== 'BR') ? 'opacity-30' : 'opacity-100'); ?>">
            <div>DIRECTING - MODERAT</div>
            <div>MENTORING - TINGGI</div>
            <div>COACHING - MODERAT</div>
        </div>
    </div>
</div>

<?php $__env->startPush('modals'); ?>
<!-- Modal Tambah Via Character -->
<div id="addViaCharacterModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Tambah Via Character</h3>
            <button type="button" onclick="document.getElementById('addViaCharacterModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="<?php echo e(route('employee-via-character.store')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($employee->id); ?>">
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

<!-- Modal Tambah Strength & Weakness -->
<div id="addStrengthModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Tambah Strength & Weakness</h3>
            <button type="button" onclick="document.getElementById('addStrengthModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="<?php echo e(route('employee-strength.store')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($employee->id); ?>">
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
<?php $__currentLoopData = $employee->viaCharacters ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $via): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div id="editViaCharacterModal<?php echo e($via->id); ?>" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Edit Via Character</h3>
            <button type="button" onclick="document.getElementById('editViaCharacterModal<?php echo e($via->id); ?>').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="<?php echo e(route('employee-via-character.update', $via->id)); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($employee->id); ?>">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Overuse Strength</label>
                    <input type="text" name="overuse_strength" value="<?php echo e($via->overuse_strength); ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Underuse Strength</label>
                    <input type="text" name="underuse_strength" value="<?php echo e($via->underuse_strength); ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editViaCharacterModal<?php echo e($via->id); ?>').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Perbarui</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modals Edit Strength & Weakness -->
<?php $__currentLoopData = $employee->strengths ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div id="editStrengthModal<?php echo e($st->id); ?>" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 transition-opacity">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all relative z-[10000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Edit Strength & Weakness</h3>
            <button type="button" onclick="document.getElementById('editStrengthModal<?php echo e($st->id); ?>').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <form action="<?php echo e(route('employee-strength.update', $st->id)); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="hidden" name="id_karyawan" value="<?php echo e($employee->id); ?>">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Strength</label>
                    <input type="text" name="strength" value="<?php echo e($st->strength); ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weakness</label>
                    <input type="text" name="weakness" value="<?php echo e($st->weakness); ?>" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editStrengthModal<?php echo e($st->id); ?>').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#0ea5e9] rounded-lg hover:bg-[#0284c7] transition-all focus:outline-none">Perbarui</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
        
        <form action="<?php echo e(route('employee-scarf.update', $employee->id)); ?>" method="POST" class="p-5">
            <?php echo csrf_field(); ?>
            
            <?php
                $scarf = $employee->scarf;
            ?>
            
            <!-- Jarak antar elemen dirapatkan menjadi space-y-3 -->
            <div class="space-y-3 mb-5">
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Status (0-100%)</label>
                    <input type="number" step="any" name="status" min="0" max="100" value="<?php echo e($scarf->status ?? 0); ?>" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Certainty (0-100%)</label>
                    <input type="number" step="any" name="certainty" min="0" max="100" value="<?php echo e($scarf->certainty ?? 0); ?>" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Autonomy (0-100%)</label>
                    <input type="number" step="any" name="autonomy" min="0" max="100" value="<?php echo e($scarf->autonomy ?? 0); ?>" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Relatedness (0-100%)</label>
                    <input type="number" step="any" name="relatedness" min="0" max="100" value="<?php echo e($scarf->relatedness ?? 0); ?>" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-gray-700 mb-1">Fairness (0-100%)</label>
                    <input type="number" step="any" name="fairness" min="0" max="100" value="<?php echo e($scarf->fairness ?? 0); ?>" required class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-sky-100 focus:border-[#0ea5e9] transition-all text-sm">
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views/user/profile/show.blade.php ENDPATH**/ ?>