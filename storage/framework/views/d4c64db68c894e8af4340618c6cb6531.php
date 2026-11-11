

<?php $__env->startPush('modals'); ?>
<!-- Full screen modal overlay -->
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 sm:p-6">
    <!-- Modal Card -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[95vh] flex flex-col overflow-hidden animate-fade-in-up">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 bg-white">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Add Employee</h2>
                <p class="text-sm text-gray-500 mt-1">Enter the new employee's detailed information into the system</p>
            </div>
            <a href="<?php echo e(route('employee.index')); ?>" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-times text-lg"></i>
            </a>
        </div>

        <form action="<?php echo e(route('employee.store')); ?>" method="POST" enctype="multipart/form-data" novalidate hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>

            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-1 bg-white custom-scrollbar">
                <?php if($errors->any()): ?>
                    <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6 border border-red-200">
                        <ul class="list-disc pl-5 text-sm">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <!-- NPK -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">NPK <span class="text-red-500">*</span></label>
                        <input type="text" name="npk" value="<?php echo e(old('npk')); ?>" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['npk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. 10231">
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full name <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Ade Kurniawan">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date of birth</label>
                        <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <!-- Lama Bekerja -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Start Date of Employment</label>
                        <input type="date" name="lama_bekerja" value="<?php echo e(old('lama_bekerja')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['lama_bekerja'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Position / Job Title <span class="text-red-500">*</span></label>
                        <select name="position_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['position_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select Position</option>
                            <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pos->id); ?>" <?php echo e(old('position_id') == $pos->id ? 'selected' : ''); ?>><?php echo e($pos->nama_jabatan); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <!-- Readiness -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cadre Status</label>
                        <select name="readiness" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['readiness'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select Status (Optional)</option>
                            <option value="R1 ready" <?php echo e(in_array(old('readiness'), ['R1', 'R1 ready']) ? 'selected' : ''); ?>>R1</option>
                            <option value="R2 ready" <?php echo e(in_array(old('readiness'), ['R2', 'R2 ready']) ? 'selected' : ''); ?>>R2</option>
                            <option value="R3 ready" <?php echo e(in_array(old('readiness'), ['R3', 'R3 ready']) ? 'selected' : ''); ?>>R3</option>
                            <option value="R4 ready" <?php echo e(in_array(old('readiness'), ['R4', 'R4 ready']) ? 'selected' : ''); ?>>R4</option>
                            <option value="R5 ready" <?php echo e(in_array(old('readiness'), ['R5', 'R5 ready']) ? 'selected' : ''); ?>>R5</option>
                            <option value="not_ready" <?php echo e(old('readiness') == 'not_ready' ? 'selected' : ''); ?>>Not Ready</option> 
                        </select>
                    </div>

                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Photo</label>
                        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-[#0ea5e9] focus:ring-2 focus:ring-[#0ea5e9]/20 text-sm py-1.5 px-4 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#0ea5e9]/10 file:text-[#0ea5e9] hover:file:bg-[#0ea5e9]/20 <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Education</label>
                        <input type="text" name="pendidikan" value="<?php echo e(old('pendidikan')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['pendidikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Bachelor of Information Systems">
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Contact (Mobile Number)</label>
                        <input type="text" name="kontak" value="<?php echo e(old('kontak')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['kontak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. 08123456789">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="cth. email@company.com">
                    </div>

                    <!-- Departemen -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department <span class="text-red-500">*</span></label>
                        <select name="department_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select Department</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id') == $dept->id ? 'selected' : ''); ?>>
                                    <?php echo e($dept->nama_departemen); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Grade -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Grade</label>
                        <input type="text" name="grade" value="<?php echo e(old('grade')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. A1">
                    </div>

                    <!-- IQ -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">IQ Score</label>
                        <input type="number" name="iq" value="<?php echo e(old('iq')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['iq'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. 120">
                    </div>

                    <!-- DISC -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">DISC</label>
                        <input type="text" name="disc" value="<?php echo e(old('disc')); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['disc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. D/I/S/C">
                    </div>

                    <!-- HAV -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">HAV</label>
                        <select name="hav" class="drop-up w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border <?php $__errorArgs = ['hav'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select HAV (Optional)</option>
                            <option value="star" <?php echo e(old('hav') == 'star' ? 'selected' : ''); ?>>Star</option>
                            <option value="high potential" <?php echo e(old('hav') == 'high potential' ? 'selected' : ''); ?>>High potential</option>
                            <option value="potential" <?php echo e(old('hav') == 'potential' ? 'selected' : ''); ?>>Potential</option>
                            <option value="excellent career person" <?php echo e(old('hav') == 'excellent career person' ? 'selected' : ''); ?>>Excellent career person</option>
                            <option value="career person" <?php echo e(old('hav') == 'career person' ? 'selected' : ''); ?>>Career person</option>
                            <option value="problem employee" <?php echo e(old('hav') == 'problem employee' ? 'selected' : ''); ?>>Problem employee</option>
                            <option value="deadwood" <?php echo e(old('hav') == 'deadwood' ? 'selected' : ''); ?>>Deadwood</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <a href="<?php echo e(route('employee.index')); ?>" class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Save Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('employee.index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views\employee\create.blade.php ENDPATH**/ ?>