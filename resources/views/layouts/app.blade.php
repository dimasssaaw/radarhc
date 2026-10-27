<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Sora</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Tom Select for beautiful 3D dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!-- Flatpickr for custom themed date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
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

        /* Flatpickr Custom Theme matching Sora / RadarHC - Compact Size */
        @keyframes fpFadeIn {
            from { opacity: 0; transform: translateY(-4px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .flatpickr-calendar {
            font-family: 'Inter', sans-serif !important;
            background: #ffffff !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
            padding: 6px 8px !important;
            width: 252px !important;
            z-index: 999999 !important;
            animation: fpFadeIn 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after,
        .flatpickr-calendar.arrowBottom:before,
        .flatpickr-calendar.arrowBottom:after {
            display: none !important;
        }
        .flatpickr-months {
            display: flex !important;
            align-items: center !important;
            margin-bottom: 2px !important;
            position: relative !important;
            height: 28px !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #0f172a !important;
            fill: #0f172a !important;
            height: 28px !important;
        }
        .flatpickr-current-month {
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            color: #0f172a !important;
        }
        .flatpickr-current-month .cur-month {
            font-weight: 700 !important;
            color: #0f172a !important;
            margin-right: 4px !important;
        }
        .flatpickr-current-month input.cur-year {
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 24px !important;
            height: 24px !important;
            border-radius: 0.375rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #64748b !important;
            fill: #64748b !important;
            padding: 0 !important;
            transition: all 0.15s !important;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 10px !important;
            height: 10px !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: #f1f5f9 !important;
            color: #0ea5e9 !important;
            fill: #0ea5e9 !important;
        }
        .flatpickr-weekdays {
            margin-bottom: 2px !important;
            height: 20px !important;
        }
        span.flatpickr-weekday {
            color: #94a3b8 !important;
            font-size: 0.68rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.01em !important;
        }
        .flatpickr-days {
            width: 236px !important;
        }
        .dayContainer {
            width: 236px !important;
            min-width: 236px !important;
            max-width: 236px !important;
            justify-content: space-around !important;
        }
        .flatpickr-day {
            max-width: 30px !important;
            height: 28px !important;
            line-height: 28px !important;
            border-radius: 0.375rem !important;
            font-size: 0.785rem !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            border: 1px solid transparent !important;
            margin: 1px 0.5px !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-day:hover {
            background: #f0f9ff !important;
            color: #0284c7 !important;
            border-color: #bae6fd !important;
        }
        .flatpickr-day.today {
            border-color: #0ea5e9 !important;
            color: #0284c7 !important;
            font-weight: 700 !important;
        }
        .flatpickr-day.today:hover {
            background: #e0f2fe !important;
        }
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover,
        .flatpickr-day.selected:focus {
            background: #0ea5e9 !important;
            border-color: #0ea5e9 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 6px rgba(14, 165, 233, 0.3) !important;
        }
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #cbd5e1 !important;
        }
        .flatpickr-custom-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 4px 0 4px !important;
            border-top: 1px solid #f1f5f9;
            margin-top: 4px !important;
        }
        .flatpickr-custom-footer button {
            background: transparent;
            border: none;
            font-size: 0.72rem !important;
            font-weight: 600;
            cursor: pointer;
            padding: 2px 6px !important;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .flatpickr-custom-footer .flatpickr-btn-clear {
            color: #ef4444;
        }
        .flatpickr-custom-footer .flatpickr-btn-clear:hover {
            background: #fef2f2;
        }
        .flatpickr-custom-footer .flatpickr-btn-today {
            color: #0ea5e9;
        }
        .flatpickr-custom-footer .flatpickr-btn-today:hover {
            background: #f0f9ff;
        }
        /* Style date input to show calendar icon and hide native picker */
        input[data-flatpickr],
        input.datepicker {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1.15rem !important;
            padding-right: 2.25rem !important;
            cursor: pointer !important;
        }
        input::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none !important;
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
    @include('components.sidebar')

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
                            {{ strtoupper(substr(auth()->user()->name ?? 'HR', 0, 2)) }}
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
                            <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] font-bold text-[#00b4d8] uppercase tracking-widest mt-1">{{ auth()->user()->role ?? 'HR / Head' }}</p>
                        </div>
                        <div class="p-2">
                            <form method="POST" action="{{ route('logout') }}" hx-boost="false" data-hx-boost="false" hx-disable>
                                @csrf
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
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-20 right-8 z-[100] bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 transition-all">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <h4 class="font-bold text-sm">Berhasil</h4>
                <p class="text-xs">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-4 text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        @endif

        @if(session('error') || $errors->any())
        <div x-data="{ show: true }" x-show="show" class="fixed top-20 right-8 z-[100] bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-start gap-3 transition-all max-w-sm">
            <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Terjadi Kesalahan</h4>
                <p class="text-xs mt-1">{{ session('error') ?? 'Ada kesalahan pada formulir yang Anda kirimkan. Silakan periksa kembali.' }}</p>
                @if($errors->any())
                    <ul class="text-xs mt-2 list-disc pl-4 opacity-90">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <button @click="show = false" class="ml-4 text-white/80 hover:text-white mt-0.5"><i class="fas fa-times"></i></button>
        </div>
        @endif

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8 no-scrollbar bg-[#f8fafc]">
            @yield('content')
        </div>
    </main>

    @stack('modals')
    @yield('scripts')
    
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

        function initFlatpickr(container) {
            let root = (container instanceof HTMLElement) ? container : document;
            let localeId = (typeof flatpickr !== 'undefined' && flatpickr.l10ns && flatpickr.l10ns.id) ? flatpickr.l10ns.id : 'default';
            
            root.querySelectorAll('input[type="date"], input.datepicker, input[data-flatpickr]').forEach(function(el) {
                if (el._flatpickr) return;
                
                el.type = 'text';
                el.setAttribute('data-flatpickr', 'true');
                el.setAttribute('placeholder', el.getAttribute('placeholder') || 'YYYY-MM-DD');
                
                flatpickr(el, {
                    dateFormat: "Y-m-d",
                    locale: localeId,
                    disableMobile: true,
                    allowInput: true,
                    position: "auto",
                    onReady: function(selectedDates, dateStr, instance) {
                        if (!instance.calendarContainer.querySelector('.flatpickr-custom-footer')) {
                            let footer = document.createElement('div');
                            footer.className = 'flatpickr-custom-footer';
                            footer.innerHTML = `
                                <button type="button" class="flatpickr-btn-clear">Hapus</button>
                                <button type="button" class="flatpickr-btn-today">Hari Ini</button>
                            `;
                            footer.querySelector('.flatpickr-btn-clear').addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                instance.clear();
                                instance.close();
                            });
                            footer.querySelector('.flatpickr-btn-today').addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                instance.setDate(new Date());
                                instance.close();
                            });
                            instance.calendarContainer.appendChild(footer);
                        }
                    }
                });
            });
        }
        window.initFlatpickr = initFlatpickr;
        
        document.addEventListener('DOMContentLoaded', () => {
            initTomSelect();
            initFlatpickr();
        });
        document.addEventListener('htmx:afterSwap', (e) => {
            initTomSelect(e.detail?.target);
            initFlatpickr(e.detail?.target);
        });

        // Dynamic fallback for any modal or input clicked/focused
        document.addEventListener('focusin', function(e) {
            if (e.target && (e.target.matches('input[type="date"], input.datepicker') || e.target.hasAttribute('data-flatpickr'))) {
                if (!e.target._flatpickr) {
                    initFlatpickr(e.target.parentElement || document);
                    if (e.target._flatpickr) {
                        e.target._flatpickr.open();
                    }
                }
            }
        });
        
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
                window.location.href = '{{ route('login') }}';
            }
        });
        document.addEventListener('htmx:beforeSwap', function(event) {
            if (event.detail?.xhr?.status === 401 || event.detail?.xhr?.status === 419) {
                window.location.href = '{{ route('login') }}';
                event.detail.shouldSwap = false;
            }
            let responseUrl = event.detail?.xhr?.responseURL;
            if (responseUrl && (responseUrl.endsWith('/login') || responseUrl === '{{ url('/') }}' || responseUrl === '{{ url('/') }}/' || responseUrl === '{{ route('login') }}')) {
                window.location.href = '{{ route('login') }}';
                event.detail.shouldSwap = false;
            }
        });
    </script>
</body>
</html>

