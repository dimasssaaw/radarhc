<?php $__env->startSection('title', 'Position'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Position</h1>
        <p class="text-sm text-gray-500 mt-1">Manage position data in the company</p>
    </div>
    <div class="flex gap-3">
        <button onclick="document.getElementById('addPosModal').classList.remove('hidden')" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Position
        </button>
    </div>
</div>

<?php if(session('success')): ?>
<div class="bg-green-50 text-green-600 p-4 rounded-lg mb-6 border border-green-200">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<div class="card-3d bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center w-16">No</th>
                    <th class="px-5 py-4 font-semibold tracking-wider">Position Name</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Total Employees</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
                    <td class="px-5 py-4 text-center text-gray-500"><?php echo e($loop->iteration + $positions->firstItem() - 1); ?></td>
                    <td class="px-5 py-4 font-medium text-gray-800"><?php echo e($pos->nama_jabatan); ?></td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center justify-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            <?php echo e($pos->karyawan()->count()); ?> Employees
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="document.getElementById('editPosModal-<?php echo e($pos->id); ?>').classList.remove('hidden')" class="text-gray-400 hover:text-amber-600 p-1.5 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                <i class="fas fa-edit w-4"></i>
                            </button>
                            <form action="<?php echo e(route('position.destroy', $pos->id)); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this position?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-gray-400 hover:text-red-600 p-1.5 rounded-md hover:bg-red-50 transition-colors" title="Delete">
                                    <i class="fas fa-trash-alt w-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-briefcase text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">No position data available yet</p>
                            <p class="text-xs text-gray-400 mt-1">Please click the Add Position button</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-5 border-t border-gray-100 bg-white flex justify-between items-center">
        <?php if(method_exists($positions, 'links')): ?>
            <div class="w-full">
                <?php echo e($positions->links('pagination::tailwind')); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
<!-- Modal Tambah Jabatan -->
<div id="addPosModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="document.getElementById('addPosModal').classList.add('hidden')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-gray-100 my-8">
            <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                        Add Position
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Enter new position name.</p>
                </div>
                <button type="button" onclick="document.getElementById('addPosModal').classList.add('hidden')" class="bg-gray-50 p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition-all">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="<?php echo e(route('position.store')); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                <?php echo csrf_field(); ?>
                <div class="bg-white px-6 pt-5 pb-6 sm:p-8 sm:pt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Position Name <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jabatan" value="<?php echo e(old('nama_jabatan')); ?>" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border" placeholder="e.g. Manager">
                        <?php $__errorArgs = ['nama_jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 sm:px-8">
                    <button type="button" onclick="document.getElementById('addPosModal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">
                        Cancel
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md shadow-sky-500/30 px-6 py-2.5 bg-[#0ea5e9] text-sm font-medium text-white hover:bg-[#0284c7] focus:outline-none transition-all sm:w-auto flex items-center gap-2">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- Modal Edit Jabatan -->
<div id="editPosModal-<?php echo e($pos->id); ?>" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="document.getElementById('editPosModal-<?php echo e($pos->id); ?>').classList.add('hidden')"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-gray-100 my-8">
            <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-5 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Edit Position
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Modify position name.</p>
                </div>
                <button type="button" onclick="document.getElementById('editPosModal-<?php echo e($pos->id); ?>').classList.add('hidden')" class="bg-gray-50 p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition-all">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form action="<?php echo e(route('position.update', $pos->id)); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="id" value="<?php echo e($pos->id); ?>">
                <div class="bg-white px-6 pt-5 pb-6 sm:p-8 sm:pt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Position Name <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_jabatan" value="<?php echo e(old('nama_jabatan', $pos->nama_jabatan)); ?>" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0ea5e9] focus:ring-[#0ea5e9] text-sm py-2 px-3 border" placeholder="e.g. Manager">
                        <?php if(old('_method') == 'PUT' && old('id') == $pos->id): ?>
                            <?php $__errorArgs = ['nama_jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 sm:px-8">
                    <button type="button" onclick="document.getElementById('editPosModal-<?php echo e($pos->id); ?>').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">
                        Cancel
                    </button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md shadow-sky-500/30 px-6 py-2.5 bg-[#0ea5e9] text-sm font-medium text-white hover:bg-[#0284c7] focus:outline-none transition-all sm:w-auto flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function checkPosModals() {
        <?php if($errors->any()): ?>
            <?php if(!old('_method')): ?>
                document.getElementById('addPosModal')?.classList.remove('hidden');
            <?php elseif(old('_method') == 'PUT'): ?>
                <?php $oldId = old('id') ?? request()->route('id'); ?>
                <?php if($oldId): ?>
                    document.getElementById('editPosModal-<?php echo e($oldId); ?>')?.classList.remove('hidden');
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    }
    
    document.addEventListener('DOMContentLoaded', checkPosModals);
    document.addEventListener('htmx:afterSwap', checkPosModals);
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views\position\index.blade.php ENDPATH**/ ?>