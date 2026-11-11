<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Talent Dossier & Journey Report - <?php echo e($karyawan->nama); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* =========================================================
           A4 Print & Layout Configuration
           Spacious, Balanced, Non-Cramped 2-Page Executive Dossier
           ========================================================= */
        @page {
            size: A4 portrait;
            margin: 7mm 11mm 7mm 11mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            background-color: #f1f5f9;
            font-size: 9.5px;
            line-height: 1.45;
        }

        /* Screen toolbar */
        .preview-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #0f172a;
            color: #ffffff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .toolbar-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 13px;
        }

        .toolbar-title span.badge {
            background: #0284c7;
            color: #ffffff;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 999px;
            font-weight: 600;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-print {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-print:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }

        /* Printable Page Sheet */
        /* Printable Page Sheet */
        .page-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 16px auto;
            background: #ffffff;
            padding: 8mm 12mm;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
            border-radius: 4px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .page-content {
            display: block;
        }

        /* Print media overrides */
        @media print {
            @page {
                size: A4 portrait;
                margin: 7mm 11mm 7mm 11mm;
            }

            html, body {
                width: 100% !important;
                height: auto !important;
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .preview-toolbar {
                display: none !important;
            }

            .page-sheet {
                display: block !important;
                width: 100% !important;
                min-height: 0 !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                overflow: visible !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .page-content {
                display: block !important;
                flex: none !important;
            }

            .page-footer {
                margin-top: 8px !important;
            }

            #page-2 {
                display: block !important;
                page-break-before: always !important;
                break-before: page !important;
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
        }

        /* =========================================================
           Header Elements
           ========================================================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2.5px solid #0284c7;
            padding-bottom: 6px;
            margin-bottom: 9px;
        }

        .header-title h1 {
            font-size: 13.5px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.2px;
            text-transform: uppercase;
        }

        .header-title p {
            margin: 2px 0 0 0;
            font-size: 8.8px;
            color: #64748b;
            font-weight: 600;
        }

        .badge-confidential {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            font-size: 8px;
            font-weight: 800;
            padding: 2.5px 7px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-block;
        }

        .running-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 8.5px;
            color: #64748b;
            font-weight: 600;
        }

        .running-header .doc-tag {
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* =========================================================
           Section Containers & Typography
           ========================================================= */
        .section-box {
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            padding: 8px 11px;
            margin-bottom: 8px;
            background: #ffffff;
        }

        .section-title {
            font-size: 9.2px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0 0 6px 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .section-title::before {
            content: '';
            display: inline-block;
            width: 3.5px;
            height: 10px;
            background: #0284c7;
            border-radius: 2px;
        }

        /* =========================================================
           1. Profile & Key Talent Metrics
           ========================================================= */
        .profile-grid {
            display: grid;
            grid-template-columns: 60px 1fr 1fr;
            gap: 12px;
            align-items: center;
        }

        .avatar-box {
            width: 58px;
            height: 58px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1.5px solid #86efac;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            font-weight: 800;
            color: #166534;
            box-shadow: 0 2px 5px rgba(22, 101, 52, 0.1);
            overflow: hidden;
        }

        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-col table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-col td {
            padding: 2px 3px;
            font-size: 8.8px;
            vertical-align: middle;
        }

        .info-col td.label {
            color: #64748b;
            width: 70px;
            font-weight: 500;
        }

        .info-col td.val {
            color: #0f172a;
            font-weight: 700;
        }

        .metrics-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 7px;
            margin-top: 7px;
            padding-top: 7px;
            border-top: 1px dashed #e2e8f0;
        }

        .metric-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 5px;
            text-align: center;
        }

        .metric-card .m-label {
            font-size: 7.5px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .metric-card .m-val {
            font-size: 9px;
            font-weight: 800;
            margin-top: 2px;
        }

        .pill-green {
            color: #15803d;
            background: #dcfce7;
            padding: 1.5px 6px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 800;
        }

        .pill-purple {
            color: #6b21a8;
            background: #f3e8ff;
            padding: 1.5px 6px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 800;
        }

        .pill-blue {
            color: #0369a1;
            background: #e0f2fe;
            padding: 1.5px 6px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 800;
        }

        .pill-dark {
            color: #0f172a;
            background: #f1f5f9;
            padding: 1.5px 6px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 800;
            border: 1px solid #cbd5e1;
        }

        /* =========================================================
           2. Assessor Recommendation Highlight Box
           ========================================================= */
        .recom-box {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border: 1px solid #bbf7d0;
            border-left: 4.5px solid #16a34a;
            border-radius: 7px;
            padding: 7px 11px;
            margin-bottom: 8px;
        }

        .recom-box .decision {
            font-size: 9.3px;
            font-weight: 800;
            color: #15803d;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .recom-box .notes {
            font-size: 8.8px;
            color: #334155;
            font-style: italic;
            line-height: 1.45;
        }

        /* =========================================================
           Custom Data Tables
           ========================================================= */
        table.custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }

        table.custom-table th {
            background: #0f172a;
            color: #ffffff;
            font-size: 8.2px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 5px 7px;
            text-align: left;
        }

        table.custom-table td {
            padding: 4.5px 7px;
            font-size: 8.8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        table.custom-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            font-size: 7.8px;
            font-weight: 700;
            padding: 1.5px 5px;
            border-radius: 4px;
            letter-spacing: 0.2px;
        }

        .status-active {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .status-done {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .status-target {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        /* =========================================================
           Two Columns Grid
           ========================================================= */
        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 8px;
        }

        /* Progress Bars */
        .progress-bar-bg {
            background: #f1f5f9;
            border-radius: 999px;
            height: 5.5px;
            overflow: hidden;
            margin-top: 2.5px;
            border: 1px solid #e2e8f0;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 999px;
        }

        /* Radar Chart IDP */
        .radar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            border: 1px solid #f1f5f9;
            border-radius: 6px;
            padding: 3px;
        }

        /* =========================================================
           Page 2 Specific Elements
           ========================================================= */
        /* Certification Cards */
        .cert-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 9px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .cert-item:last-child {
            margin-bottom: 0;
        }

        .cert-icon {
            width: 26px;
            height: 26px;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .cert-info {
            flex: 1;
            min-width: 0;
        }

        .cert-title {
            font-size: 9px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cert-sub {
            font-size: 7.8px;
            color: #64748b;
            margin-top: 1px;
        }

        /* SCARF Profile */
        .scarf-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }

        .scarf-item:last-child {
            margin-bottom: 0;
        }

        .scarf-label {
            width: 82px;
            font-size: 8.2px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .scarf-bar-bg {
            flex: 1;
            background: #f1f5f9;
            height: 6.5px;
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .scarf-bar-fill {
            height: 100%;
            border-radius: 999px;
        }

        .scarf-val {
            width: 28px;
            text-align: right;
            font-size: 8.8px;
            font-weight: 800;
            color: #0f172a;
        }

        /* Split Table (VIA Character & Strength) */
        table.custom-table.table-split th,
        .table-split th {
            padding: 5.5px 7px;
            font-size: 8.5px;
            text-align: center !important;
            font-weight: 700;
            text-transform: uppercase;
            color: #ffffff;
            letter-spacing: 0.3px;
        }

        table.custom-table.table-split td,
        .table-split td {
            padding: 6.5px 8px;
            font-size: 9px;
            text-align: center !important;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
        }

        /* Mentoring Matrix 4-Quadrant (With subtle 3D lighting per quadrant color) */
        .matrix-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            border-radius: 6px;
            overflow: hidden;
            font-size: 8.5px;
            font-weight: 700;
            text-align: center;
        }

        .matrix-cell {
            padding: 16px 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            border-radius: 5px;
            line-height: 1.45;
            font-size: 8.8px;
            transition: all 0.2s ease;
        }

        .matrix-cell.inactive-cell {
            opacity: 0.35;
            filter: saturate(0.85);
        }

        .matrix-cell.active-cell {
            opacity: 1;
            font-weight: 900;
            z-index: 2;
        }

        /* GROW Model */
        .grow-header {
            background: #fbbf24;
            color: #111827;
            font-weight: 800;
            font-size: 9px;
            padding: 5px 8px;
            text-align: center;
            border-radius: 5px;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .grow-row {
            display: flex;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 4px;
            font-size: 8.8px;
            align-items: stretch;
            min-height: 29px;
        }

        .grow-row:last-child {
            margin-bottom: 0;
        }

        .grow-left {
            width: 70px;
            color: #ffffff;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            flex-shrink: 0;
            letter-spacing: 0.3px;
            font-size: 8.5px;
        }

        .grow-right {
            flex: 1;
            padding: 5px 9px;
            display: flex;
            align-items: center;
            color: #1f2937;
            font-weight: 600;
            line-height: 1.4;
        }

        /* Talent Development Focus (Page 2) */
        .dev-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .dev-card {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 6px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .dev-card-title {
            font-size: 9px;
            font-weight: 800;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dev-card-body {
            font-size: 8.5px;
            color: #334155;
            line-height: 1.4;
            font-weight: 500;
        }

        /* Page Footer */
        .page-footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 7.5px;
            color: #94a3b8;
            font-weight: 500;
        }

        .page-footer .page-number {
            font-weight: 700;
            color: #64748b;
        }
    </style>
</head>

<body>

    <!-- Floating Top Toolbar for Screen Browsing -->
    <div class="preview-toolbar">
        <div class="toolbar-title">
            <span>Individual Talent Dossier & Journey Report</span>
            <span class="badge"><?php echo e($karyawan->nama); ?> (<?php echo e($karyawan->npk); ?>)</span>
        </div>
        <div class="toolbar-actions">
            <span style="font-size: 11px; color: #94a3b8;">Format: A4 Portrait • Tata Letak Seimbang 2 Halaman</span>
            <button class="btn-print" onclick="window.print()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print / Save PDF
            </button>
        </div>
    </div>

    <?php
        // 1. Hitung Masa Kerja
        $masaKerja = '-';
        if ($karyawan->masuk_kerja) {
            try {
                $start = \Carbon\Carbon::parse($karyawan->masuk_kerja);
                $diff = $start->diff(\Carbon\Carbon::now());
                $masaKerja = ($diff->y > 0 ? $diff->y . ' Yrs ' : '') . $diff->m . ' Mos';
            } catch (\Exception $e) {
                $masaKerja = $karyawan->masuk_kerja;
            }
        }

        // 2. Assessor Decision
        $activePk = $karyawan->perjalananKarirs->where('status_pencapaian', 'aktif/tercapai')->first() ?? $karyawan->perjalananKarirs->last();

        // 3. Assessment Scores
        $kbAspect = \App\Models\AssessmentAspect::where('code', 'kb_kecerdasan_umum')->first();
        $skAspect = \App\Models\AssessmentAspect::where('code', 'sk_sistematika_kerja')->first();
        $kpLeadAspect = \App\Models\AssessmentAspect::where('code', 'kp_kepemimpinan')->first();
        $kpIntegAspect = \App\Models\AssessmentAspect::where('code', 'kp_integritas')->first();

        $kbLabel = $kbAspect?->name ?? 'General Intelligence';
        $skLabel = $skAspect?->name ?? 'Work Systematics';
        $kpLeadLabel = $kpLeadAspect?->name ?? 'Leadership';
        $kpIntegLabel = $kpIntegAspect?->name ?? 'Integrity';

        $kb = $activeReport ? ($kbAspect ? $activeReport->getAspectScore($kbAspect) : (float)($activeReport->kb_kecerdasan_umum ?? 0)) : 0;
        $sk = $activeReport ? ($skAspect ? $activeReport->getAspectScore($skAspect) : (float)($activeReport->sk_sistematika_kerja ?? 0)) : 0;
        $kp_lead = $activeReport ? ($kpLeadAspect ? $activeReport->getAspectScore($kpLeadAspect) : (float)($activeReport->kp_kepemimpinan ?? 0)) : 0;
        $kp_integ = $activeReport ? ($kpIntegAspect ? $activeReport->getAspectScore($kpIntegAspect) : (float)($activeReport->kp_integritas ?? 0)) : 0;
        $ms_plan = (float) ($activeReport->ms_planning_controlling ?? 0);

        $keyScores = [$kb, $sk, $kp_lead, $kp_integ, $ms_plan];
        $validScores = array_filter($keyScores, fn($v) => $v > 0);
        $avgScore = count($validScores) > 0 ? round(array_sum($validScores) / count($validScores), 1) : 0;

        // 4. Training Realization
        $riwayat = \App\Models\RiwayatTraining::where('id_karyawan', $karyawan->id)->get();
        $c_mandatory = $riwayat->where('kategori', 'mandatory')->filter(fn($r) => $r->eva1_status == 'completed' && $r->eva2_status == 'completed' && $r->eva3_status == 'completed')->count();
        $c_skill = $riwayat->where('kategori', 'skill')->filter(fn($r) => $r->eva1_status == 'completed' && $r->eva2_status == 'completed' && $r->eva3_status == 'completed')->count();
        $c_managerial = $riwayat->where('kategori', 'managerial')->filter(fn($r) => $r->eva1_status == 'completed' && $r->eva2_status == 'completed' && $r->eva3_status == 'completed')->count();

        $training = $trainings->first();
        $target_mandatory = $training->target_mandatory ?? 0;
        $target_skill = $training->target_skill ?? 0;
        $target_managerial = $training->target_managerial ?? 0;

        $achieveMandatory = ($target_mandatory > 0) ? min(100, round(($c_mandatory / $target_mandatory) * 100)) : 0;
        $achieveSkill = ($target_skill > 0) ? min(100, round(($c_skill / $target_skill) * 100)) : 0;
        $achieveManagerial = ($target_managerial > 0) ? min(100, round(($c_managerial / $target_managerial) * 100)) : 0;

        // 5. Mentoring Matrix
        $latestMentoring = $latestMentoring ?? $karyawan->mentorings->sortByDesc('created_at')->first();
        $comp = $latestMentoring->competency ?? null;
        $comm = $latestMentoring->commitment ?? null;

        $activeBox = null;
        if ($comp === 'Rendah' && $comm === 'Tinggi') $activeBox = 'TL';
        if ($comp === 'Rendah' && $comm === 'Rendah') $activeBox = 'TR';
        if ($comp === 'Tinggi' && $comm === 'Tinggi') $activeBox = 'BL';
        if ($comp === 'Tinggi' && $comm === 'Rendah') $activeBox = 'BR';

        $isTL = ($activeBox === 'TL');
        $isTR = ($activeBox === 'TR');
        $isBL = ($activeBox === 'BL');
        $isBR = ($activeBox === 'BR');

        // 6. Readiness Pill Styling
        $readiness = strtolower($karyawan->readiness ?? '');
        $isReady = str_contains($readiness, 'ready') && $readiness !== 'not_ready';
        $readinessText = !empty($karyawan->readiness) ? ($karyawan->readiness === 'not_ready' ? 'Not Ready' : trim(str_ireplace('ready', '', $karyawan->readiness))) : '-';

        // 7. Development Focus Notes
        $activeIdp = $activeIdp ?? $karyawan->idps->sortByDesc('created_at')->first();
        $devNotes = $activeIdp?->development ?: '-';
        $targetNotes = $activeIdp?->target ?: '-';
        $monitoringNotes = $activeIdp?->monitoring ?: '-';
    ?>

    <!-- ===================================================================
         HALAMAN 1: Talent Profile, Career Journey & Performance / Training
         =================================================================== -->
    <div class="page-sheet" id="page-1">
        <div class="page-content">
            <!-- Header Halaman 1 -->
            <div class="header">
                <div class="header-title">
                    <h1>Individual Talent Dossier & Journey Report</h1>
                    <p>Talent Management & Career Development System &bull; PT Radar Solusi HC</p>
                </div>
                <div style="text-align: right;">
                    <span class="badge-confidential">Strictly Confidential</span>
                    <div style="font-size: 8px; color: #94a3b8; margin-top: 3px;">Printed on: <?php echo e(\Carbon\Carbon::now()->format('d M Y')); ?></div>
                </div>
            </div>

            <!-- 1. Profil Karyawan & Key Talent Metrics -->
            <div class="section-box">
                <div class="section-title">1. Employee Profile</div>
                <div class="profile-grid">
                    <div class="avatar-box">
                        <?php if($karyawan->foto): ?>
                            <img src="<?php echo e($karyawan->foto); ?>" alt="<?php echo e($karyawan->nama); ?>">
                        <?php else: ?>
                            <?php echo e(strtoupper(substr($karyawan->nama, 0, 2))); ?>

                        <?php endif; ?>
                    </div>
                    <div class="info-col">
                        <table>
                            <tr>
                                <td class="label">Name</td>
                                <td class="val">: <?php echo e($karyawan->nama); ?></td>
                            </tr>
                            <tr>
                                <td class="label">NPK</td>
                                <td class="val">: <?php echo e($karyawan->npk); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Position</td>
                                <td class="val">: <?php echo e($karyawan->position?->nama_jabatan ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Department</td>
                                <td class="val">: <?php echo e($karyawan->department?->nama_departemen ?? '-'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="info-col">
                        <table>
                            <tr>
                                <td class="label">Grade</td>
                                <td class="val">: <?php echo e($karyawan->grade ? 'Grade ' . $karyawan->grade : '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Education</td>
                                <td class="val">: <?php echo e($karyawan->pendidikan ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Join Date</td>
                                <td class="val">: <?php echo e($karyawan->masuk_kerja ? \Carbon\Carbon::parse($karyawan->masuk_kerja)->format('d F Y') : '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Length of Service</td>
                                <td class="val">: <?php echo e($masaKerja); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="metrics-bar">
                    <div class="metric-card">
                        <div class="m-label">Readiness</div>
                        <div class="m-val">
                            <span class="<?php echo e($isReady ? 'pill-green' : 'pill-dark'); ?>"><?php echo e($readinessText); ?></span>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="m-label">9-Box HAV Matrix</div>
                        <div class="m-val"><span class="pill-purple"><?php echo e($karyawan->hav ?? '-'); ?></span></div>
                    </div>
                    <div class="metric-card">
                        <div class="m-label">IQ Score</div>
                        <div class="m-val">
                            <span class="pill-blue">
                                <?php echo e($karyawan->iq ? $karyawan->iq . ' (' . ($karyawan->iq >= 120 ? 'Superior' : ($karyawan->iq >= 110 ? 'High Avg' : 'Average')) . ')' : '-'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="m-label">DISC Profile</div>
                        <div class="m-val"><span class="pill-dark"><?php echo e($karyawan->disc ?? '-'); ?></span></div>
                    </div>
                </div>
            </div>

            <!-- 2. Keputusan Assessor & Rekomendasi Atasan -->
            <div class="recom-box">
                <div class="decision">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="#16a34a">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Assessor Decision: <?php echo e($activePk?->keputusan ? strtoupper($activePk->keputusan) : 'NO DECISION YET'); ?>

                </div>
                <div class="notes">
                    <?php if($activePk?->catatan_rekomendasi_atasan): ?>
                        "<?php echo e($activePk->catatan_rekomendasi_atasan); ?>"
                    <?php else: ?>
                        <span style="color: #94a3b8; font-style: italic;">No recommendation notes recorded yet.</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 3. Rekam Jejak Karier (Timeline Milestones) -->
            <div class="section-box">
                <div class="section-title">3. Position Track Record & Career Journey</div>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">Year</th>
                            <th style="text-align: center;">Position / Title</th>
                            <th style="text-align: center;">Department</th>
                            <th style="text-align: center;">Milestone Type</th>
                            <th style="width: 95px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $perjalananKarirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="text-align: center; font-weight: 700;"><?php echo e($pk->tahun_karir); ?></td>
                            <td style="text-align: center;">
                                <strong><?php echo e($pk->nama_posisi); ?></strong>
                                <?php if($pk->operational_award): ?>
                                    <span style="font-size: 7.5px; color: #b45309; background: #fef3c7; padding: 1px 4px; border-radius: 3px; margin-left: 4px; display: inline-block;"><?php echo e($pk->operational_award); ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;"><?php echo e($pk->department?->nama_departemen ?? '-'); ?></td>
                            <td style="text-align: center;"><?php echo e($pk->tipe_milestone == 'aktif' ? 'Initial Join' : ($pk->tipe_milestone == 'tetap' ? 'Stay in Role' : ucwords($pk->tipe_milestone))); ?></td>
                            <td style="text-align: center;">
                                <?php if($pk->status_pencapaian == 'aktif/tercapai'): ?>
                                    <span class="status-badge status-active">Current Active</span>
                                <?php elseif($pk->status_pencapaian == 'proyeksi/target'): ?>
                                    <span class="status-badge status-target">Projection</span>
                                <?php else: ?>
                                    <span class="status-badge status-done">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 8px;">No career track record recorded yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 4. Kesiapan Pelatihan (Training Evaluation) - Dipindahkan ke Halaman 1 agar layout terisi proporsional -->
            <div class="section-box">
                <div class="section-title">4. Training Readiness (Training Evaluation)</div>
                <table class="custom-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th>Training Category</th>
                            <th style="text-align: center; width: 15%;">Target</th>
                            <th style="text-align: center; width: 15%;">Completed</th>
                            <th style="text-align: center; width: 18%;">Achievement</th>
                            <th style="text-align: center; width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Mandatory Training</strong></td>
                            <td style="text-align: center;"><?php echo e($target_mandatory); ?> Modules</td>
                            <td style="text-align: center;"><?php echo e($c_mandatory); ?> Modules</td>
                            <td style="text-align: center; font-weight: 800; color: #16a34a;"><?php echo e($achieveMandatory); ?>%</td>
                            <td style="text-align: center;">
                                <span class="status-badge <?php echo e($achieveMandatory >= 100 ? 'status-active' : 'status-target'); ?>">
                                    <?php echo e($achieveMandatory >= 100 ? 'Fulfilled' : 'On Progress'); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Skill & Functional Training</strong></td>
                            <td style="text-align: center;"><?php echo e($target_skill); ?> Modules</td>
                            <td style="text-align: center;"><?php echo e($c_skill); ?> Modules</td>
                            <td style="text-align: center; font-weight: 800; color: #0284c7;"><?php echo e($achieveSkill); ?>%</td>
                            <td style="text-align: center;">
                                <span class="status-badge <?php echo e($achieveSkill >= 100 ? 'status-active' : 'status-target'); ?>">
                                    <?php echo e($achieveSkill >= 100 ? 'Fulfilled' : 'On Progress'); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Managerial & Leadership Training</strong></td>
                            <td style="text-align: center;"><?php echo e($target_managerial); ?> Modules</td>
                            <td style="text-align: center;"><?php echo e($c_managerial); ?> Modules</td>
                            <td style="text-align: center; font-weight: 800; color: #16a34a;"><?php echo e($achieveManagerial); ?>%</td>
                            <td style="text-align: center;">
                                <span class="status-badge <?php echo e($achieveManagerial >= 100 ? 'status-active' : 'status-target'); ?>">
                                    <?php echo e($achieveManagerial >= 100 ? 'Fulfilled' : 'On Progress'); ?>

                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 5 & 6. Skor Assessment & Pemetaan Radar Chart IDP (2 Kolom Berdampingan) -->
            <div class="grid-2col" style="margin-bottom: 0;">
                <!-- Assessment Score Bars -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">5. Assessment Score Summary <?php echo e($activeReport ? '('.($activeReport->tahun ?? '-').($activeReport->periode ? ' P'.$activeReport->periode : '').')' : ''); ?></div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px; padding-bottom: 3px; border-bottom: 1px dashed #e2e8f0;">
                        <span style="font-size: 8.2px; color: #64748b; font-weight: 600;">Average Score:</span>
                        <span style="font-size: 12px; font-weight: 800; color: #0284c7;"><?php echo e(number_format($avgScore, 1)); ?> <span style="font-size: 8.5px; color: #64748b; font-weight: 600;">/ 5.0</span></span>
                    </div>

                    <div style="margin-bottom: 5px;">
                        <div style="display: flex; justify-content: space-between; font-size: 8.2px; font-weight: 600;">
                            <span>1. <?php echo e($kbLabel); ?></span>
                            <span style="color: #0284c7; font-weight: 800;"><?php echo e(number_format($kb, 1)); ?> / 5</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: <?php echo e(min(100, ($kb / 5) * 100)); ?>%; background: #0284c7;"></div>
                        </div>
                    </div>

                    <div style="margin-bottom: 5px;">
                        <div style="display: flex; justify-content: space-between; font-size: 8.2px; font-weight: 600;">
                            <span>2. <?php echo e($skLabel); ?></span>
                            <span style="color: #16a34a; font-weight: 800;"><?php echo e(number_format($sk, 1)); ?> / 5</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: <?php echo e(min(100, ($sk / 5) * 100)); ?>%; background: #16a34a;"></div>
                        </div>
                    </div>

                    <div style="margin-bottom: 5px;">
                        <div style="display: flex; justify-content: space-between; font-size: 8.2px; font-weight: 600;">
                            <span>3. <?php echo e($kpLeadLabel); ?></span>
                            <span style="color: #9333ea; font-weight: 800;"><?php echo e(number_format($kp_lead, 1)); ?> / 5</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: <?php echo e(min(100, ($kp_lead / 5) * 100)); ?>%; background: #9333ea;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 8.2px; font-weight: 600;">
                            <span>4. <?php echo e($kpIntegLabel); ?></span>
                            <span style="color: #ea580c; font-weight: 800;"><?php echo e(number_format($kp_integ, 1)); ?> / 5</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: <?php echo e(min(100, ($kp_integ / 5) * 100)); ?>%; background: #ea580c;"></div>
                        </div>
                    </div>
                </div>

                <!-- Radar Chart IDP -->
                <div class="section-box" style="margin-bottom: 0;">
                    <?php
                        $activeIdp = $activeIdp ?? $karyawan->idps->sortByDesc('created_at')->first();
                        $savedScores = $activeIdp ? $activeIdp->competencyScores->pluck('score', 'idp_competency_id')->toArray() : [];

                        $penilaian = null;
                        if ($activeIdp && $activeIdp->penilaian) {
                            if (is_array($activeIdp->penilaian)) {
                                $penilaian = $activeIdp->penilaian;
                            } elseif (is_object($activeIdp->penilaian)) {
                                $penilaian = method_exists($activeIdp->penilaian, 'toArray') ? $activeIdp->penilaian->toArray() : (array) $activeIdp->penilaian;
                            } else {
                                $penilaian = json_decode($activeIdp->penilaian, true);
                            }
                        }

                        $legacyMap = [
                            'Managing Change' => 'head_managing_change',
                            'Empowerment' => 'head_empowerment',
                            'Fostering Teamwork' => 'head_fostering_teamwork',
                            'Analysis & Decision Making' => 'head_analysis_decision',
                            'Analysis & Decision' => 'head_analysis_decision',
                            'Planning & Controlling' => 'head_planning_controlling',
                            'Integrity & Ethics' => 'head_integrity_ethics',
                            'Excellence' => 'head_excellence',
                            'Compassion' => 'head_compassion',
                            'Humility' => 'head_humility',
                            'Leads Courageously' => 'staff_leads_courageously',
                            'Coach & Develop Others' => 'staff_coach_develop',
                            'Coach & Develop' => 'staff_coach_develop',
                            'Teamwork' => 'staff_teamwork',
                            'Trouble Shooting' => 'staff_trouble_shooting',
                            'Customer Satisfaction' => 'staff_customer_satisfaction',
                            'Cross Functional Capability' => 'staff_cross_functional',
                            'Cross Functional' => 'staff_cross_functional',
                            'Quality Focus' => 'staff_quality_focus',
                            'Continuous Improvement' => 'staff_continuous_improvement',
                            'Planning Monitoring' => 'staff_planning_monitoring',
                            'Planning & Monitoring' => 'staff_planning_monitoring',
                            'Personal Integrity' => 'staff_personal_integrity',
                            'Drive For Result' => 'staff_drive_for_result',
                            'Drive for Result' => 'staff_drive_for_result',
                            'Learning' => 'staff_learning',
                            'Directiveness' => 'unit_directiveness',
                            'Monitoring' => 'unit_monitoring',
                            'Quality & Customer Focus' => 'unit_quality_customer',
                            'Quality Customer' => 'unit_quality_customer',
                        ];

                        $empPos = strtolower(trim((string) ($karyawan->position?->nama_jabatan ?? '')));
                        $allGroupsForPos = \App\Models\IdpCompetencyGroup::with(['competencies' => fn ($q) => $q->orderBy('order')->orderBy('id')])
                            ->where(function ($q) use ($empPos) {
                                if ($empPos !== '') {
                                    $q->whereRaw('LOWER(target_position) = ?', [$empPos]);
                                    if (str_contains($empPos, 'section head')) {
                                        $q->orWhereRaw('LOWER(target_position) = ?', ['department head']);
                                    }
                                    if (str_contains($empPos, 'group head')) {
                                        $q->orWhereRaw('LOWER(target_position) = ?', ['unit head']);
                                    }
                                } else {
                                    $q->whereRaw('1 = 0');
                                }
                            })
                            ->orderBy('order')
                            ->orderBy('id')
                            ->get();

                        $groupsToInclude = collect();
                        if ($activeIdp && $activeIdp->selected_charts !== null) {
                            $selectedIds = (array) $activeIdp->selected_charts;
                            $groupsToInclude = $allGroupsForPos->filter(fn ($g) => in_array($g->id, $selectedIds));
                        } elseif ($activeIdp) {
                            $withScores = $allGroupsForPos->filter(function ($g) use ($savedScores, $penilaian, $legacyMap) {
                                foreach ($g->competencies as $c) {
                                    if (isset($savedScores[$c->id]) && (float) $savedScores[$c->id] > 0) return true;
                                    if ($penilaian && isset($legacyMap[$c->name])) {
                                        $col = $legacyMap[$c->name];
                                        if (isset($penilaian[$col]) && (float) $penilaian[$col] > 0) return true;
                                    }
                                }
                                return false;
                            });
                            $groupsToInclude = $withScores->isNotEmpty() ? $withScores : $allGroupsForPos->take(1);
                        }

                        $chartsList = [];
                        foreach ($groupsToInclude as $grp) {
                            $labels = [];
                            $scores = [];
                            $hasScore = false;
                            $totalScore = 0;
                            $scoreCount = 0;

                            foreach ($grp->competencies as $comp) {
                                $labels[] = $comp->name;
                                $val = 0;
                                if (isset($savedScores[$comp->id])) {
                                    $val = (float) $savedScores[$comp->id];
                                } elseif ($penilaian && isset($legacyMap[$comp->name])) {
                                    $col = $legacyMap[$comp->name];
                                    $val = (float) ($penilaian[$col] ?? 0);
                                }
                                $scores[] = $val;
                                if ($val > 0) {
                                    $hasScore = true;
                                    $totalScore += $val;
                                    $scoreCount++;
                                }
                            }

                            $cleanTitle = trim(preg_replace('/\s*\([^)]*\)\s*$/', '', $grp->title));
                            $chartsList[] = [
                                'id' => $grp->id,
                                'title' => $cleanTitle ?: $grp->title,
                                'labels' => $labels,
                                'scores' => $scores,
                                'avg' => $scoreCount > 0 ? number_format($totalScore / $scoreCount, 1) : '0.0',
                                'hasScore' => $hasScore,
                            ];
                        }

                        $reqChartIndex = request()->query('chart_index');
                        $reqChartId = request()->query('chart_id');
                        $selectedChart = null;

                        if ($reqChartIndex !== null && isset($chartsList[(int) $reqChartIndex])) {
                            $selectedChart = $chartsList[(int) $reqChartIndex];
                        } elseif ($reqChartId !== null) {
                            $selectedChart = collect($chartsList)->firstWhere('id', (int) $reqChartId);
                        }

                        if (! $selectedChart) {
                            $selectedChart = collect($chartsList)->firstWhere('hasScore', true) ?? ($chartsList[0] ?? null);
                        }

                        $radarLabels = $selectedChart['labels'] ?? [];
                        $radarScores = $selectedChart['scores'] ?? [];
                        $hasIdpPenilaian = $selectedChart['hasScore'] ?? false;
                        $selectedChartTitle = $selectedChart['title'] ?? 'IDP Competencies';
                        $selectedChartAvg = $selectedChart['avg'] ?? '0.0';
                        $otherCharts = collect($chartsList)->filter(fn($c) => $c['id'] !== ($selectedChart['id'] ?? null));
                    ?>

                    <div class="section-title">6. IDP: <?php echo e($selectedChartTitle); ?> <?php echo e($activeIdp ? '('.($activeIdp->tahun ?? '-').($activeIdp->periode ? ' P'.$activeIdp->periode : '').')' : ''); ?></div>
                    <div class="radar-container">
                        <?php
                            $N = count($radarLabels);
                            $R = 52;
                            $cx = 120;
                            $cy = 76;

                            $gridOuter = [];
                            $gridMid = [];
                            $dataPoints = [];
                            $axisLines = [];
                            $labelNodes = [];

                            if ($N > 0) {
                                for ($i = 0; $i < $N; $i++) {
                                    $angle = -M_PI/2 + $i * (2 * M_PI / $N);
                                    $gx = round($cx + $R * cos($angle), 1);
                                    $gy = round($cy + $R * sin($angle), 1);
                                    $gridOuter[] = "$gx,$gy";

                                    $mx = round($cx + ($R * 0.6) * cos($angle), 1);
                                    $my = round($cy + ($R * 0.6) * sin($angle), 1);
                                    $gridMid[] = "$mx,$my";

                                    $score = $radarScores[$i] ?? 0;
                                    $rd = $R * (min(5.0, max(0.5, $score)) / 5.0);
                                    $dx = round($cx + $rd * cos($angle), 1);
                                    $dy = round($cy + $rd * sin($angle), 1);
                                    $dataPoints[] = "$dx,$dy";

                                    $axisLines[] = ['x1' => $cx, 'y1' => $cy, 'x2' => $gx, 'y2' => $gy];

                                    $lx = round($cx + ($R + 12) * cos($angle), 1);
                                    $ly = round($cy + ($R + 12) * sin($angle), 1);
                                    $cosVal = cos($angle);
                                    $anchor = ($cosVal > 0.3) ? 'start' : (($cosVal < -0.3) ? 'end' : 'middle');

                                    $labelNodes[] = [
                                        'x' => $lx,
                                        'y' => $ly + 2,
                                        'anchor' => $anchor,
                                        'text' => $radarLabels[$i] . ' (' . number_format($score, 1) . ')'
                                    ];
                                }
                            }
                        ?>
                        <?php if($hasIdpPenilaian && $N > 0): ?>
                            <svg width="235" height="130" viewBox="0 0 250 160" style="overflow: visible;">
                                <polygon points="<?php echo e(implode(' ', $gridOuter)); ?>" fill="none" stroke="#e2e8f0" stroke-width="1.2" />
                                <polygon points="<?php echo e(implode(' ', $gridMid)); ?>" fill="none" stroke="#f1f5f9" stroke-width="0.9" />

                                <?php $__currentLoopData = $axisLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <line x1="<?php echo e($line['x1']); ?>" y1="<?php echo e($line['y1']); ?>" x2="<?php echo e($line['x2']); ?>" y2="<?php echo e($line['y2']); ?>" stroke="#cbd5e1" stroke-width="0.8" stroke-dasharray="2,2" />
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <polygon points="<?php echo e(implode(' ', $dataPoints)); ?>" fill="rgba(2, 132, 199, 0.22)" stroke="#0284c7" stroke-width="2.2" />

                                <?php $__currentLoopData = explode(' ', implode(' ', $dataPoints)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php list($px, $py) = explode(',', $pt); ?>
                                    <circle cx="<?php echo e($px); ?>" cy="<?php echo e($py); ?>" r="2.6" fill="#0284c7" stroke="#ffffff" stroke-width="1" />
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php $__currentLoopData = $labelNodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <text x="<?php echo e($lbl['x']); ?>" y="<?php echo e($lbl['y']); ?>" font-size="7.5" font-weight="700" fill="#334155" text-anchor="<?php echo e($lbl['anchor']); ?>"><?php echo e($lbl['text']); ?></text>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </svg>

                            <div style="width: 100%; display: flex; justify-content: space-between; font-size: 8px; color: #64748b; margin-top: 4px; padding: 0 4px; border-top: 1px dashed #e2e8f0; padding-top: 3px;">
                                <span>Mentor: <strong style="color: #334155;"><?php echo e($activeIdp->mentor ?? '-'); ?></strong></span>
                                <span>Average: <strong style="color: #0284c7;"><?php echo e($selectedChartAvg); ?>/5</strong></span>
                            </div>

                            <?php if($otherCharts->isNotEmpty()): ?>
                            <div style="font-size: 7.2px; color: #94a3b8; text-align: center; margin-top: 2px;">
                                Other IDP Chart: 
                                <?php $__currentLoopData = $otherCharts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <strong><?php echo e($oc['title']); ?></strong> (Avg: <?php echo e($oc['avg']); ?>/5)<?php echo e(!$loop->last ? ', ' : ''); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="text-align: center; color: #94a3b8; font-style: italic; font-size: 8.8px; padding: 42px 10px;">
                                No IDP assessment data recorded yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 7. Sertifikasi Aktif & 8. SCARF Profile (2 Kolom Berdampingan) -->
            <div class="grid-2col" style="margin-top: 6px; margin-bottom: 6px;">
                <!-- Sertifikasi Kompetensi -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">7. Competency Certifications</div>
                    <?php $__empty_1 = true; $__currentLoopData = $karyawan->certifications->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="cert-item">
                        <div class="cert-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        </div>
                        <div class="cert-info">
                            <div class="cert-title"><?php echo e($cert->nama_sertifikasi); ?></div>
                            <div class="cert-sub">Issuer: <?php echo e($cert->penerbit); ?> &bull; Issued: <?php echo e($cert->terbit ? \Carbon\Carbon::parse($cert->terbit)->format('M Y') : '-'); ?></div>
                        </div>
                        <span class="status-badge <?php echo e($cert->status_sertifikat == 'aktif' ? 'status-active' : 'status-done'); ?>">
                            <?php echo e($cert->status_sertifikat == 'aktif' ? 'Active' : 'Expired'); ?>

                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="text-align: center; color: #94a3b8; padding: 10px; font-style: italic;">
                        No certification data recorded yet.
                    </div>
                    <?php endif; ?>
                </div>

                <!-- SCARF Profile -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">8. SCARF Profile</div>
                    <div class="scarf-item">
                        <div class="scarf-label">Status (S)</div>
                        <div class="scarf-bar-bg">
                            <div class="scarf-bar-fill" style="width: <?php echo e($karyawan->scarf->status ?? 0); ?>%; background: #1b7356;"></div>
                        </div>
                        <div class="scarf-val"><?php echo e($karyawan->scarf->status ?? 0); ?>%</div>
                    </div>
                    <div class="scarf-item">
                        <div class="scarf-label">Certainty (C)</div>
                        <div class="scarf-bar-bg">
                            <div class="scarf-bar-fill" style="width: <?php echo e($karyawan->scarf->certainty ?? 0); ?>%; background: #f59e0b;"></div>
                        </div>
                        <div class="scarf-val"><?php echo e($karyawan->scarf->certainty ?? 0); ?>%</div>
                    </div>
                    <div class="scarf-item">
                        <div class="scarf-label">Autonomy (A)</div>
                        <div class="scarf-bar-bg">
                            <div class="scarf-bar-fill" style="width: <?php echo e($karyawan->scarf->autonomy ?? 0); ?>%; background: #0284c7;"></div>
                        </div>
                        <div class="scarf-val"><?php echo e($karyawan->scarf->autonomy ?? 0); ?>%</div>
                    </div>
                    <div class="scarf-item">
                        <div class="scarf-label">Relatedness (R)</div>
                        <div class="scarf-bar-bg">
                            <div class="scarf-bar-fill" style="width: <?php echo e($karyawan->scarf->relatedness ?? 0); ?>%; background: #e11d48;"></div>
                        </div>
                        <div class="scarf-val"><?php echo e($karyawan->scarf->relatedness ?? 0); ?>%</div>
                    </div>
                    <div class="scarf-item">
                        <div class="scarf-label">Fairness (F)</div>
                        <div class="scarf-bar-bg">
                            <div class="scarf-bar-fill" style="width: <?php echo e($karyawan->scarf->fairness ?? 0); ?>%; background: #475569;"></div>
                        </div>
                        <div class="scarf-val"><?php echo e($karyawan->scarf->fairness ?? 0); ?>%</div>
                    </div>
                </div>
            </div>

            <!-- 9. Karakter & Kekuatan (Via Character & Strength / Weakness - 2 Kolom Berdampingan) -->
            <div class="grid-2col" style="margin-bottom: 0;">
                <!-- Via Character -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">9. Via Character</div>
                    <table class="custom-table table-split" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="background: #0c7c85; width: 50%; text-align: center;">Overuse Strength</th>
                                <th style="background: #9e2a2b; width: 50%; text-align: center;">Underuse Strength</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $karyawan->viaCharacters->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $via): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="background: #f0fdfa; color: #134e4a; text-align: center;"><?php echo e($via->overuse_strength); ?></td>
                                <td style="background: #fff1f2; color: #881337; text-align: center;"><?php echo e($via->underuse_strength); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="2" style="background: #f8fafc; color: #94a3b8; font-style: italic; text-align: center;">No Via Character data recorded yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Strength & Weakness -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">10. Strength & Weakness</div>
                    <table class="custom-table table-split" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="background: #2563eb; width: 50%; text-align: center;">Strength</th>
                                <th style="background: #b45309; width: 50%; text-align: center;">Weakness</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $karyawan->strengths->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="background: #eff6ff; color: #1e3a8a; text-align: center;"><?php echo e($sw->strength); ?></td>
                                <td style="background: #fffbeb; color: #78350f; text-align: center;"><?php echo e($sw->weakness); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="2" style="background: #f8fafc; color: #94a3b8; font-style: italic; text-align: center;">No Strength & Weakness data recorded yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Page 1 Footer -->
        <div class="page-footer">
            <div>Official Talent Management Document &bull; PT Radar Solusi HC</div>
            <div class="page-number">Page 1 of 2</div>
        </div>
    </div>

    <!-- ===================================================================
         HALAMAN 2: Behavioral Insights, Mentoring Matrix & Development Plan
         =================================================================== -->
    <div class="page-sheet" id="page-2">
        <div class="page-content">
            <!-- Running Header Halaman 2 -->
            <div class="running-header">
                <div>
                    <span class="doc-tag">TALENT DOSSIER</span> &bull; <?php echo e($karyawan->nama); ?> (NPK: <?php echo e($karyawan->npk); ?>) &bull; <?php echo e($karyawan->position?->nama_jabatan ?? '-'); ?>

                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="badge-confidential" style="padding: 2px 6px; font-size: 7.5px;">Strictly Confidential</span>
                </div>
            </div>

            <!-- 11. Mentoring Matrix & GROW Model (2 Kolom Berdampingan) -->
            <div class="grid-2col" style="margin-bottom: 8px;">
                <!-- Matrix Mentoring & Coaching -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">11. Mentoring & Coaching Matrix <?php echo e($latestMentoring ? '('.($latestMentoring->tahun ?? '-').($latestMentoring->periode ? ' P'.$latestMentoring->periode : '').')' : ''); ?></div>
                    <div class="matrix-grid">
                        <!-- TL: Yellow / Amber -->
                        <div class="matrix-cell <?php echo e($isTL ? 'active-cell' : 'inactive-cell'); ?>"
                             style="<?php echo e($isTL 
                                ? 'background: linear-gradient(135deg, #fde065 0%, #facd42 60%, #f3c332 100%); box-shadow: 2px 2px 8px rgba(0,0,0,0.12), inset 0 1.5px 2px rgba(255,255,255,0.6), inset 0 -1.5px 2px rgba(0,0,0,0.06); color: #7c5c00; border: 1.5px solid #eab308;' 
                                : 'background-color: #facd42; color: #8a6800;'); ?>">
                            <div><?php echo e($isTL ? '✓ ' : ''); ?>DIRECTING &bull; MODERATE</div>
                            <div><?php echo e($isTL ? '✓ ' : ''); ?>MENTORING &bull; MODERATE</div>
                            <div><?php echo e($isTL ? '✓ ' : ''); ?>COACHING &bull; MODERATE</div>
                        </div>

                        <!-- TR: Blue -->
                        <div class="matrix-cell <?php echo e($isTR ? 'active-cell' : 'inactive-cell'); ?>"
                             style="<?php echo e($isTR 
                                ? 'background: linear-gradient(135deg, #5390f7 0%, #4485f5 60%, #397ceb 100%); box-shadow: -2px 2px 8px rgba(0,0,0,0.15), inset 0 1.5px 2px rgba(255,255,255,0.4), inset 0 -1.5px 2px rgba(0,0,0,0.08); color: #ffffff; border: 1.5px solid #2563eb;' 
                                : 'background-color: #4485f5; color: #ffffff;'); ?>">
                            <div><?php echo e($isTR ? '✓ ' : ''); ?>DIRECTING &bull; LOW</div>
                            <div><?php echo e($isTR ? '✓ ' : ''); ?>MENTORING &bull; LOW</div>
                            <div><?php echo e($isTR ? '✓ ' : ''); ?>COACHING &bull; LOW</div>
                        </div>

                        <!-- BL: Green -->
                        <div class="matrix-cell <?php echo e($isBL ? 'active-cell' : 'inactive-cell'); ?>"
                             style="<?php echo e($isBL 
                                ? 'background: linear-gradient(135deg, #87d38e 0%, #78c87f 60%, #6abd72 100%); box-shadow: 2px -2px 8px rgba(0,0,0,0.12), inset 0 1.5px 2px rgba(255,255,255,0.5), inset 0 -1.5px 2px rgba(0,0,0,0.06); color: #144918; border: 1.5px solid #16a34a;' 
                                : 'background-color: #78c87f; color: #144918;'); ?>">
                            <div><?php echo e($isBL ? '✓ ' : ''); ?>DIRECTING &bull; HIGH</div>
                            <div><?php echo e($isBL ? '✓ ' : ''); ?>MENTORING &bull; HIGH</div>
                            <div><?php echo e($isBL ? '✓ ' : ''); ?>COACHING &bull; HIGH</div>
                        </div>

                        <!-- BR: Red -->
                        <div class="matrix-cell <?php echo e($isBR ? 'active-cell' : 'inactive-cell'); ?>"
                             style="<?php echo e($isBR 
                                ? 'background: linear-gradient(135deg, #f75d5e 0%, #f05051 60%, #e84546 100%); box-shadow: -2px -2px 8px rgba(0,0,0,0.15), inset 0 1.5px 2px rgba(255,255,255,0.4), inset 0 -1.5px 2px rgba(0,0,0,0.08); color: #ffffff; border: 1.5px solid #dc2626;' 
                                : 'background-color: #f05051; color: #ffffff;'); ?>">
                            <div><?php echo e($isBR ? '✓ ' : ''); ?>DIRECTING &bull; MODERATE</div>
                            <div><?php echo e($isBR ? '✓ ' : ''); ?>MENTORING &bull; HIGH</div>
                            <div><?php echo e($isBR ? '✓ ' : ''); ?>COACHING &bull; MODERATE</div>
                        </div>
                    </div>
                </div>

                <!-- GROW Model -->
                <div class="section-box" style="margin-bottom: 0;">
                    <div class="section-title">12. GROW Model <?php echo e($latestMentoring ? '('.($latestMentoring->tahun ?? '-').($latestMentoring->periode ? ' P'.$latestMentoring->periode : '').')' : ''); ?></div>
                    <div class="grow-header">GROW MODEL ACTION PLAN</div>

                    <div class="grow-row">
                        <div class="grow-left" style="background: #059669;">GOALS</div>
                        <div class="grow-right" style="background: #ecfdf5;"><?php echo e($latestMentoring?->grow_goals ?: '-'); ?></div>
                    </div>

                    <div class="grow-row">
                        <div class="grow-left" style="background: #0284c7;">REALITY</div>
                        <div class="grow-right" style="background: #f0f9ff;"><?php echo e($latestMentoring?->grow_realitas ?: '-'); ?></div>
                    </div>

                    <div class="grow-row">
                        <div class="grow-left" style="background: #7c3aed;">OPTIONS</div>
                        <div class="grow-right" style="background: #f5f3ff;"><?php echo e($latestMentoring?->grow_options ?: '-'); ?></div>
                    </div>

                    <div class="grow-row">
                        <div class="grow-left" style="background: #e11d48;">WRAP UP</div>
                        <div class="grow-right" style="background: #fff1f2;"><?php echo e($latestMentoring?->grow_wrap_up ?: '-'); ?></div>
                    </div>

                    <div class="grow-row">
                        <div class="grow-left" style="background: #d97706;">INSIGHT</div>
                        <div class="grow-right" style="background: #fffbeb;"><?php echo e($latestMentoring?->grow_insight ?: '-'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page 2 Footer -->
        <div class="page-footer">
            <div>Official Talent Management Document &bull; PT Radar Solusi HC</div>
            <div class="page-number">Page 2 of 2</div>
        </div>
    </div>

</body>

</html>
<?php /**PATH C:\laragon\www\radarhc\resources\views\journey\pdf.blade.php ENDPATH**/ ?>