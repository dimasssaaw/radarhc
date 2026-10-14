    <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" class="transition-all duration-300 bg-[#ffffff] border-r border-white/50 flex flex-col justify-between hidden md:flex h-full shadow-[4px_0_20px_rgba(166,180,200,0.3)] z-20 relative">
        
        <!-- Header / Logo Area (Fixed at top) -->
        <div class="py-6 flex items-center relative min-h-[80px] shrink-0" :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between px-5'">
            <div class="flex items-center gap-3 overflow-hidden" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                <div class="w-8 h-8 bg-[#00b4d8] rounded shrink-0 flex items-center justify-center">
                    <i class="fas fa-bullseye text-white text-lg"></i>
                </div>
                <div x-show="!sidebarCollapsed" class="whitespace-nowrap transition-opacity duration-300">
                    <h2 class="font-bold text-gray-900 leading-tight">SORA</h2>
                    <p class="text-[10px] text-gray-400">HUMAN CAPITAL - SDI</p>
                </div>
            </div>
            
            <!-- Inline Toggle Button for Expanded State -->
            <button @click="sidebarCollapsed = true" 
                    x-show="!sidebarCollapsed"
                    class="text-gray-400 hover:text-[#00b4d8] focus:outline-none transition-all shrink-0 z-30">
                <i class="fas fa-bars text-lg"></i>
            </button>

            <!-- Floating Toggle Button for Collapsed State -->
            <button @click="sidebarCollapsed = false" 
                    x-show="sidebarCollapsed"
                    style="display: none;"
                    x-transition
                    class="absolute -right-3.5 top-0 bottom-0 my-auto bg-white border border-gray-200 rounded-full w-7 h-7 flex items-center justify-center shadow-[0_2px_10px_rgba(0,0,0,0.1)] text-gray-400 hover:text-[#00b4d8] hover:scale-110 focus:outline-none transition-all z-[60]">
                <i class="fas fa-chevron-right text-[10px] ml-[1px]"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto overflow-x-hidden no-scrollbar">
            
            <!-- Navigation -->
            <nav class="mt-2 flex flex-col gap-1 pb-4">
                <a href="<?php echo e(route('dashboard') ?? 'javascript:void(0);'); ?>" class="menu-3d flex items-center font-medium text-sm <?php echo e(request()->routeIs('dashboard') ? 'sidebar-active text-[#0284c7]' : 'text-gray-600'); ?>" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'gap-3'">
                    <i class="fas fa-border-all w-5 text-center text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Dashboard HC</span>
                </a>
                
                <?php if(strtolower(auth()->user()->role ?? '') === 'admin'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_karyawan')): ?>
                <a href="<?php echo e(route('employee.index')); ?>" class="menu-3d flex items-center font-medium text-sm <?php echo e(request()->is('employee-data*') ? 'sidebar-active text-[#0284c7]' : 'text-gray-600'); ?>" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'gap-3'">
                    <i class="fas fa-users w-5 text-center text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Employee Data</span>
                </a>
                <?php endif; ?>
                <?php endif; ?>
                
                <?php if(strtolower(auth()->user()->role ?? '') !== 'admin'): ?>
                <a href="<?php echo e(route('profile.index')); ?>" class="menu-3d flex items-center font-medium text-sm <?php echo e(request()->is('profile*') ? 'sidebar-active text-[#0284c7]' : 'text-gray-600'); ?>" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'gap-3'">
                    <i class="fas fa-user w-5 text-center text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Profile</span>
                </a>
                <?php endif; ?>
               
                <!-- Development Dropdown -->
                <div x-data="{ open: <?php echo e(request()->is('assessment-report*') || request()->is('user-assessment-report*') || request()->is('mentoring*') || request()->is('user-mentoring*') || request()->is('idp*') || request()->is('user-idp*') || request()->is('training*') || request()->is('certification*') ? 'true' : 'false'); ?> }">
                    <button @click="if(!sidebarCollapsed) open = !open" class="w-full menu-3d flex items-center text-gray-600 font-medium text-sm" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'justify-between'">
                        <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center w-full' : 'gap-3'">
                            <i class="fas fa-chart-line w-5 text-center text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Development</span>
                        </div>
                        <i x-show="!sidebarCollapsed" class="fas fa-chevron-up text-xs mr-3" :class="{'rotate-180': !open}"></i>
                    </button>
                    <div x-show="open && !sidebarCollapsed" class="flex flex-col pl-8 pr-4 py-2 text-xs text-gray-500 ml-6 transition-all whitespace-nowrap overflow-hidden">
                        <?php if(strtolower(auth()->user()->role ?? '') === 'admin'): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_assessment')): ?>
                            <a href="<?php echo e(route('assessment-report.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('assessment-report*') ? 'active font-bold' : ''); ?>"><i class="far fa-clipboard"></i> Assessment Report</a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(strtolower(auth()->user()->role ?? '') !== 'admin'): ?>
                            <a href="<?php echo e(route('user-assessment-report.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('user-assessment-report*') ? 'active font-bold' : ''); ?>"><i class="far fa-clipboard"></i> Assessment Report</a>
                        <?php endif; ?>
                        <?php if(strtolower(auth()->user()->role ?? '') === 'admin'): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_mentoring')): ?>
                            <a href="<?php echo e(route('mentoring.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('mentoring*') ? 'active font-bold' : ''); ?>"><i class="fas fa-handshake"></i> Mentoring</a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(strtolower(auth()->user()->role ?? '') !== 'admin'): ?>
                            <a href="<?php echo e(route('user-mentoring.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('user-mentoring*') ? 'active font-bold' : ''); ?>"><i class="fas fa-handshake"></i> Mentoring</a>
                        <?php endif; ?>
                        <?php if(strtolower(auth()->user()->role ?? '') === 'admin'): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_idp')): ?>
                            <a href="<?php echo e(route('idp.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('idp*') ? 'active font-bold' : ''); ?>"><i class="fas fa-bullseye"></i> IDP</a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(strtolower(auth()->user()->role ?? '') !== 'admin'): ?>
                            <a href="<?php echo e(route('user-idp.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('user-idp*') ? 'active font-bold' : ''); ?>"><i class="fas fa-bullseye"></i> IDP</a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_training')): ?>
                            <a href="<?php echo e(route('training.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('training*') ? 'active font-bold' : ''); ?>"><i class="fas fa-graduation-cap"></i> Training</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('user-training.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('user-training*') ? 'active font-bold' : ''); ?>"><i class="fas fa-graduation-cap"></i> Training</a>
                        <?php endif; ?>
                        <?php if(strtolower(auth()->user()->role ?? '') === 'admin'): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_certification')): ?>
                            <a href="<?php echo e(route('certification.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('certification*') ? 'active font-bold' : ''); ?>"><i class="fas fa-certificate"></i> Certification</a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(strtolower(auth()->user()->role ?? '') !== 'admin'): ?>
                            <a href="<?php echo e(route('user-certification.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('user-certification*') ? 'active font-bold' : ''); ?>"><i class="fas fa-certificate"></i> Certification</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo e(route('employee-journey.index')); ?>" class="menu-3d flex items-center font-medium text-sm <?php echo e(request()->is('employee-journey*') ? 'sidebar-active text-[#0284c7]' : 'text-gray-600'); ?>" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'gap-3'">
                    <i class="far fa-compass w-5 text-center text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Employee Journey</span>
                </a>
                


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['lihat_departemen', 'lihat_jabatan', 'lihat_role'])): ?>
                <div x-data="{ open: <?php echo e(request()->is('department*') || request()->is('role*') || request()->is('positions*') ? 'true' : 'false'); ?> }">
                    <button @click="if(!sidebarCollapsed) open = !open" class="w-full menu-3d flex items-center text-gray-600 font-medium text-sm" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'justify-between'">
                        <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center w-full' : 'gap-3'">
                            <!-- Ikon Master Data diubah menjadi fa-database -->
                            <i class="fas fa-database w-5 text-center text-lg"></i> 
                            <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Master Data</span>
                        </div>
                        <i x-show="!sidebarCollapsed" class="fas fa-chevron-up text-xs mr-3" :class="{'rotate-180': !open}"></i>
                    </button>
                    <div x-show="open && !sidebarCollapsed" class="flex flex-col pl-8 pr-4 py-2 text-xs text-gray-500 ml-6 transition-all whitespace-nowrap overflow-hidden">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_departemen')): ?>
                        <a href="<?php echo e(route('department.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('department*') ? 'active font-bold' : ''); ?>"><i class="fas fa-building w-5 text-center text-lg"></i> Department</a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_jabatan')): ?>
                        <a href="<?php echo e(route('position.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('positions*') ? 'active font-bold' : ''); ?>"><i class="fas fa-briefcase w-5 text-center text-lg"></i> Jabatan</a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_role')): ?>
                        <a href="<?php echo e(route('role.index')); ?>" class="submenu-3d flex items-center gap-2 <?php echo e(request()->is('role*') ? 'active font-bold' : ''); ?>"><i class="fas fa-user-shield w-5 text-center text-lg"></i> Role User</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lihat_user')): ?>
                <a href="<?php echo e(route('user-management.index')); ?>" class="menu-3d flex items-center font-medium text-sm <?php echo e(request()->is('user-management*') ? 'sidebar-active text-[#0284c7]' : 'text-gray-600'); ?>" :class="sidebarCollapsed ? 'justify-center !px-0 !mx-auto w-11 h-11 mb-1' : 'gap-3'">
                    <i class="fas fa-user-cog w-5 text-center text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Manage Users</span>
                </a>
                <?php endif; ?>
            </nav>
        </div>
        
        <!-- Logout -->
        <div class="p-6 border-t border-gray-100 shrink-0" :class="sidebarCollapsed ? 'flex justify-center px-0' : ''">
            <form method="POST" action="<?php echo e(route('logout')); ?>" :class="sidebarCollapsed ? 'w-12' : 'w-full'">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-[#00b4d8] hover:bg-[#0096c7] text-white font-medium rounded-lg flex items-center justify-center transition-all duration-300 transform hover:-translate-y-0.5 shadow-sm hover:shadow-[0_6px_15px_rgba(0,180,216,0.4)]" :class="sidebarCollapsed ? 'h-12 !p-0 mx-auto' : 'py-2.5 gap-2'">
                    <i class="fas fa-sign-out-alt text-lg"></i> <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>
<?php /**PATH C:\laragon\www\radarhc\resources\views/components/sidebar.blade.php ENDPATH**/ ?>