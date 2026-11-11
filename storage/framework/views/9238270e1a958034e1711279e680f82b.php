<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
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
    <!-- Flatpickr for custom themed date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
            border-radius: 0.75rem !important; /* rounded-xl */
            border: 1px solid #e2e8f0 !important; /* border-slate-200 */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            margin-top: 4px !important;
            font-size: 0.875rem !important;
            z-index: 999999 !important; /* Ensure it stays above modals and backdrops */
            padding: 6px !important;
        }
        .ts-dropdown.is-dropup {
            margin-top: 0 !important;
            margin-bottom: 4px !important;
            box-shadow: 0 -10px 25px -5px rgba(0, 0, 0, 0.12), 0 -8px 10px -6px rgba(0, 0, 0, 0.05) !important;
        }
        .ts-dropdown .ts-dropdown-content {
            max-height: 220px !important;
            overflow-y: auto !important;
        }
        /* Completely remove WebKit scrollbar top and bottom arrow buttons */
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-button,
        .ts-dropdown *::-webkit-scrollbar-button,
        .ts-dropdown::-webkit-scrollbar-button {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
            width: 5px !important;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-track {
            background: transparent !important;
            margin: 4px 0 !important;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb {
            background: #cbd5e1 !important;
            border-radius: 9999px !important;
        }
        .ts-dropdown .ts-dropdown-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8 !important;
        }
        .ts-dropdown .optgroup-header {
            font-size: 0.68rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            color: #475569 !important; /* slate-600 */
            background-color: #f1f5f9 !important; /* slate-100 */
            padding: 5px 10px !important;
            margin: 6px 2px 3px 2px !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
        }
        .ts-dropdown .optgroup:first-child .optgroup-header {
            margin-top: 2px !important;
        }
        .ts-dropdown .optgroup .option {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important; /* rounded-md */
            transition: all 0.15s ease !important;
            color: #334155 !important;
        }
        .ts-dropdown .option:hover {
            background-color: #f8fafc !important;
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
            height: 28px !important;
        }
        /* Custom Month Trigger in Calendar Header (Matches System UI) */
        .flatpickr-current-month .cur-month {
            font-weight: 700 !important;
            color: #0f172a !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            font-size: 0.85rem !important;
            line-height: 1 !important;
            transition: all 0.15s ease !important;
            user-select: none !important;
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            margin-right: 4px !important;
            pointer-events: auto !important;
        }
        .flatpickr-current-month .cur-month:hover,
        .flatpickr-current-month .cur-month.active {
            background-color: #f0f9ff !important;
            border-color: #38bdf8 !important;
            color: #0284c7 !important;
        }
        .flatpickr-current-month .cur-month .month-chevron {
            font-size: 8px !important;
            color: #94a3b8 !important;
            margin-left: 2px !important;
            transition: transform 0.2s ease !important;
            pointer-events: none !important;
        }
        .flatpickr-current-month .cur-month:hover .month-chevron,
        .flatpickr-current-month .cur-month.active .month-chevron {
            color: #0ea5e9 !important;
        }
        .flatpickr-current-month .cur-month.active .month-chevron {
            transform: rotate(180deg) !important;
        }
        .flatpickr-monthDropdown-months {
            display: none !important;
        }

        /* Custom Month Popup Menu (Matches System UI) */
        .flatpickr-custom-month-menu {
            position: absolute !important;
            top: 36px !important;
            left: 8px !important;
            right: 8px !important;
            background: #ffffff !important;
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            z-index: 9999 !important;
            padding: 8px !important;
            animation: fpFadeIn 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .flatpickr-custom-month-menu.hidden {
            display: none !important;
        }
        .flatpickr-custom-month-menu .month-menu-header {
            font-size: 10px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #64748b !important;
            text-align: center !important;
            padding-bottom: 6px !important;
            margin-bottom: 6px !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .flatpickr-custom-month-menu .month-menu-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 4px !important;
        }
        .flatpickr-custom-month-menu .month-menu-item {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 6px 2px !important;
            border-radius: 8px !important;
            font-family: 'Inter', sans-serif !important;
            background: #ffffff !important;
            border: 1px solid transparent !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            user-select: none !important;
            pointer-events: auto !important;
        }
        .flatpickr-custom-month-menu .month-menu-item .m-short,
        .flatpickr-custom-month-menu .month-menu-item .m-full {
            pointer-events: none !important;
        }
        .flatpickr-custom-month-menu .month-menu-item .m-short {
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            line-height: 1.2 !important;
        }
        .flatpickr-custom-month-menu .month-menu-item .m-full {
            font-size: 0.62rem !important;
            font-weight: 500 !important;
            color: #64748b !important;
            line-height: 1 !important;
        }
        .flatpickr-custom-month-menu .month-menu-item:hover {
            background-color: #f0f9ff !important;
            border-color: #bae6fd !important;
        }
        .flatpickr-custom-month-menu .month-menu-item:hover .m-short,
        .flatpickr-custom-month-menu .month-menu-item:hover .m-full {
            color: #0284c7 !important;
        }
        .flatpickr-custom-month-menu .month-menu-item.active {
            background-color: #0ea5e9 !important;
            border-color: #0284c7 !important;
            box-shadow: 0 2px 4px rgba(14, 165, 233, 0.25) !important;
        }
        .flatpickr-custom-month-menu .month-menu-item.active .m-short {
            color: #ffffff !important;
            font-weight: 800 !important;
        }
        .flatpickr-custom-month-menu .month-menu-item.active .m-full {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .flatpickr-current-month .numInputWrapper {
            display: inline-block !important;
            width: 62px !important;
            vertical-align: middle !important;
            margin-left: 2px !important;
        }
        .flatpickr-current-month input.cur-year {
            font-size: 0.82rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            height: 24px !important;
            line-height: 24px !important;
            padding: 0 2px !important;
            border: 1px solid transparent !important;
            border-radius: 4px !important;
            background: transparent !important;
            margin: 0 !important;
        }
        .flatpickr-current-month input.cur-year:hover,
        .flatpickr-current-month input.cur-year:focus {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0ea5e9 !important;
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
        /* Custom Scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* 3D Styles (Reduced, Clean & Soft Shadows) */
        .card-3d {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 
                0 4px 16px -2px rgba(148, 163, 184, 0.12), 
                0 2px 6px -1px rgba(148, 163, 184, 0.06);
            border: 1px solid rgba(226, 232, 240, 0.7);
            transition: all 0.3s ease;
        }
        .card-3d:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 10px 24px -4px rgba(148, 163, 184, 0.16), 
                0 4px 10px -2px rgba(148, 163, 184, 0.08);
        }
        .card-3d-inner {
            background: #f8fafc;
            border-radius: 12px;
            box-shadow: inset 0 2px 4px 0 rgba(148, 163, 184, 0.08);
            border: 1px solid #e2e8f0;
        }
        .icon-3d {
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(148, 163, 184, 0.12);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .header-3d {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(148, 163, 184, 0.08);
            border-bottom: 1px solid #e2e8f0;
        }
        .btn-3d {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(148, 163, 184, 0.12);
            transition: all 0.2s ease;
            color: #4b5563;
        }
        .btn-3d:hover {
            box-shadow: 0 3px 8px rgba(148, 163, 184, 0.18);
            border-color: #cbd5e1;
            transform: translateY(-1px);
            color: #1e293b;
        }
        .btn-3d:active {
            box-shadow: inset 0 1px 2px rgba(148, 163, 184, 0.12);
            transform: translateY(1px);
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
                <h4 class="font-bold text-sm">Success</h4>
                <p class="text-xs"><?php echo e(session('success')); ?></p>
            </div>
            <button @click="show = false" class="ml-4 text-white/80 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>

        <?php if(session('error') || (isset($errors) && $errors->any())): ?>
        <div x-data="{ show: true }" x-show="show" class="fixed top-20 right-8 z-[100] bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-start gap-3 transition-all max-w-sm">
            <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Error</h4>
                <p class="text-xs mt-1"><?php echo e(session('error') ?? 'There were errors in the submitted form. Please check again.'); ?></p>
                <?php if(isset($errors) && $errors->any()): ?>
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

    <!-- Global Floating Tooltip (Outside of cards/overflow containers) -->
    <div id="system-tooltip" 
         class="fixed pointer-events-none z-[999999] opacity-0 scale-95 transition-all duration-150 ease-out invisible" 
         style="top: 0; left: 0;">
        <div class="relative px-3 py-2 bg-gray-800 text-white text-xs font-medium rounded-lg shadow-xl border border-gray-700/60 max-w-xs leading-relaxed text-left" style="font-size: 11px; line-height: 1.5; color: #f1f5f9;">
            <div id="system-tooltip-text"></div>
            <div id="system-tooltip-arrow" class="absolute w-2 h-2 bg-gray-800 rotate-45 border-r border-b border-gray-700/60"></div>
        </div>
    </div>
    
    <script>
        window.radarBaseUrl = window.radarBaseUrl || window.location.origin;

        if (typeof window.closeModal !== 'function') {
            window.closeModal = function (modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                }
            };
        }

        if (typeof window.openAddCategoryModal !== 'function') {
            window.openAddCategoryModal = function () {
                const modal = document.getElementById('addCategoryModal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            };
        }

        if (typeof window.openEditCategoryModal !== 'function') {
            window.openEditCategoryModal = function (button) {
                const form = document.getElementById('editCategoryForm');
                if (!form) {
                    return;
                }

                form.action = window.radarBaseUrl + '/master-assessment/category/' + button.dataset.id;
                document.getElementById('editCatName').value = button.dataset.name || '';
                document.getElementById('editCategoryModal').classList.remove('hidden');
            };
        }

        if (typeof window.openAddAspectModal !== 'function') {
            window.openAddAspectModal = function (button) {
                const form = document.getElementById('addAspectForm');
                if (!form) {
                    return;
                }

                form.action = window.radarBaseUrl + '/master-assessment/category/' + button.dataset.categoryId + '/aspect';
                document.getElementById('addAspectCategorySubtitle').innerText = 'Kategori: ' + (button.dataset.categoryName || '');
                document.getElementById('addAspectName').value = '';
                document.getElementById('addAspectTargetScore').value = '5.0';
                document.getElementById('addAspectModal').classList.remove('hidden');
            };
        }

        if (typeof window.openEditAspectModal !== 'function') {
            window.openEditAspectModal = function (button) {
                const form = document.getElementById('editAspectForm');
                if (!form) {
                    return;
                }

                form.action = window.radarBaseUrl + '/master-assessment/aspect/' + button.dataset.id;
                document.getElementById('editAspectName').value = button.dataset.name || '';
                document.getElementById('editAspectTargetScore').value = button.dataset.targetScore || '5.0';
                document.getElementById('editAspectModal').classList.remove('hidden');
            };
        }

        if (typeof window.openAddGroupModal !== 'function') {
            window.openAddGroupModal = function (position) {
                const select = document.getElementById('addChartTargetPosition');
                if (select && position) {
                    select.value = position;
                }

                document.getElementById('addChartGroupModal').classList.remove('hidden');
            };
        }

        if (typeof window.openEditGroupModal !== 'function') {
            window.openEditGroupModal = function (button) {
                const form = document.getElementById('editGroupForm');
                if (!form) {
                    return;
                }

                form.action = window.radarBaseUrl + '/master-idp/group/' + button.dataset.id;
                document.getElementById('editGroupTitle').value = button.dataset.title || '';
                document.getElementById('editGroupTargetPosition').value = button.dataset.targetPosition || '';
                document.getElementById('editGroupDescription').value = button.dataset.description || '';
                document.getElementById('editChartGroupModal').classList.remove('hidden');
            };
        }

        if (typeof window.openAddCompetencyModal !== 'function') {
            window.openAddCompetencyModal = function (button) {
                const form = document.getElementById('addCompetencyForm');
                if (!form) {
                    return;
                }

                form.action = window.radarBaseUrl + '/master-idp/group/' + button.dataset.groupId + '/competency';
                document.getElementById('addCompGroupSubtitle').innerText = 'Ke Chart: ' + (button.dataset.groupTitle || '');
                document.getElementById('addCompName').value = '';
                document.getElementById('addCompTargetScore').value = '5.0';
                document.getElementById('addCompetencyModal').classList.remove('hidden');
            };
        }

        if (typeof window.openEditCompetencyModal !== 'function') {
            window.openEditCompetencyModal = function (button) {
                const form = document.getElementById('editCompetencyForm');
                if (!form) {
                    return;
                }

                form.action = window.radarBaseUrl + '/master-idp/competency/' + button.dataset.id;
                document.getElementById('editCompName').value = button.dataset.name || '';
                document.getElementById('editCompTargetScore').value = button.dataset.targetScore || '5.0';
                document.getElementById('editCompetencyModal').classList.remove('hidden');
            };
        }

        function initTomSelect(container) {
            let root = (container instanceof HTMLElement) ? container : document;
            root.querySelectorAll('select').forEach(function(el) {
                if (el.classList.contains('native-select')) return;
                if (el.tomselect) return; // Prevent double initialization
                
                let isSearchable = el.classList.contains('searchable');
                let isDropUp = el.classList.contains('drop-up');
                let useBody = el.getAttribute('data-dropdown-parent') === 'body' || el.classList.contains('dropdown-body');
                let isChartRender = el.getAttribute('data-custom-render') === 'chart';
                
                el.className = isDropUp ? 'drop-up' : '';
                
                let tomConfig = {
                    controlInput: isSearchable ? undefined : null,
                    dropdownParent: useBody ? 'body' : null,
                    searchField: ['text', 'title', 'pos']
                };

                if (isChartRender) {
                    tomConfig.render = {
                        option: function(data, escape) {
                            let aspek = data.aspek || '';
                            let title = data.title || escape(data.text);
                            return `<div class="flex items-center justify-between w-full py-0.5">
                                <span class="font-semibold text-gray-800 text-xs">${escape(title)}</span>
                                ${aspek ? `<span class="text-[10px] font-bold text-sky-600 bg-sky-50 border border-sky-100 px-2 py-0.5 rounded-full shrink-0 ml-3">${escape(aspek)} Aspek</span>` : ''}
                            </div>`;
                        },
                        item: function(data, escape) {
                            let pos = data.pos || '';
                            let title = data.title || escape(data.text);
                            let aspek = data.aspek || '';
                            return `<div class="flex items-center gap-1.5 text-xs">
                                ${pos ? `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800 shrink-0">[${escape(pos)}]</span>` : ''}
                                <span class="font-semibold text-gray-800">${escape(title)}</span>
                                ${aspek ? `<span class="text-gray-400 font-normal">(${escape(aspek)} Aspek)</span>` : ''}
                            </div>`;
                        }
                    };
                }

                let ts = new TomSelect(el, tomConfig);
                
                if (useBody) {
                    ts.positionDropdown = function() {
                        let context = ts.control;
                        let rect = context.getBoundingClientRect();
                        let scrollY = window.scrollY || document.documentElement.scrollTop;
                        let scrollX = window.scrollX || document.documentElement.scrollLeft;
                        
                        let dd = ts.dropdown;
                        let spaceBelow = window.innerHeight - rect.bottom;
                        let spaceAbove = rect.top;
                        
                        let desiredHeight = Math.min(dd.scrollHeight || 220, 240);
                        let shouldDropUp = ts.input.classList.contains('drop-up') || (spaceBelow < desiredHeight + 20 && spaceAbove > spaceBelow);
                        
                        let availableHeight = shouldDropUp ? spaceAbove - 20 : spaceBelow - 20;
                        let contentEl = dd.querySelector('.ts-dropdown-content');
                        if (contentEl) {
                            contentEl.style.maxHeight = Math.max(120, Math.min(220, availableHeight)) + 'px';
                        }
                        
                        let actualHeight = dd.offsetHeight || desiredHeight;
                        let top, left = rect.left + scrollX;
                        
                        if (shouldDropUp) {
                            top = rect.top + scrollY - actualHeight - 4;
                            dd.classList.add('is-dropup');
                            dd.classList.remove('is-dropdown');
                            ts.wrapper.classList.add('drop-up');
                        } else {
                            top = rect.bottom + scrollY + 4;
                            dd.classList.add('is-dropdown');
                            dd.classList.remove('is-dropup');
                            ts.wrapper.classList.remove('drop-up');
                        }
                        
                        dd.style.width = rect.width + 'px';
                        dd.style.top = Math.max(8, top) + 'px';
                        dd.style.left = left + 'px';
                    };
                } else {
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
                }
            });
        }
        window.initTomSelect = initTomSelect;

        function parseFlexibleDate(val) {
            if (!val || typeof val !== 'string') return null;
            val = val.trim();
            if (!val) return null;
            
            // Match YYYY-MM-DD or YYYY/MM/DD or YYYY.MM.DD
            let m = val.match(/^(\d{4})[-/.](\d{1,2})[-/.](\d{1,2})$/);
            if (m) {
                let y = parseInt(m[1], 10), mo = parseInt(m[2], 10) - 1, d = parseInt(m[3], 10);
                let dt = new Date(y, mo, d);
                if (dt.getFullYear() === y && dt.getMonth() === mo && dt.getDate() === d) return dt;
            }
            // Match DD-MM-YYYY or DD/MM/YYYY or DD.MM.YYYY
            m = val.match(/^(\d{1,2})[-/.](\d{1,2})[-/.](\d{4})$/);
            if (m) {
                let d = parseInt(m[1], 10), mo = parseInt(m[2], 10) - 1, y = parseInt(m[3], 10);
                let dt = new Date(y, mo, d);
                if (dt.getFullYear() === y && dt.getMonth() === mo && dt.getDate() === d) return dt;
            }
            let native = new Date(val);
            if (!isNaN(native.getTime())) return native;
            return null;
        }

        function commitFlatpickrValue(instance) {
            if (!instance || !instance.element) return;
            let raw = instance.element.value;
            let val = raw ? raw.trim() : '';
            if (!val) {
                if (instance.selectedDates.length > 0) {
                    instance.clear();
                }
                return;
            }
            let parsed = parseFlexibleDate(val);
            if (parsed) {
                let formatted = instance.formatDate(parsed, "Y-m-d");
                if (instance.element.value !== formatted || !instance.selectedDates[0] || instance.formatDate(instance.selectedDates[0], "Y-m-d") !== formatted) {
                    instance.setDate(parsed, true, "Y-m-d");
                }
            }
        }

        function refreshActiveMonth(instance) {
            if (!instance || !instance.calendarContainer) return;
            let menu = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
            let curMonthEl = instance.calendarContainer.querySelector('.cur-month');
            if (curMonthEl && !curMonthEl.querySelector('.month-chevron')) {
                let chevron = document.createElement('i');
                chevron.className = 'fas fa-chevron-down month-chevron';
                curMonthEl.appendChild(chevron);
            }
            if (menu) {
                let currentM = instance.currentMonth;
                menu.querySelectorAll('.month-menu-item').forEach((item) => {
                    let idx = parseInt(item.getAttribute('data-month-idx'), 10);
                    if (idx === currentM) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
        }

        function setupCustomMonthDropdown(instance) {
            if (!instance || !instance.calendarContainer) return;
            
            let curMonthEl = instance.calendarContainer.querySelector('.cur-month');
            if (curMonthEl && !curMonthEl.querySelector('.month-chevron')) {
                let chevron = document.createElement('i');
                chevron.className = 'fas fa-chevron-down month-chevron';
                curMonthEl.appendChild(chevron);
            }
            
            // Check if menu already exists
            let menu = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
            if (!menu) {
                menu = document.createElement('div');
                menu.className = 'flatpickr-custom-month-menu hidden';
                
                let menuHeader = document.createElement('div');
                menuHeader.className = 'month-menu-header';
                menuHeader.innerHTML = '<span>Pilih Bulan / Select Month</span>';
                menu.appendChild(menuHeader);
                
                let grid = document.createElement('div');
                grid.className = 'month-menu-grid';
                
                let monthNames = [
                    { short: 'Jan', full: 'Januari' },
                    { short: 'Feb', full: 'Februari' },
                    { short: 'Mar', full: 'Maret' },
                    { short: 'Apr', full: 'April' },
                    { short: 'Mei', full: 'Mei' },
                    { short: 'Jun', full: 'Juni' },
                    { short: 'Jul', full: 'Juli' },
                    { short: 'Agu', full: 'Agustus' },
                    { short: 'Sep', full: 'September' },
                    { short: 'Okt', full: 'Oktober' },
                    { short: 'Nov', full: 'November' },
                    { short: 'Des', full: 'Desember' }
                ];
                
                monthNames.forEach((m, idx) => {
                    let btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'month-menu-item';
                    btn.setAttribute('data-month-idx', idx);
                    btn.innerHTML = `<span class="m-short">${m.short}</span><span class="m-full">${m.full}</span>`;
                    
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        instance.changeMonth(idx, false);
                        menu.classList.add('hidden');
                        let curMonth = instance.calendarContainer.querySelector('.cur-month');
                        if (curMonth) curMonth.classList.remove('active');
                        refreshActiveMonth(instance);
                    });
                    
                    grid.appendChild(btn);
                });
                
                menu.appendChild(grid);
                instance.calendarContainer.appendChild(menu);
            }
            
            // Attach single delegated click on calendarContainer for toggling and selecting month menu
            if (!instance.calendarContainer._hasMonthDelegation) {
                instance.calendarContainer._hasMonthDelegation = true;
                instance.calendarContainer.addEventListener('click', function(e) {
                    let monthTrigger = e.target.closest('.cur-month') || (e.target.closest('.flatpickr-current-month') && !e.target.closest('.numInputWrapper, input.cur-year'));
                    if (monthTrigger) {
                        e.preventDefault();
                        e.stopPropagation();
                        let m = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
                        let curMonth = instance.calendarContainer.querySelector('.cur-month');
                        if (m) {
                            let willOpen = m.classList.contains('hidden');
                            if (willOpen) {
                                refreshActiveMonth(instance);
                                m.classList.remove('hidden');
                                if (curMonth) curMonth.classList.add('active');
                            } else {
                                m.classList.add('hidden');
                                if (curMonth) curMonth.classList.remove('active');
                            }
                        }
                        return;
                    }
                    
                    let monthBtn = e.target.closest('.month-menu-item');
                    if (monthBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        let idx = parseInt(monthBtn.getAttribute('data-month-idx'), 10);
                        if (!isNaN(idx)) {
                            instance.changeMonth(idx, false);
                        }
                        let m = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
                        if (m) m.classList.add('hidden');
                        let curMonth = instance.calendarContainer.querySelector('.cur-month');
                        if (curMonth) curMonth.classList.remove('active');
                        refreshActiveMonth(instance);
                        return;
                    }

                    if (!e.target.closest('.flatpickr-custom-month-menu')) {
                        let m = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
                        if (m) m.classList.add('hidden');
                        let curMonth = instance.calendarContainer.querySelector('.cur-month');
                        if (curMonth) curMonth.classList.remove('active');
                    }
                });
            }
            
            refreshActiveMonth(instance);
        }

        function initFlatpickr(container) {
            if (typeof flatpickr === 'undefined') return;
            
            let localeId = (typeof flatpickr !== 'undefined' && flatpickr.l10ns && flatpickr.l10ns.id) ? flatpickr.l10ns.id : 'default';
            
            // Query within container if provided, but also always ensure document modals are checked
            let root = (container instanceof HTMLElement) ? container : document;
            let elements = Array.from(root.querySelectorAll('input[type="date"], input.datepicker, input[data-flatpickr]'));
            
            // Also ensure any uninitialized date inputs in document/modals are processed
            if (root !== document) {
                document.querySelectorAll('input[type="date"], input.datepicker, input[data-flatpickr]').forEach(function(el) {
                    if (!el._flatpickr && !elements.includes(el)) {
                        elements.push(el);
                    }
                });
            }
            
            elements.forEach(function(el) {
                if (el._flatpickr) return;
                
                if (el.type === 'date') {
                    el.type = 'text';
                }
                el.setAttribute('data-flatpickr', 'true');
                if (!el.getAttribute('placeholder')) {
                    el.setAttribute('placeholder', 'YYYY-MM-DD');
                }
                el.setAttribute('autocomplete', 'off');
                
                flatpickr(el, {
                    dateFormat: "Y-m-d",
                    locale: localeId,
                    disableMobile: true,
                    allowInput: true,
                    monthSelectorType: "static",
                    position: "auto",
                    onOpen: function(selectedDates, dateStr, instance) {
                        setupCustomMonthDropdown(instance);
                        refreshActiveMonth(instance);
                        if (instance.calendarContainer) {
                            let m = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
                            if (m) m.classList.add('hidden');
                            let curMonth = instance.calendarContainer.querySelector('.cur-month');
                            if (curMonth) curMonth.classList.remove('active');
                        }
                    },
                    onClose: function(selectedDates, dateStr, instance) {
                        commitFlatpickrValue(instance);
                        if (instance.calendarContainer) {
                            let m = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
                            if (m) m.classList.add('hidden');
                            let curMonth = instance.calendarContainer.querySelector('.cur-month');
                            if (curMonth) curMonth.classList.remove('active');
                        }
                    },
                    onMonthChange: function(selectedDates, dateStr, instance) {
                        refreshActiveMonth(instance);
                        if (instance.calendarContainer) {
                            let m = instance.calendarContainer.querySelector('.flatpickr-custom-month-menu');
                            if (m) m.classList.add('hidden');
                            let curMonth = instance.calendarContainer.querySelector('.cur-month');
                            if (curMonth) curMonth.classList.remove('active');
                        }
                    },
                    onYearChange: function(selectedDates, dateStr, instance) {
                        refreshActiveMonth(instance);
                    },
                    onChange: function(selectedDates, dateStr, instance) {
                        instance.element.dispatchEvent(new Event('input', { bubbles: true }));
                        instance.element.dispatchEvent(new Event('change', { bubbles: true }));
                    },
                    onReady: function(selectedDates, dateStr, instance) {
                        setupCustomMonthDropdown(instance);
                        if (!instance.calendarContainer.querySelector('.flatpickr-custom-footer')) {
                            let footer = document.createElement('div');
                            footer.className = 'flatpickr-custom-footer';
                            footer.innerHTML = `
                                <button type="button" class="flatpickr-btn-clear">Clear</button>
                                <button type="button" class="flatpickr-btn-today">Today</button>
                            `;
                            footer.querySelector('.flatpickr-btn-clear').addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                instance.clear();
                                instance.element.dispatchEvent(new Event('input', { bubbles: true }));
                                instance.element.dispatchEvent(new Event('change', { bubbles: true }));
                                instance.close();
                            });
                            footer.querySelector('.flatpickr-btn-today').addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                instance.setDate(new Date(), true);
                                instance.close();
                            });
                            instance.calendarContainer.appendChild(footer);
                        }
                    }
                });

                // Sync while typing so partial/full typed dates don't get lost
                let syncTimeout = null;
                el.addEventListener('input', function() {
                    clearTimeout(syncTimeout);
                    syncTimeout = setTimeout(function() {
                        if (!el._flatpickr) return;
                        let parsed = parseFlexibleDate(el.value);
                        if (parsed) {
                            el._flatpickr.setDate(parsed, false);
                        }
                    }, 300);
                });

                // On blur, immediately commit any typed date UNLESS interacting inside calendar
                el.addEventListener('blur', function(e) {
                    if (!el._flatpickr) return;
                    if (e.relatedTarget && el._flatpickr.calendarContainer && el._flatpickr.calendarContainer.contains(e.relatedTarget)) {
                        return;
                    }
                    setTimeout(function() {
                        if (el._flatpickr && el._flatpickr.calendarContainer && el._flatpickr.calendarContainer.contains(document.activeElement)) {
                            return;
                        }
                        if (el._flatpickr) {
                            commitFlatpickrValue(el._flatpickr);
                        }
                    }, 50);
                });

                // Prevent accidental form submission on Enter & commit typed date
                el.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.stopPropagation();
                        if (el._flatpickr) {
                            commitFlatpickrValue(el._flatpickr);
                            el._flatpickr.close();
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
            if (window.Alpine) {
                window.Alpine.initTree(e.detail?.target || document.body);
            }
            initTomSelect(e.detail?.target);
            initFlatpickr(e.detail?.target);
        });
        document.addEventListener('htmx:load', () => {
            initFlatpickr();
        });

        // Dynamic fallback for any modal or input clicked/focused
        document.addEventListener('focusin', function(e) {
            let el = e.target;
            if (el && (el.matches('input[type="date"], input.datepicker') || el.hasAttribute('data-flatpickr'))) {
                if (!el._flatpickr) {
                    initFlatpickr(document);
                    if (el._flatpickr) {
                        setTimeout(() => {
                            if (document.activeElement === el) {
                                el._flatpickr.open();
                            }
                        }, 50);
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
        // Global System Tooltip Engine (Floats outside of cards/modals/overflows)
        (function() {
            let tooltip = document.getElementById('system-tooltip');
            let tooltipText = document.getElementById('system-tooltip-text');
            let tooltipArrow = document.getElementById('system-tooltip-arrow');
            let activeTarget = null;

            function showTooltip(el) {
                let text = el.getAttribute('data-tooltip') || el.getAttribute('data-title');
                if (!text && el.hasAttribute('title')) {
                    text = el.getAttribute('title');
                    el.setAttribute('data-original-title', text);
                    el.removeAttribute('title');
                }
                if (!text || !text.trim()) return;

                activeTarget = el;

                // Support newlines with <br>
                if (text.includes('\n')) {
                    tooltipText.innerHTML = text.replace(/\n/g, '<br>');
                } else {
                    tooltipText.textContent = text;
                }

                tooltip.classList.remove('invisible', 'scale-95');
                tooltip.classList.add('opacity-0');

                // Measure
                let targetRect = el.getBoundingClientRect();
                let tipRect = tooltip.getBoundingClientRect();
                let gap = 8;
                let margin = 12;

                let left = targetRect.left + (targetRect.width / 2) - (tipRect.width / 2);
                if (left < margin) left = margin;
                if (left + tipRect.width > window.innerWidth - margin) {
                    left = window.innerWidth - margin - tipRect.width;
                }

                let top;
                let isBelow = false;
                if (targetRect.top - tipRect.height - gap < 10) {
                    top = targetRect.bottom + gap;
                    isBelow = true;
                } else {
                    top = targetRect.top - tipRect.height - gap;
                }

                tooltip.style.left = `${left}px`;
                tooltip.style.top = `${top}px`;

                let arrowX = Math.max(10, Math.min(tipRect.width - 16, (targetRect.left + targetRect.width / 2) - left - 4));
                tooltipArrow.style.left = `${arrowX}px`;

                if (isBelow) {
                    tooltipArrow.style.top = '-4px';
                    tooltipArrow.style.bottom = 'auto';
                    tooltipArrow.style.borderRight = 'none';
                    tooltipArrow.style.borderBottom = 'none';
                    tooltipArrow.style.borderLeft = '1px solid rgba(55, 65, 81, 0.6)';
                    tooltipArrow.style.borderTop = '1px solid rgba(55, 65, 81, 0.6)';
                } else {
                    tooltipArrow.style.top = 'auto';
                    tooltipArrow.style.bottom = '-4px';
                    tooltipArrow.style.borderLeft = 'none';
                    tooltipArrow.style.borderTop = 'none';
                    tooltipArrow.style.borderRight = '1px solid rgba(55, 65, 81, 0.6)';
                    tooltipArrow.style.borderBottom = '1px solid rgba(55, 65, 81, 0.6)';
                }

                requestAnimationFrame(() => {
                    tooltip.classList.remove('opacity-0');
                    tooltip.classList.add('opacity-100', 'scale-100');
                });
            }

            function hideTooltip() {
                if (!activeTarget) return;
                activeTarget = null;
                tooltip.classList.remove('opacity-100', 'scale-100');
                tooltip.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    if (!activeTarget) tooltip.classList.add('invisible');
                }, 150);
            }

            document.addEventListener('mouseover', function(e) {
                let target = e.target.closest('[data-tooltip], [data-title]');
                if (target) {
                    showTooltip(target);
                }
            });

            document.addEventListener('mouseout', function(e) {
                let target = e.target.closest('[data-tooltip], [data-title]');
                if (target) {
                    hideTooltip();
                }
            });

            window.addEventListener('scroll', hideTooltip, true);
        })();
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\radarhc\resources\views\layouts\app.blade.php ENDPATH**/ ?>