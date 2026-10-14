

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Training Data</h1>
        <p class="text-sm text-gray-500 mt-1">Select an employee to view evaluation details and training history.</p>
    </div>
    <!-- Tombol Tambah dihilangkan karena konsep Read-Only -->
</div>

<div class="card-3d overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100">
    <!-- Header / Actions / Filters -->
    <form method="GET" action="<?php echo e(route('training.index')); ?>" class="p-4 border-b border-gray-100 flex justify-between items-center bg-white relative">
        <div class="relative w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search Employee, NPK, Jabatan..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06b6d4] focus:border-transparent transition-all">
            <button type="submit" class="hidden"></button>
        </div>
        
        <div class="flex items-center gap-3 relative">
            <?php if(request()->hasAny(['search', 'department_id', 'jabatan'])): ?>
                <a href="<?php echo e(route('training.index')); ?>" class="text-xs text-red-500 hover:text-red-700 underline px-2">Reset</a>
            <?php endif; ?>

            <button type="button" onclick="document.getElementById('filterDropdown').classList.toggle('hidden')" class="btn-3d px-4 py-2 text-sm font-medium flex items-center gap-2 relative bg-white text-gray-700 border border-gray-200 rounded-lg shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-[0_4px_10px_rgb(0,0,0,0.08)] hover:border-gray-300">
                <i class="fas fa-filter text-gray-400"></i> Filter
                <?php if(request()->hasAny(['department_id', 'jabatan'])): ?>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                <?php endif; ?>
            </button>
            
            <!-- Filter Dropdown (Small) -->
            <div id="filterDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50 p-3">
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Department</label>
                        <select name="department_id" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#06b6d4]">
                            <option value="">All</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->nama_departemen); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Position</label>
                        <select name="jabatan" class="w-full text-xs border border-gray-200 rounded-md px-2 py-1.5 focus:outline-none focus:border-[#06b6d4]">
                            <option value="">All</option>
                            <option value="head" <?php echo e(request('jabatan') == 'head' ? 'selected' : ''); ?>>Department Head</option>
                            <option value="group head" <?php echo e(request('jabatan') == 'group head' ? 'selected' : ''); ?>>Group Head</option>
                            <option value="unit head" <?php echo e(request('jabatan') == 'unit head' ? 'selected' : ''); ?>>Unit Head</option>
                            <option value="staff" <?php echo e(request('jabatan') == 'staff' ? 'selected' : ''); ?>>Staff</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#06b6d4] hover:bg-[#0891b2] text-white px-3 py-1.5 rounded-md text-xs font-medium transition-colors">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="text-[10px] text-white uppercase bg-[#06b6d4] shadow-md relative z-10">
                <tr>
                    <th class="px-5 py-4 font-semibold tracking-wider w-12 text-center">No.</th>
                    <th class="px-5 py-4 font-semibold tracking-wider">Employee</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">NPK</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Position</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Department</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Readiness</th>
                    <th class="px-5 py-4 font-semibold tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="transition-all duration-300 hover:shadow-[0_4px_15px_rgba(6,182,212,0.15)] hover:-translate-y-0.5 hover:z-10 relative bg-white group border-b-transparent">
                    <td class="px-5 py-4 text-center text-gray-500 font-medium">
                        <?php echo e(($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration); ?>

                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <?php if($emp->foto): ?>
                                <img src="<?php echo e($emp->foto); ?>" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($emp->nama)); ?>&background=random" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            <?php endif; ?>
                            <div>
                                <div class="font-semibold text-gray-900 group-hover:text-[#06b6d4] transition-colors"><?php echo e($emp->nama); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($emp->email ?? '-'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-gray-600 font-medium"><?php echo e($emp->npk); ?></td>
                    <td class="px-5 py-4 text-center text-gray-600"><?php echo e($emp->position?->nama_jabatan); ?></td>
                    <td class="px-5 py-4 text-center text-gray-600"><?php echo e($emp->department ? $emp->department->nama_departemen : '-'); ?></td>
                    <td class="px-5 py-4 text-center">
                        <?php
                            $readinessStyles = [
                                'R1 ready' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200/50', 'dot' => 'bg-green-500'],
                                'R2 ready' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200/50', 'dot' => 'bg-blue-500'],
                                'R3 ready' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200/50', 'dot' => 'bg-indigo-500'],
                                'R4 ready' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200/50', 'dot' => 'bg-purple-500'],
                                'R5 ready' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200/50', 'dot' => 'bg-yellow-500'],
                                'not_ready' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200/50', 'dot' => 'bg-red-500'],
                            ];
                            $style = $readinessStyles[$emp->readiness] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200/50', 'dot' => 'bg-gray-500'];
                        ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium <?php echo e($style['bg']); ?> <?php echo e($style['text']); ?> border <?php echo e($style['border']); ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($style['dot']); ?>"></span> <?php echo e($emp->readiness === 'not_ready' ? 'Not Ready' : (empty($emp->readiness) ? '-' : ucwords($emp->readiness))); ?>

                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Hanya tombol Lihat Detail Training -->
                            <a href="<?php echo e(route('training.show', $emp->id)); ?>" class="text-gray-400 hover:text-[#06b6d4] p-1.5 rounded-md hover:bg-cyan-50 transition-colors" title="Lihat Detail Training">
                                <i class="fas fa-eye w-4 text-center"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <!-- Empty State -->
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="font-medium text-gray-900">No employee data available</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-5 border-t border-gray-100 bg-white flex justify-between items-center">
        <?php if(method_exists($employees, 'links') && $employees->hasPages()): ?>
            <div class="w-full">
                <?php echo e($employees->links('pagination::tailwind')); ?>

            </div>
        <?php endif; ?>
    </div>
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



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views/training/index.blade.php ENDPATH**/ ?>