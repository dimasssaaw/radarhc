<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | Sora</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Tom Select for beautiful 3D dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        /* Tom Select Custom Styles (Match standard form inputs) */
        .ts-wrapper {
            width: 100%;
        }
        .ts-control {
            background-color: #ffffff !important;
            border-radius: 0.5rem !important; /* rounded-lg */
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; /* shadow-sm */
            padding: 0.5rem 0.75rem !important; /* py-2 px-3 */
            font-size: 0.875rem !important; /* text-sm */
            transition: all 0.2s ease !important;
            min-height: 42px !important;
            display: flex;
            align-items: center;
            color: #374151 !important; /* text-gray-700 */
        }
        .ts-control.focus {
            border-color: #0ea5e9 !important; /* focus:border-[#0ea5e9] */
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2) !important; /* focus:ring-[#0ea5e9] */
            outline: none !important;
        }
        .ts-dropdown {
            background-color: #ffffff !important;
            border-radius: 0.5rem !important; /* rounded-lg */
            border: 1px solid #e5e7eb !important; /* border-gray-200 */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; /* shadow-lg */
            margin-top: 4px !important;
            font-size: 0.875rem !important;
            z-index: 9999 !important; /* Ensure it stays above modals */
            padding: 4px !important;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
            width: 6px;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-track {
            background: transparent;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.375rem !important; /* rounded-md */
            transition: all 0.2s !important;
            color: #374151 !important;
        }
        .ts-dropdown .option:hover {
            background-color: #f3f4f6 !important; /* bg-gray-100 */
        }
        .ts-dropdown .option.active {
            background-color: #e0f2fe !important; /* sky-100 */
            color: #0284c7 !important; /* sky-600 */
            font-weight: 500 !important;
        }
        .ts-wrapper.drop-up .ts-dropdown {
            top: auto !important;
            bottom: 100% !important;
            margin-top: 0 !important;
            margin-bottom: 4px !important;
            box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.1), 0 -4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border-radius: 0.5rem !important;
        }
        /* Hide default arrow and add fontawesome arrow */
        .ts-control::after {
            content: '\f078' !important;
            font-family: 'Font Awesome 5 Free' !important;
            font-weight: 900 !important;
            border: none !important;
            color: #94a3b8 !important;
            right: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            position: absolute !important;
            pointer-events: none !important;
            margin: 0 !important;
            transition: transform 0.3s ease !important;
        }
        .ts-wrapper.dropdown-active .ts-control::after {
            transform: translateY(-50%) rotate(180deg) !important;
            color: #0ea5e9 !important;
        }
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; }
        .sidebar-active { color: #0284c7; }
        .menu-3d {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 16px;
            padding: 10px 16px;
            border: 1px solid transparent;
        }
        .menu-3d:hover {
            box-shadow: 
                4px 4px 8px rgba(166, 180, 200, 0.4), 
                -4px -4px 8px rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            background-color: #ffffff;
            color: #0284c7;
        }
        .menu-3d.sidebar-active {
            box-shadow: 
                inset 3px 3px 6px rgba(166, 180, 200, 0.3), 
                inset -3px -3px 6px rgba(255, 255, 255, 1);
            background-color: #f8fafc;
            color: #0284c7;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }
        .submenu-3d {
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 8px 12px;
            border: 1px solid transparent;
            margin-bottom: 4px;
        }
        .submenu-3d:hover {
            box-shadow: 
                3px 3px 6px rgba(166, 180, 200, 0.4), 
                -3px -3px 6px rgba(255, 255, 255, 1);
            transform: translateY(-1px);
            background-color: #ffffff;
            color: #0284c7;
        }
        .submenu-3d.active {
            box-shadow: 
                inset 2px 2px 4px rgba(166, 180, 200, 0.3), 
                inset -2px -2px 4px rgba(255, 255, 255, 1);
            background-color: #f8fafc;
            color: #0284c7;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* 3D Neumorphic Styles */
        .card-3d {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 
                8px 8px 16px rgba(166, 180, 200, 0.5), 
                -8px -8px 16px rgba(255, 255, 255, 0.8),
                inset 1px 1px 3px rgba(255, 255, 255, 0.6),
                inset -1px -1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        .card-3d:hover {
            transform: translateY(-4px);
            box-shadow: 
                12px 12px 20px rgba(166, 180, 200, 0.6), 
                -12px -12px 20px rgba(255, 255, 255, 0.9),
                inset 1px 1px 3px rgba(255, 255, 255, 0.6),
                inset -1px -1px 3px rgba(0, 0, 0, 0.05);
        }
        .card-3d-inner {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 
                inset 6px 6px 12px rgba(166, 180, 200, 0.6), 
                inset -6px -6px 12px rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .icon-3d {
            background: #ffffff;
            box-shadow: 
                4px 4px 8px rgba(166, 180, 200, 0.6), 
                -4px -4px 8px rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .header-3d {
            background: #ffffff;
            box-shadow: 4px 4px 15px rgba(166, 180, 200, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
        }
        .btn-3d {
            background: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            box-shadow: 
                4px 4px 8px rgba(166, 180, 200, 0.5), 
                -4px -4px 8px rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease;
            color: #4b5563;
        }
        .btn-3d:hover {
            box-shadow: 
                2px 2px 5px rgba(166, 180, 200, 0.6), 
                -2px -2px 5px rgba(255, 255, 255, 0.9);
            transform: translateY(1px);
        }
        .btn-3d:active {
            box-shadow: 
                inset 2px 2px 5px rgba(166, 180, 200, 0.5), 
                inset -2px -2px 5px rgba(255, 255, 255, 0.8);
            transform: translateY(2px);
        }
    </style>
    <!-- HTMX for SPA without refresh -->
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
    <script src="https://unpkg.com/htmx.org@1.9.12/dist/ext/preload.js"></script>
    <!-- NProgress for loading bar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <style>
        #nprogress .bar { background: #0ea5e9 !important; height: 3px !important; }
        #nprogress .peg { box-shadow: 0 0 10px #0ea5e9, 0 0 5px #0ea5e9 !important; }
        #nprogress .spinner-icon { border-top-color: #0ea5e9 !important; border-left-color: #0ea5e9 !important; }
    </style>
    <!-- CSS block hiding actions for non-admin removed because it conflicts with Spatie permissions -->
</head>
<body class="flex h-screen overflow-hidden text-gray-800" hx-ext="preload" preload="mouseover" hx-boost="true" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))">

    <!-- SIDEBAR -->
    <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full bg-[#ffffff] overflow-hidden z-10 relative">
        
        <!-- Top Header -->
        <header class="h-16 header-3d flex items-center justify-end px-8 shrink-0 z-40 relative">
            <div class="flex items-center gap-6 text-gray-500">
                

                <!-- Profile Dropdown -->
                <div x-data="{ 
                    open: false,
                    dropUp: false,
                    toggle(btnEl) {
                        this.open = !this.open;
                        if (this.open) {
                            let rect = btnEl.getBoundingClientRect();
                            let spaceBelow = window.innerHeight - rect.bottom;
                            let spaceAbove = rect.top;
                            this.dropUp = spaceBelow < 200 && spaceAbove > spaceBelow;
                        }
                    }
                }" class="relative">
                    <button type="button" @click="toggle($el)" @click.away="open = false" class="focus:outline-none flex items-center gap-2 p-1 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-[#00b4d8] rounded-full overflow-hidden border-2 border-white shadow-sm flex items-center justify-center text-white font-bold text-xs">
                            <?php echo e(strtoupper(substr(auth()->user()->name ?? 'HR', 0, 2))); ?>

                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" style="display: none;"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         :class="dropUp ? 'bottom-full mb-3 origin-bottom-right' : 'top-full mt-3 origin-top-right'"
                         class="absolute right-0 w-56 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden z-50">
                        
                        <div class="px-4 py-4 border-b border-gray-50 bg-gradient-to-br from-sky-50 to-white">
                            <p class="text-sm font-bold text-gray-800 truncate"><?php echo e(auth()->user()->name ?? 'Administrator'); ?></p>
                            <p class="text-[10px] font-bold text-[#00b4d8] uppercase tracking-widest mt-1"><?php echo e(auth()->user()->role ?? 'HR / Head'); ?></p>
                        </div>
                        <div class="p-2">
                            <form method="POST" action="<?php echo e(route('logout')); ?>" hx-boost="false" data-hx-boost="false" hx-disable>
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-3 py-2 text-xs font-bold text-rose-600 rounded-lg hover:bg-rose-50 transition-colors flex items-center gap-3 focus:outline-none">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </header>

        <!-- Global Flash Messages -->
        <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-20 right-8 z-[100] bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 transition-all">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <h4 class="font-bold text-sm">Berhasil</h4>
                <p class="text-xs"><?php echo e(session('success')); ?></p>
            </div>
            <button @click="show = false" class="ml-4 text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>

        <?php if(session('error') || $errors->any()): ?>
        <div x-data="{ show: true }" x-show="show" class="fixed top-20 right-8 z-[100] bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-start gap-3 transition-all max-w-sm">
            <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Terjadi Kesalahan</h4>
                <p class="text-xs mt-1"><?php echo e(session('error') ?? 'Ada kesalahan pada formulir yang Anda kirimkan. Silakan periksa kembali.'); ?></p>
                <?php if($errors->any()): ?>
                    <ul class="text-xs mt-2 list-disc pl-4 opacity-90">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>
            <button @click="show = false" class="ml-4 text-white/80 hover:text-white mt-0.5"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8 no-scrollbar bg-[#f8fafc]">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <?php echo $__env->yieldPushContent('modals'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>
    
    <script>
        function initTomSelect(container) {
            let root = (container instanceof HTMLElement) ? container : document;
            root.querySelectorAll('select').forEach(function(el) {
                if (el.classList.contains('native-select')) return;
                if (el.tomselect) return; // Prevent double initialization
                
                let isSearchable = el.classList.contains('searchable');
                let isDropUp = el.classList.contains('drop-up');
                el.className = isDropUp ? 'drop-up' : '';
                let ts = new TomSelect(el, {
                    controlInput: isSearchable ? undefined : null
                });
                
                let reposition = function() {
                    let controlRect = ts.control.getBoundingClientRect();
                    let dropdownHeight = Math.max(ts.dropdown.scrollHeight || 0, ts.dropdown.offsetHeight || 0, 240);
                    let spaceBelow = window.innerHeight - controlRect.bottom;
                    let spaceAbove = controlRect.top;
                    if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                        ts.wrapper.classList.add('drop-up');
                    } else {
                        ts.wrapper.classList.remove('drop-up');
                    }
                };
                
                ts.on('dropdown_open', reposition);
            });
        }
        window.initTomSelect = initTomSelect;
        
        document.addEventListener('DOMContentLoaded', () => initTomSelect());
        document.addEventListener('htmx:afterSwap', (e) => initTomSelect(e.detail?.target));
        
        // NProgress binding for HTMX
        document.addEventListener('htmx:beforeRequest', function() {
            NProgress.start();
        });
        document.addEventListener('htmx:afterRequest', function() {
            NProgress.done();
        });
        document.addEventListener('htmx:historyRestore', function() {
            NProgress.done();
        });

        // Redirect completely if session expired (401/419) or response redirected to login
        document.addEventListener('htmx:responseError', function(event) {
            if (event.detail?.xhr?.status === 401 || event.detail?.xhr?.status === 419) {
                window.location.href = '<?php echo e(route('login')); ?>';
            }
        });
        document.addEventListener('htmx:beforeSwap', function(event) {
            if (event.detail?.xhr?.status === 401 || event.detail?.xhr?.status === 419) {
                window.location.href = '<?php echo e(route('login')); ?>';
                event.detail.shouldSwap = false;
            }
            let responseUrl = event.detail?.xhr?.responseURL;
            if (responseUrl && (responseUrl.endsWith('/login') || responseUrl === '<?php echo e(url('/')); ?>' || responseUrl === '<?php echo e(url('/')); ?>/' || responseUrl === '<?php echo e(route('login')); ?>')) {
                window.location.href = '<?php echo e(route('login')); ?>';
                event.detail.shouldSwap = false;
            }
        });
    </script>
</body>
</html>

<?php /**PATH C:\laragon\www\radarhc\resources\views/layouts/app.blade.php ENDPATH**/ ?>