<div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] mb-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
    <div class="flex flex-col lg:flex-row gap-8 items-center">
        
        <!-- Left: Avatar & Name -->
        <div class="flex flex-col justify-center items-center min-w-[200px] lg:border-r border-gray-100 lg:pr-8">
            <div class="w-28 h-28 rounded-full bg-cyan-500 border-4 border-white shadow-[0_4px_12px_rgb(0,0,0,0.1)] overflow-hidden mb-4 flex items-center justify-center text-white text-3xl font-bold">
                <?php if($employee->foto): ?>
                    <img src="<?php echo e($employee->foto); ?>" alt="Foto" class="w-full h-full object-cover">
                <?php else: ?>
                    <?php echo e(strtoupper(substr($employee->nama, 0, 2))); ?>

                <?php endif; ?>
            </div>
            <h2 class="text-xl font-bold text-gray-900 text-center"><?php echo e($employee->nama); ?></h2>
            <span class="mt-2 inline-flex items-center justify-center px-4 py-1 rounded-full text-[10px] font-bold text-gray-600 bg-gray-100 uppercase tracking-widest shadow-sm">
                <?php echo e($employee->position?->nama_jabatan); ?>

            </span>
        </div>

        <!-- Right: Info Columns -->
        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- PERSONAL INFO -->
            <div>
                <h3 class="flex items-center gap-2 text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-4 pb-2 border-b border-gray-50">
                    <div class="w-3 h-3 bg-gray-200 rounded-sm"></div> PERSONAL INFO
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Pendidikan</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->pendidikan ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Tgl Lahir</p>
                        <p class="font-bold text-gray-800 text-sm">
                            <?php echo e($employee->tanggal_lahir ? \Carbon\Carbon::parse($employee->tanggal_lahir)->format('d M Y') . ' (' . \Carbon\Carbon::parse($employee->tanggal_lahir)->age . ' Thn)' : '-'); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">No. HP</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->kontak ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Email</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->email ?? '-'); ?></p>
                    </div>
                </div>
            </div>

            <!-- CAREER -->
            <div>
                <h3 class="flex items-center gap-2 text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-4 pb-2 border-b border-gray-50">
                    <div class="w-3 h-3 bg-gray-200 rounded-sm"></div> CAREER
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">NPK</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->npk ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Departemen</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->department->nama_departemen ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Grade</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->grade ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Lama Bekerja</p>
                        <p class="font-bold text-gray-800 text-sm">
                            <?php
                                $diffString = '-';
                                if ($employee->masuk_kerja) {
                                    try {
                                        $start = \Carbon\Carbon::parse($employee->masuk_kerja);
                                        $diff = $start->diff(\Carbon\Carbon::now());
                                        $diffString = $diff->y . ' Thn ' . $diff->m . ' Bln';
                                    } catch (\Exception $e) {
                                        $diffString = $employee->masuk_kerja; 
                                    }
                                }
                            ?>
                            <?php echo e($diffString); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- TALENT METRICS -->
            <div>
                <h3 class="flex items-center gap-2 text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-4 pb-2 border-b border-gray-50">
                    <div class="w-3 h-3 bg-gray-200 rounded-sm"></div> TALENT METRICS
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">IQ</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->iq ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">DISC</p>
                        <p class="font-bold text-gray-800 text-sm"><?php echo e($employee->disc ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Readiness</p>
                        <?php
                            $readiness = strtolower($employee->readiness ?? '');
                            $isReady = str_contains($readiness, 'ready') && $readiness !== 'not_ready';
                            $readyText = $employee->readiness === 'not_ready' ? 'Not Ready' : ucwords($employee->readiness ?? '-');
                        ?>
                        <?php if($isReady): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <?php echo e($readyText); ?>

                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                                <?php echo e($readyText); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">HAV</p>
                        <?php
                            $hav = strtolower(trim($employee->hav ?? ''));
                            $havStyles = [
                                'star' => 'background: linear-gradient(to bottom, #3b82f6, #2563eb); border-color: #60a5fa; color: white;', 
                                'high potential' => 'background: linear-gradient(to bottom, #15803d, #166534); border-color: #16a34a; color: white;',
                                'potential' => 'background: linear-gradient(to bottom, #22c55e, #16a34a); border-color: #4ade80; color: white;',
                                'excellent career person' => 'background: linear-gradient(to bottom, #fb923c, #f97316); border-color: #fdba74; color: white;',
                                'career person' => 'background: linear-gradient(to bottom, #facc15, #eab308); border-color: #fde047; color: white;',
                                'problem employee' => 'background: linear-gradient(to bottom, #6b7280, #4b5563); border-color: #9ca3af; color: white;',
                                'deadwood' => 'background: linear-gradient(to bottom, #dc2626, #b91c1c); border-color: #ef4444; color: white;',
                            ];
                        ?>
                        <?php if($hav && $hav !== '-'): ?>
                            <?php
                                $styleStr = $havStyles[$hav] ?? 'background: #e5e7eb; border-color: #d1d5db; color: #374151;';
                            ?>
                            <span class="inline-flex items-center justify-center px-5 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider border shadow-sm" style="<?php echo e($styleStr); ?>">
                                <?php echo e($employee->hav); ?>

                            </span>
                        <?php else: ?>
                            <p class="font-bold text-gray-800 text-sm">-</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php /**PATH C:\laragon\www\radarhc\resources\views/components/employee-profile-header.blade.php ENDPATH**/ ?>