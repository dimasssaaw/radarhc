<?php

namespace App\Http\Controllers;

use App\Models\Idp;
use App\Models\IdpCompetencyGroup;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (strtolower($request->user()->role ?? '') !== 'admin') {
            return redirect()->route('user.dashboard');
        }

        $area = 'Admin';

        $stats = Karyawan::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN readiness = "R3 ready" THEN 1 ELSE 0 END) as r3_count,
            SUM(CASE WHEN readiness = "R5 ready" THEN 1 ELSE 0 END) as r5_count,
            SUM(CASE WHEN hav = "star" THEN 1 ELSE 0 END) as star_count,
            SUM(CASE WHEN YEAR(tanggal_lahir) BETWEEN 1981 AND 1996 THEN 1 ELSE 0 END) as gen_milenial,
            SUM(CASE WHEN YEAR(tanggal_lahir) BETWEEN 1997 AND 2012 THEN 1 ELSE 0 END) as gen_z,
            SUM(CASE WHEN YEAR(tanggal_lahir) BETWEEN 1965 AND 1980 THEN 1 ELSE 0 END) as gen_x,
            SUM(CASE WHEN hav = "high potential" THEN 1 ELSE 0 END) as hav_high_potential,
            SUM(CASE WHEN hav = "potential" THEN 1 ELSE 0 END) as hav_potential,
            SUM(CASE WHEN hav = "excellent career person" THEN 1 ELSE 0 END) as hav_excellent_career,
            SUM(CASE WHEN hav = "career person" THEN 1 ELSE 0 END) as hav_career_person,
            SUM(CASE WHEN hav = "problem employee" THEN 1 ELSE 0 END) as hav_problem_employee,
            SUM(CASE WHEN hav = "deadwood" THEN 1 ELSE 0 END) as hav_deadwood
        ')->first();

        $totalKaryawan = $stats->total ?? 0;
        $r3Count = $stats->r3_count ?? 0;
        $r5Count = $stats->r5_count ?? 0;
        $starCount = $stats->star_count ?? 0;

        $genMilenial = $stats->gen_milenial ?? 0;
        $genZ = $stats->gen_z ?? 0;
        $genX = $stats->gen_x ?? 0;

        $havStats = [
            'star' => $stats->star_count ?? 0,
            'high_potential' => $stats->hav_high_potential ?? 0,
            'potential' => $stats->hav_potential ?? 0,
            'excellent_career' => $stats->hav_excellent_career ?? 0,
            'career_person' => $stats->hav_career_person ?? 0,
            'problem_employee' => $stats->hav_problem_employee ?? 0,
            'deadwood' => $stats->hav_deadwood ?? 0,
        ];

        // Mappings for competencies with dynamic names & targets from Master IDP
        $masterHead = IdpCompetencyGroup::with(['competencies' => fn ($q) => $q->orderBy('order')->orderBy('id')])
            ->whereIn('target_position', ['department head', 'section head'])
            ->orderBy('order')
            ->orderBy('id')
            ->first();

        $masterStaff = IdpCompetencyGroup::with(['competencies' => fn ($q) => $q->orderBy('order')->orderBy('id')])
            ->where('target_position', 'staff')
            ->orderBy('order')
            ->orderBy('id')
            ->first();

        $masterUnit = IdpCompetencyGroup::with(['competencies' => fn ($q) => $q->orderBy('order')->orderBy('id')])
            ->whereIn('target_position', ['unit head', 'group head'])
            ->orderBy('order')
            ->orderBy('id')
            ->first();

        $defaultHeadCols = [
            'head_managing_change' => ['Managing Change', 5.0],
            'head_empowerment' => ['Empowerment', 5.0],
            'head_fostering_teamwork' => ['Fostering Teamwork', 5.0],
            'head_analysis_decision' => ['Analysis & Decision', 5.0],
            'head_planning_controlling' => ['Planning & Controlling', 5.0],
            'head_integrity_ethics' => ['Integrity & Ethics', 5.0],
            'head_excellence' => ['Excellence', 5.0],
            'head_compassion' => ['Compassion', 5.0],
            'head_humility' => ['Humility', 5.0],
        ];

        $defaultStaffCols = [
            'staff_leads_courageously' => ['Leads Courageously', 5.0],
            'staff_coach_develop' => ['Coach & Develop', 5.0],
            'staff_teamwork' => ['Teamwork', 5.0],
            'staff_trouble_shooting' => ['Trouble Shooting', 5.0],
            'staff_customer_satisfaction' => ['Customer Satisfaction', 5.0],
            'staff_cross_functional' => ['Cross Functional', 5.0],
            'staff_quality_focus' => ['Quality Focus', 5.0],
            'staff_continuous_improvement' => ['Continuous Improvement', 5.0],
            'staff_planning_monitoring' => ['Planning & Monitoring', 5.0],
            'staff_personal_integrity' => ['Personal Integrity', 5.0],
            'staff_drive_for_result' => ['Drive for Result', 5.0],
            'staff_learning' => ['Learning', 5.0],
        ];

        $defaultUnitCols = [
            'unit_directiveness' => ['Directiveness', 5.0],
            'unit_monitoring' => ['Monitoring', 5.0],
            'unit_teamwork' => ['Teamwork', 5.0],
            'unit_quality_customer' => ['Quality Customer', 5.0],
            'unit_continuous_improvement' => ['Continuous Improvement', 5.0],
            'unit_personal_integrity' => ['Personal Integrity', 5.0],
        ];

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

        $buildDynamicCompetencyList = function ($group, array $defaultCols) use ($legacyMap) {
            $items = [];
            if ($group && $group->competencies->isNotEmpty()) {
                foreach ($group->competencies->values() as $idx => $comp) {
                    $defaultColKey = array_keys($defaultCols)[$idx] ?? null;
                    $items[] = [
                        'id' => $comp->id,
                        'label' => $comp->name,
                        'target' => (float) ($comp->target_score ?? 5.0),
                        'legacy_col' => $legacyMap[$comp->name] ?? $defaultColKey,
                    ];
                }
            } else {
                foreach ($defaultCols as $col => $data) {
                    $items[] = [
                        'id' => null,
                        'label' => $data[0],
                        'target' => (float) $data[1],
                        'legacy_col' => $col,
                    ];
                }
            }

            return $items;
        };

        $levelCompetencyLists = [
            'head' => $buildDynamicCompetencyList($masterHead, $defaultHeadCols),
            'staff' => $buildDynamicCompetencyList($masterStaff, $defaultStaffCols),
            'unit_head_group_head' => $buildDynamicCompetencyList($masterUnit, $defaultUnitCols),
        ];

        // Define db jabatan lookup
        $jabatanLookup = [
            'head' => ['head', 'Head', 'department head', 'Department Head', 'section head', 'Section Head'],
            'staff' => ['staff', 'Staff'],
            'unit_head_group_head' => ['unit head', 'Unit Head', 'group head', 'Group Head'],
        ];

        // Fetch all distinct years from IDPs that have penilaian data
        $availableYears = Idp::where(function ($q) {
            $q->whereHas('penilaian')->orWhereHas('competencyScores');
        })
            ->whereNotNull('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Selected year filter: 'latest' (default) or a specific year
        $selectedYear = $request->query('idp_year', 'latest');

        $IDP_LEVEL_COMPETENCY = [];
        $IDP_LEVEL_EMPLOYEES = [];

        foreach ($levelCompetencyLists as $level => $competencyList) {
            $dbJabatan = $jabatanLookup[$level];

            // Get IDP records for employees with this jabatan level
            $query = Idp::whereHas('karyawan.position', function ($qPos) use ($dbJabatan) {
                $qPos->whereIn('nama_jabatan', $dbJabatan);
            })->with([
                'karyawan.department',
                'karyawan.position',
                'penilaian',
                'competencyScores',
            ]);

            // Apply year filter if specific year selected
            if ($selectedYear !== 'latest' && ! empty($selectedYear)) {
                $query->where('tahun', $selectedYear);
            }

            $rawRecords = $query->orderBy('tahun', 'desc')->orderBy('periode', 'desc')->orderBy('id', 'desc')->get();

            // Helper to get score for a competency item from an IDP record
            $getCompScore = function ($idp, $compItem) {
                if ($idp->relationLoaded('competencyScores') && $compItem['id']) {
                    $cs = $idp->competencyScores->firstWhere('idp_competency_id', $compItem['id']);
                    if ($cs && $cs->score !== null && is_numeric($cs->score) && (float) $cs->score > 0) {
                        return (float) $cs->score;
                    }
                }
                if ($idp->penilaian && ! empty($compItem['legacy_col'])) {
                    $col = $compItem['legacy_col'];
                    $val = $idp->penilaian->$col ?? null;
                    if ($val !== null && is_numeric($val) && (float) $val > 0) {
                        return (float) $val;
                    }
                }

                return null;
            };

            // Group by employee and select the best/latest evaluated IDP record
            $employeeRecords = [];
            foreach ($rawRecords as $record) {
                $empId = $record->id_karyawan;
                if (! $empId) {
                    continue;
                }

                // Calculate whether this record has any score
                $hasScore = false;
                foreach ($competencyList as $compItem) {
                    if ($getCompScore($record, $compItem) !== null) {
                        $hasScore = true;
                        break;
                    }
                }

                if (! isset($employeeRecords[$empId])) {
                    $employeeRecords[$empId] = [
                        'record' => $record,
                        'has_score' => $hasScore,
                    ];
                } elseif (! $employeeRecords[$empId]['has_score'] && $hasScore) {
                    $employeeRecords[$empId] = [
                        'record' => $record,
                        'has_score' => $hasScore,
                    ];
                }
            }

            // Filter to only records that have evaluated scores
            $evaluatedEmployeeData = array_filter($employeeRecords, fn ($item) => $item['has_score']);
            $totalEmployees = count($evaluatedEmployeeData);

            // Calculate aggregate competency scores across all evaluated employees
            $compAverages = [];
            $allScoresSum = 0;
            $itemsCount = count($competencyList);

            foreach ($competencyList as $compItem) {
                $label = $compItem['label'];
                $targetVal = $compItem['target'];

                $compScores = [];
                foreach ($evaluatedEmployeeData as $item) {
                    $score = $getCompScore($item['record'], $compItem);
                    if ($score !== null) {
                        $compScores[] = $score;
                    }
                }

                $avg = count($compScores) > 0 ? (array_sum($compScores) / count($compScores)) : 0;
                $compAverages[] = [
                    'label' => $label,
                    'score' => round($avg, 2),
                    'target' => $targetVal,
                ];
                $allScoresSum += $avg;
            }

            $overallAvg = $itemsCount > 0 ? ($allScoresSum / $itemsCount) : 0;

            if ($level === 'unit_head_group_head') {
                $displayTitle = 'Unit Head / Group Head';
            } elseif ($level === 'head') {
                $displayTitle = 'Department / Section Head';
            } else {
                $displayTitle = ucfirst(str_replace('_', ' ', $level));
            }

            $IDP_LEVEL_COMPETENCY[$level] = [
                'title' => $displayTitle,
                'total_employees' => $totalEmployees,
                'overall_average' => round($overallAvg, 2),
                'competencies' => $compAverages,
            ];

            // Calculate per employee details for the modal table
            $employeesList = [];
            foreach ($evaluatedEmployeeData as $item) {
                $record = $item['record'];
                $emp = $record->karyawan;
                if (! $emp) {
                    continue;
                }

                $empScoresArray = [];
                $empSum = 0;
                $validCount = 0;

                foreach ($competencyList as $compItem) {
                    $label = $compItem['label'];
                    $score = $getCompScore($record, $compItem);
                    if ($score !== null && $score > 0) {
                        $empScoresArray[] = ['label' => $label, 'score' => (float) $score];
                        $empSum += (float) $score;
                        $validCount++;
                    }
                }

                if (empty($empScoresArray)) {
                    $weakestLabel = 'Not Evaluated';
                    $weakestVal = 0;
                    $allWeaknessesStr = 'Not Evaluated';
                    $weaknessItems = [];
                } else {
                    usort($empScoresArray, function ($a, $b) {
                        return $a['score'] <=> $b['score'];
                    });

                    // Threshold <= 3.0 (di bawah / butuh pengembangan)
                    $weaknesses = array_filter($empScoresArray, function ($item) {
                        return $item['score'] <= 3.0;
                    });

                    if (empty($weaknesses)) {
                        $weakestLabel = 'None';
                        $weakestVal = 0;
                        $allWeaknessesStr = 'All competencies meet standard (> 3.0)';
                        $weaknessItems = [];
                    } else {
                        $minScore = reset($weaknesses)['score'];
                        $minItems = array_filter($weaknesses, function ($item) use ($minScore) {
                            return $item['score'] == $minScore;
                        });
                        $minLabels = array_column($minItems, 'label');

                        if (count($minLabels) > 2) {
                            $weakestLabel = $minLabels[0].', '.$minLabels[1].' (+'.(count($minLabels) - 2).')';
                        } else {
                            $weakestLabel = implode(', ', $minLabels);
                        }
                        $weakestVal = $minScore;

                        $allWeaknessList = [];
                        foreach ($weaknesses as $w) {
                            $allWeaknessList[] = $w['label'].' ('.round($w['score'], 1).')';
                        }
                        $allWeaknessesStr = implode(', ', $allWeaknessList);

                        $weaknessItems = array_values(array_map(function ($w) {
                            return [
                                'label' => $w['label'],
                                'score' => round($w['score'], 1),
                            ];
                        }, $weaknesses));
                    }
                }

                $empAvg = $validCount > 0 ? ($empSum / $validCount) : 0;

                $employeesList[] = [
                    'id' => $emp->id,
                    'nama' => $emp->nama,
                    'foto' => $emp->foto ? asset('storage/'.$emp->foto) : null,
                    'department' => $emp->department ? $emp->department->nama_departemen : '-',
                    'tahun' => $record->tahun,
                    'periode' => $record->periode,
                    'average_score' => round($empAvg, 2),
                    'weakest_competency' => $weakestLabel,
                    'weakest_score' => round($weakestVal, 2),
                    'all_weaknesses' => $allWeaknessesStr,
                    'weakness_items' => $weaknessItems,
                ];
            }

            $IDP_LEVEL_EMPLOYEES[$level] = $employeesList;
        }

        // Stagnasi Kaderisasi: Penilaian tahunan (setahun sekali), kondisi 2 tahun terakhir tidak ada peningkatan nilai & status readiness tidak ada perubahan, serta kendala pada Training/Mentoring
        $stagnasiWarnings = [];
        $allEmployees = Karyawan::with([
            'department',
            'position',
            'idps' => function ($q) {
                $q->with('penilaian')->orderBy('tahun', 'asc');
            },
            'assessmentReports' => function ($q) {
                $q->orderBy('tahun', 'asc');
            },
            'mentorings' => function ($q) {
                $q->orderBy('tahun', 'desc')->orderBy('periode', 'desc')->orderBy('id', 'desc');
            },
            'trainings',
            'riwayatTrainings',
        ])->get();

        foreach ($allEmployees as $karyawan) {
            $yearlyScores = [];

            // 1. Dari IDP tahunan
            foreach ($karyawan->idps as $idp) {
                if (! empty($idp->tahun) && $idp->penilaian) {
                    $tahun = (int) $idp->tahun;
                    $attrs = $idp->penilaian->getAttributes();
                    $sum = 0;
                    $cnt = 0;
                    foreach ($attrs as $col => $val) {
                        if (! in_array($col, ['id', 'id_idp', 'id_karyawan', 'id_penilain', 'created_at', 'updated_at']) && is_numeric($val) && (float) $val > 0) {
                            $sum += (float) $val;
                            $cnt++;
                        }
                    }
                    if ($cnt > 0) {
                        $yearlyScores[$tahun]['idp'] = round($sum / $cnt, 2);
                    }
                }
            }

            // 2. Dari Assessment Report tahunan
            foreach ($karyawan->assessmentReports as $ar) {
                if (! empty($ar->tahun)) {
                    $tahun = (int) $ar->tahun;
                    $attrs = $ar->toArray();
                    $sum = 0;
                    $cnt = 0;
                    foreach ($attrs as $col => $val) {
                        if (! in_array($col, ['id', 'id_karyawan', 'tahun', 'periode', 'provider', 'created_at', 'updated_at']) && is_numeric($val) && (float) $val > 0) {
                            $sum += (float) $val;
                            $cnt++;
                        }
                    }
                    if ($cnt > 0) {
                        $yearlyScores[$tahun]['ar'] = round($sum / $cnt, 2);
                    }
                }
            }

            // Hitung rata-rata penilaian per tahun
            $yearlyAverages = [];
            foreach ($yearlyScores as $yr => $types) {
                $yearlyAverages[$yr] = round(array_sum($types) / count($types), 2);
            }
            ksort($yearlyAverages);

            // Evaluasi 2 tahun terakhir penilaian
            $years = array_keys($yearlyAverages);
            if (count($years) >= 2) {
                $prevYear = $years[count($years) - 2];
                $latestYear = $years[count($years) - 1];

                $prevScore = $yearlyAverages[$prevYear];
                $latestScore = $yearlyAverages[$latestYear];

                // Kondisi 1: Nilai penilaian 2 tahun terakhir tidak ada peningkatan / stagnan
                $isScoreStagnant = ($latestScore <= $prevScore);

                // Kondisi 2: Status Readiness tidak ada perubahan saat penilaian dilakukan
                $rawReadiness = strtolower(trim((string) $karyawan->readiness));
                $isReadinessStagnant = in_array($rawReadiness, ['not_ready', 'r5 ready', 'r4 ready', 'r3 ready', 'r2 ready', 'r1 ready', '']);

                // Kondisi 3 (Opsi 2): Terdapat kendala pada Program Training ATAU Mentoring
                // 3a. Evaluasi Training
                $riwayats = $karyawan->riwayatTrainings;
                $trainingModel = $karyawan->trainings->first();

                $hasTrainingIssue = false;
                $trainingReason = '';

                if ($riwayats->isNotEmpty()) {
                    $hasRemedial = $riwayats->contains('status_training', 'remedial');
                    $hasIncompleteEva = $riwayats->contains(function ($r) {
                        return $r->eva1_status !== 'completed' || $r->eva2_status !== 'completed' || $r->eva3_status !== 'completed';
                    });

                    if ($hasRemedial) {
                        $hasTrainingIssue = true;
                        $trainingReason = 'Training Remedial';
                    } elseif ($hasIncompleteEva) {
                        $hasTrainingIssue = true;
                        $trainingReason = 'Evaluasi Training Belum Tuntas';
                    }
                }

                // Cek target modul jika ada target
                if ($trainingModel) {
                    $targetMandatory = (int) ($trainingModel->target_mandatory ?? 0);
                    $targetSkill = (int) ($trainingModel->target_skill ?? 0);
                    $targetManagerial = (int) ($trainingModel->target_managerial ?? 0);
                    $totalTarget = $targetMandatory + $targetSkill + $targetManagerial;

                    if ($totalTarget > 0) {
                        $completedCount = $riwayats->filter(function ($r) {
                            return $r->eva1_status === 'completed' && $r->eva2_status === 'completed' && $r->eva3_status === 'completed';
                        })->count();

                        if ($completedCount < $totalTarget) {
                            $hasTrainingIssue = true;
                            if (empty($trainingReason)) {
                                $trainingReason = "Target Training {$completedCount}/{$totalTarget}";
                            }
                        }
                    }
                }

                // 3b. Evaluasi Mentoring
                $hasMentoringIssue = false;
                $mentoringReason = '';

                if ($karyawan->mentorings->isNotEmpty()) {
                    $latestMentoring = $karyawan->mentorings->first(function ($m) {
                        return ! empty($m->competency) || ! empty($m->commitment);
                    }) ?? $karyawan->mentorings->first();

                    $comp = $latestMentoring->competency ?? null;
                    $comm = $latestMentoring->commitment ?? null;
                    $status = $latestMentoring->status_mentoring ?? null;

                    if ($comp === 'Rendah' && $comm === 'Rendah') {
                        $hasMentoringIssue = true;
                        $mentoringReason = 'Mentoring: Rendah-Rendah (Kuadran BL)';
                    } elseif ($comp === 'Rendah') {
                        $hasMentoringIssue = true;
                        $mentoringReason = 'Mentoring: Kompetensi Rendah';
                    } elseif ($comm === 'Rendah') {
                        $hasMentoringIssue = true;
                        $mentoringReason = 'Mentoring: Komitmen Rendah';
                    } elseif ($status !== 'completed') {
                        $hasMentoringIssue = true;
                        $mentoringReason = 'Mentoring Belum Selesai';
                    }
                } else {
                    $hasMentoringIssue = true;
                    $mentoringReason = 'Belum Ada Mentoring';
                }

                $hasDevIssue = ($hasTrainingIssue || $hasMentoringIssue);

                if ($isScoreStagnant && $isReadinessStagnant && $hasDevIssue) {
                    $karyawan->stagnasi_prev_year = $prevYear;
                    $karyawan->stagnasi_latest_year = $latestYear;
                    $karyawan->stagnasi_prev_score = $prevScore;
                    $karyawan->stagnasi_latest_score = $latestScore;

                    $reasons = array_filter([$trainingReason, $mentoringReason]);
                    $karyawan->stagnasi_indicator = ! empty($reasons) ? implode(' • ', $reasons) : 'Perlu Evaluasi Pembinaan';

                    $stagnasiWarnings[] = $karyawan;
                }
            }
        }

        return view('dashboard.index', compact(
            'area',
            'totalKaryawan',
            'r3Count',
            'r5Count',
            'starCount',
            'genMilenial',
            'genZ',
            'genX',
            'havStats',
            'availableYears',
            'selectedYear',
            'IDP_LEVEL_COMPETENCY',
            'IDP_LEVEL_EMPLOYEES',
            'stagnasiWarnings'
        ));
    }
}
