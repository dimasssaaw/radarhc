<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Role User</h1>
        <p class="text-sm text-gray-500 mt-1">Manage role data in the company</p>
    </div>
    <div class="flex gap-3">
        <a href="<?php echo e(route('role.create')); ?>" class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Role
        </a>
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
                    <th class="px-5 py-4 font-semibold tracking-wider">Role Name</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
                    <td class="px-5 py-4 text-center text-gray-500"><?php echo e($loop->iteration + $roles->firstItem() - 1); ?></td>
                    <td class="px-5 py-4 font-medium text-gray-800"><?php echo e($role->name); ?></td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?php echo e(route('role.edit', $role->id)); ?>" class="text-gray-400 hover:text-amber-600 p-1.5 rounded-md hover:bg-amber-50 transition-colors" title="Edit">
                                <i class="fas fa-edit w-4"></i>
                            </a>
                            <form action="<?php echo e(route('role.destroy', $role->id)); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role?');">
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
                    <td colspan="3" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-user-shield text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">No role data available yet</p>
                            <p class="text-xs text-gray-400 mt-1">Please click the Add Role button</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-5 border-t border-gray-100 bg-white flex justify-between items-center">
        <?php if(method_exists($roles, 'links')): ?>
            <div class="w-full">
                <?php echo e($roles->links('pagination::tailwind')); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views\role\index.blade.php ENDPATH**/ ?>