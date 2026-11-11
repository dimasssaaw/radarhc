<?php $__env->startSection('title', 'Mentoring Details'); ?>
<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <div class="text-sm text-gray-400 mb-1 flex items-center gap-2">
            <a href="<?php echo e(route('mentoring.index')); ?>" class="hover:text-gray-700 transition-colors">Mentoring Data</a>
            <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <?php if(isset($mentoring->id_karyawan)): ?>
                <a href="<?php echo e(route('mentoring.show-employee', $mentoring->id_karyawan)); ?>" class="hover:text-gray-700 transition-colors">Employee Mentoring</a>
                <span class="text-xs"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
            <span class="text-gray-900 font-medium">Mentoring Plan Details</span>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="<?php echo e(isset($mentoring->id_karyawan) ? route('mentoring.show-employee', $mentoring->id_karyawan) : route('mentoring.index')); ?>" class="bg-[#0ea5e9] hover:bg-[#0284c7] shadow-sm hover:shadow-md rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition-all transform hover:-translate-y-0.5 text-white">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Employee Profile Header -->
<?php echo $__env->make('components.employee-profile-header', ['employee' => $mentoring->karyawan], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Main Mentoring Grid layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pb-12">
    
    <!-- Left Column (Session Details) -->
    <div class="lg:col-span-4 flex flex-col gap-6">
        
        <!-- Session Details Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-info-circle text-[#0ea5e9]"></i> Session Details
                </h3>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tambah_mentoring')): ?>
                <button @click="$dispatch('open-session-modal')" class="text-[#0ea5e9] hover:bg-sky-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="<?php echo e($mentoring->id ? 'Edit Session Details' : 'Add Session Details'); ?>">
                    <i class="fas <?php echo e($mentoring->id ? 'fa-pen' : 'fa-plus'); ?>"></i>
                </button>
                <?php endif; ?>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Year</span>
                    <span class="font-semibold text-gray-900 text-sm"><?php echo e($mentoring->tahun ?? '-'); ?></span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Period</span>
                    <span class="font-semibold text-gray-900 text-sm"><?php echo e($mentoring->periode ?? '-'); ?></span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Project</span>
                    <span class="font-semibold text-gray-900 text-sm text-right max-w-[150px] truncate" title="<?php echo e($mentoring->project); ?>"><?php echo e($mentoring->project ?? '-'); ?></span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Status</span>
                    <?php if(empty($mentoring->status_mentoring)): ?>
                        <span class="font-semibold text-gray-900 text-sm">-</span>
                    <?php else: ?>
                        <?php
                            $statusClasses = [
                                'completed' => 'bg-green-100 text-green-700',
                                'in_progress' => 'bg-blue-100 text-blue-700'
                            ];
                            $sClass = $statusClasses[$mentoring->status_mentoring] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?php echo e($sClass); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $mentoring->status_mentoring))); ?>

                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Mentor</span>
                    <span class="font-semibold text-gray-900 text-sm"><?php echo e($mentoring->mentor ?? '-'); ?></span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded-lg border border-transparent hover:border-sky-100 hover:bg-sky-50/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-sm">
                    <span class="text-gray-500 text-sm">Development Area</span>
                    <span class="font-semibold text-gray-900 text-sm text-right max-w-[150px] truncate" title="<?php echo e($mentoring->development); ?>"><?php echo e($mentoring->development ?? '-'); ?></span>
                </div>
            </div>
        </div>

        <!-- Evaluation Metrics Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200"
            x-data="{ 
                mentoringId: '<?php echo e($mentoring->id ?? ''); ?>',
                employeeId: '<?php echo e($mentoring->karyawan->id ?? ''); ?>',
                competency: '<?php echo e($mentoring->competency ?? ''); ?>', 
                commitment: '<?php echo e($mentoring->commitment ?? ''); ?>',
                updateMetric(type, val) {
                    if (type === 'competency') this.competency = val;
                    if (type === 'commitment') this.commitment = val;
                    
                    let url = this.mentoringId 
                        ? '<?php echo e(url("mentoring")); ?>/' + this.mentoringId + '/metrics'
                        : '<?php echo e(url("mentoring/employee")); ?>/' + this.employeeId + '/metrics';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            competency: this.competency,
                            commitment: this.commitment
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('Metrics updated', data);
                        if (data.mentoring && data.mentoring.id) {
                            this.mentoringId = data.mentoring.id;
                        }
                    })
                    .catch(err => console.error(err));
                }
            }">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-bar text-emerald-800"></i> Evaluation Metrics
            </h3>
            
            <!-- Competency -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-800">Competency</span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold"
                        :class="competency === 'Tinggi' ? 'bg-emerald-100 text-emerald-800' : (competency === 'Rendah' ? 'bg-gray-200 text-gray-700' : 'bg-gray-100 text-gray-500')"
                        x-text="competency === 'Tinggi' ? 'High' : (competency === 'Rendah' ? 'Low' : (competency || '-'))"></span>
                </div>
                <div class="flex rounded-lg bg-gray-50 border border-gray-200 p-1">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                    <button @click="updateMetric('competency', 'Rendah')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="competency === 'Rendah' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        Low
                    </button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                    <button @click="updateMetric('competency', 'Tinggi')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="competency === 'Tinggi' ? 'bg-white shadow text-emerald-700' : 'text-gray-500 hover:text-gray-700'">
                        High
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Commitment -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-800">Commitment</span>
                    <span class="px-2.5 py-0.5 rounded text-xs font-semibold"
                        :class="commitment === 'Tinggi' ? 'bg-amber-100 text-amber-800' : (commitment === 'Rendah' ? 'bg-gray-200 text-gray-700' : 'bg-gray-100 text-gray-500')"
                        x-text="commitment === 'Tinggi' ? 'High' : (commitment === 'Rendah' ? 'Low' : (commitment || '-'))"></span>
                </div>
                <div class="flex rounded-lg bg-gray-50 border border-gray-200 p-1">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                    <button @click="updateMetric('commitment', 'Rendah')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="commitment === 'Rendah' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        Low
                    </button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                    <button @click="updateMetric('commitment', 'Tinggi')"
                        class="flex-1 py-1.5 text-sm font-medium rounded-md transition-all"
                        :class="commitment === 'Tinggi' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        High
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column (Progress Tracking & GROW Model) -->
    <div class="lg:col-span-8 flex flex-col gap-6">
        
        <!-- Progress Tracking -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-tasks text-emerald-500"></i> Progress Tracking
                </h3>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                <button @click="$dispatch('open-progress-modal')" class="text-emerald-500 hover:bg-emerald-50 px-3 py-1.5 rounded text-sm font-semibold transition-colors flex items-center gap-1.5 focus:outline-none" title="<?php echo e($mentoring->id ? 'Edit Progress Tracking' : 'Add Progress Tracking'); ?>">
                    <i class="fas <?php echo e($mentoring->id ? 'fa-pen' : 'fa-plus'); ?>"></i>
                </button>
                <?php endif; ?>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Target</p>
                    <p class="font-medium text-gray-900"><?php echo e($mentoring->target ? \Carbon\Carbon::parse($mentoring->target)->format('d M Y') : '-'); ?></p>
                </div>
                
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Monitoring</p>
                    <p class="font-medium text-gray-900"><?php echo e($mentoring->monitoring ? \Carbon\Carbon::parse($mentoring->monitoring)->format('d M Y') : '-'); ?></p>
                </div>
                
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md hover:border-emerald-200 hover:bg-white">
                    <p class="text-xs text-gray-500 mb-1">Final</p>
                    <p class="font-medium text-gray-900"><?php echo e($mentoring->final ? \Carbon\Carbon::parse($mentoring->final)->format('d M Y') : '-'); ?></p>
                </div>
            </div>
        </div>
        <!-- GROW Model -->
        <?php
            $actionUrl = $mentoring->id 
                ? route('mentoring.update-grow', $mentoring->id) 
                : route('mentoring.update-employee-grow', $mentoring->karyawan->id ?? 0);
        ?>
        <div x-data="{ 
                editing: false,
                goals: <?php echo e(json_encode($mentoring->grow_goals ?? '')); ?>,
                realitas: <?php echo e(json_encode($mentoring->grow_realitas ?? '')); ?>,
                options: <?php echo e(json_encode($mentoring->grow_options ?? '')); ?>,
                wrap_up: <?php echo e(json_encode($mentoring->grow_wrap_up ?? '')); ?>,
                insight: <?php echo e(json_encode($mentoring->grow_insight ?? '')); ?>,
                original: {
                    goals: <?php echo e(json_encode($mentoring->grow_goals ?? '')); ?>,
                    realitas: <?php echo e(json_encode($mentoring->grow_realitas ?? '')); ?>,
                    options: <?php echo e(json_encode($mentoring->grow_options ?? '')); ?>,
                    wrap_up: <?php echo e(json_encode($mentoring->grow_wrap_up ?? '')); ?>,
                    insight: <?php echo e(json_encode($mentoring->grow_insight ?? '')); ?>

                },
                cancel() {
                    this.goals = this.original.goals;
                    this.realitas = this.original.realitas;
                    this.options = this.original.options;
                    this.wrap_up = this.original.wrap_up;
                    this.insight = this.original.insight;
                    this.editing = false;
                },
                startEditing(focusField = 'goals') {
                    this.editing = true;
                    this.$nextTick(() => {
                        if (this.$refs[focusField]) {
                            this.$refs[focusField].focus();
                        }
                    });
                }
            }" 
            class="bg-white rounded-xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] hover:border-gray-200">
            
            <form action="<?php echo e($actionUrl); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true">
                <?php echo csrf_field(); ?>

                <!-- Header Banner -->
                <div class="bg-[#fbbf24] text-gray-900 font-bold text-center py-3 rounded-lg mb-6 shadow-sm flex items-center justify-center relative">
                    GROW MODEL
                </div>
                
                <div class="space-y-3">
                    <!-- GOALS -->
                    <div class="flex flex-col md:flex-row bg-[#d1fae5] rounded-lg overflow-hidden border border-[#a7f3d0] group">
                        <div class="bg-[#059669] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                            GOALS
                        </div>
                        <div x-show="!editing" <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?> @click="startEditing('goals')" <?php endif; ?> 
                            class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                            <span x-text="goals || 'What do you want?'" :class="!goals ? 'text-gray-400 italic' : ''"></span>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                            <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#059669] transition-opacity flex items-center gap-1 text-xs font-semibold">
                                <i class="fas fa-edit"></i> Edit
                            </div>
                            <?php endif; ?>
                        </div>
                        <div x-show="editing" style="display: none;" class="p-3 flex-1 flex flex-col bg-white/60">
                            <textarea name="grow_goals" x-model="goals" x-ref="goals" rows="2" 
                                class="w-full rounded border-gray-300 focus:border-[#059669] focus:ring focus:ring-[#059669]/20 text-sm p-2" 
                                placeholder="What do you want?"></textarea>
                        </div>
                    </div>
                    
                    <!-- REALITAS -->
                    <div class="flex flex-col md:flex-row bg-[#dbeafe] rounded-lg overflow-hidden border border-[#bfdbfe] group">
                        <div class="bg-[#3b82f6] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                            REALITY
                        </div>
                        <div x-show="!editing" <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?> @click="startEditing('realitas')" <?php endif; ?> 
                            class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                            <span x-text="realitas || 'Where are you now?'" :class="!realitas ? 'text-gray-400 italic' : ''"></span>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                            <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#3b82f6] transition-opacity flex items-center gap-1 text-xs font-semibold">
                                <i class="fas fa-edit"></i> Edit
                            </div>
                            <?php endif; ?>
                        </div>
                        <div x-show="editing" style="display: none;" class="p-3 flex-1 flex flex-col bg-white/60">
                            <textarea name="grow_realitas" x-model="realitas" x-ref="realitas" rows="2" 
                                class="w-full rounded border-gray-300 focus:border-[#3b82f6] focus:ring focus:ring-[#3b82f6]/20 text-sm p-2" 
                                placeholder="Where are you now?"></textarea>
                        </div>
                    </div>
                    
                    <!-- OPTIONS -->
                    <div class="flex flex-col md:flex-row bg-[#ede9fe] rounded-lg overflow-hidden border border-[#ddd6fe] group">
                        <div class="bg-[#8b5cf6] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                            OPTIONS
                        </div>
                        <div x-show="!editing" <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?> @click="startEditing('options')" <?php endif; ?> 
                            class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                            <span x-text="options || 'What could you do?'" :class="!options ? 'text-gray-400 italic' : ''"></span>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                            <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#8b5cf6] transition-opacity flex items-center gap-1 text-xs font-semibold">
                                <i class="fas fa-edit"></i> Edit
                            </div>
                            <?php endif; ?>
                        </div>
                        <div x-show="editing" style="display: none;" class="p-3 flex-1 flex flex-col bg-white/60">
                            <textarea name="grow_options" x-model="options" x-ref="options" rows="2" 
                                class="w-full rounded border-gray-300 focus:border-[#8b5cf6] focus:ring focus:ring-[#8b5cf6]/20 text-sm p-2" 
                                placeholder="What could you do?"></textarea>
                        </div>
                    </div>
                    
                    <!-- WRAP UP -->
                    <div class="flex flex-col md:flex-row bg-[#fee2e2] rounded-lg overflow-hidden border border-[#fecaca] group">
                        <div class="bg-[#ef4444] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                            WRAP UP
                        </div>
                        <div x-show="!editing" <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?> @click="startEditing('wrap_up')" <?php endif; ?> 
                            class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                            <span x-text="wrap_up || 'What will you do?'" :class="!wrap_up ? 'text-gray-400 italic' : ''"></span>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                            <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#ef4444] transition-opacity flex items-center gap-1 text-xs font-semibold">
                                <i class="fas fa-edit"></i> Edit
                            </div>
                            <?php endif; ?>
                        </div>
                        <div x-show="editing" style="display: none;" class="p-3 flex-1 flex flex-col bg-white/60">
                            <textarea name="grow_wrap_up" x-model="wrap_up" x-ref="wrap_up" rows="2" 
                                class="w-full rounded border-gray-300 focus:border-[#ef4444] focus:ring focus:ring-[#ef4444]/20 text-sm p-2" 
                                placeholder="What will you do?"></textarea>
                        </div>
                    </div>
                    
                    <!-- INSIGHT -->
                    <div class="flex flex-col md:flex-row bg-[#ffedd5] rounded-lg overflow-hidden border border-[#fed7aa] group">
                        <div class="bg-[#f97316] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
                            INSIGHT
                        </div>
                        <div x-show="!editing" <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?> @click="startEditing('insight')" <?php endif; ?> 
                            class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative cursor-pointer hover:bg-white/40 transition-colors min-h-[56px] flex items-center">
                            <span x-text="insight || 'Key takeaways and learning insights...'" :class="!insight ? 'text-gray-400 italic' : ''"></span>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                            <div class="absolute right-4 opacity-0 group-hover:opacity-100 text-[#f97316] transition-opacity flex items-center gap-1 text-xs font-semibold">
                                <i class="fas fa-edit"></i> Edit
                            </div>
                            <?php endif; ?>
                        </div>
                        <div x-show="editing" style="display: none;" class="p-3 flex-1 flex flex-col bg-white/60">
                            <textarea name="grow_insight" x-model="insight" x-ref="insight" rows="2" 
                                class="w-full rounded border-gray-300 focus:border-[#f97316] focus:ring focus:ring-[#f97316]/20 text-sm p-2" 
                                placeholder="Key takeaways and learning insights..."></textarea>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons when editing -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit_mentoring')): ?>
                    <div x-show="editing" style="display: none;" class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" @click="cancel()" 
                            class="px-4 py-2 text-xs font-medium text-gray-600 bg-white hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors shadow-sm">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 text-xs font-bold text-white bg-[#059669] hover:bg-[#047857] rounded-lg shadow-sm transition-colors flex items-center gap-1.5">
                            <i class="fas fa-save"></i> Save GROW Model
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit GROW Model (Alpine.js Animated) -->
<?php $__env->startPush('modals'); ?>
<!-- Session Details Modal -->
<div x-data="{ open: false }" @open-session-modal.window="open = true" class="relative z-[100]">
    <!-- Latar Belakang Hitam Transparan (Tanpa Blur) -->
    <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity cursor-pointer"
         @click="open = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="open" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0"
         @click.self="open = false">
        <div @click.stop
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto max-h-[90vh]">
            <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Session Details</h3>
                </div>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="<?php echo e(!empty($mentoring->id) ? route('mentoring.update-info', $mentoring->id) : route('mentoring.update-employee-info', $mentoring->karyawan->id)); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="flex flex-col overflow-hidden">
                <?php echo csrf_field(); ?>
                
                <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <!-- Year -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Year</label>
                        <input type="number" name="tahun" value="<?php echo e($mentoring->tahun ?? ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Period -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Period</label>
                        <input type="text" name="periode" value="<?php echo e($mentoring->periode ?? ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Project -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Project</label>
                        <input type="text" name="project" value="<?php echo e($mentoring->project ?? ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border" placeholder="Project Name">
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Status</label>
                        <select name="status_mentoring" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                            <option value="" <?php echo e(empty($mentoring->status_mentoring) ? 'selected' : ''); ?>>-</option>
                            <option value="in_progress" <?php echo e(in_array($mentoring->status_mentoring ?? '', ['in_progress', 'pending']) ? 'selected' : ''); ?>>In Progress</option>
                            <option value="completed" <?php echo e(($mentoring->status_mentoring ?? '') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    </div>
                    
                    <!-- Mentor -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Mentor</label>
                        <input type="text" name="mentor" value="<?php echo e($mentoring->mentor ?? ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Development Area -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Development Area</label>
                        <textarea name="development" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border resize-y"><?php echo e($mentoring->development ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                    <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-sky-500 text-sm font-medium text-white hover:bg-sky-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5"><i class="fas fa-save text-xs"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Progress Tracking Modal -->
<div x-data="{ open: false }" @open-progress-modal.window="open = true" class="relative z-[100]">
    <!-- Latar Belakang Hitam Transparan (Tanpa Blur) -->
    <div x-show="open" style="display: none;" class="fixed inset-0 bg-black/50 z-[100] transition-opacity cursor-pointer"
         @click="open = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
    <div x-show="open" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-0"
         @click.self="open = false">
        <div @click.stop
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-[360px] border border-gray-100 my-8 flex flex-col mx-auto max-h-[90vh]">
            
            <div class="bg-white px-5 pt-4 pb-2.5 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Progress Tracking</h3>
                </div>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 p-1 focus:outline-none transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="<?php echo e(!empty($mentoring->id) ? route('mentoring.update-progress', $mentoring->id) : route('mentoring.update-employee-progress', $mentoring->karyawan->id)); ?>" method="POST" hx-target="body" hx-swap="innerHTML" hx-push-url="true" class="flex flex-col">
                <?php echo csrf_field(); ?>
                
                <div class="bg-white px-5 py-3 sm:px-6 space-y-2.5 max-h-[50vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <!-- Target Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Target Setting</label>
                        <input type="date" name="target" value="<?php echo e($mentoring->target ? \Carbon\Carbon::parse($mentoring->target)->format('Y-m-d') : ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Monitoring Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Monitoring</label>
                        <input type="date" name="monitoring" value="<?php echo e($mentoring->monitoring ? \Carbon\Carbon::parse($mentoring->monitoring)->format('Y-m-d') : ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                    
                    <!-- Final Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Final Review</label>
                        <input type="date" name="final" value="<?php echo e($mentoring->final ? \Carbon\Carbon::parse($mentoring->final)->format('Y-m-d') : ''); ?>" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-1.5 px-2.5 border">
                    </div>
                </div>
                
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2 sm:px-6">
                    <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition-all sm:w-auto">Cancel</button>
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-md px-4 py-1.5 bg-sky-500 text-sm font-medium text-white hover:bg-sky-600 focus:outline-none transition-all sm:w-auto flex items-center gap-1.5"><i class="fas fa-save text-xs"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\radarhc\resources\views\mentoring\show.blade.php ENDPATH**/ ?>