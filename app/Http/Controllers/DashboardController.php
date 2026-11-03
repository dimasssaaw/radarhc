<?php

namespace App\Http\Controllers;

use App\Models\Idp;
use App\Models\IdpPenilain;
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

        // Mappings for competencies
        $compMaps = [
            'head' => [
                'Managing Change' => 'head_managing_change',
                'Empowerment' => 'head_empowerment',
                'Fostering Teamwork' => 'head_fostering_teamwork',
                'Analysis & Decision' => 'head_analysis_decision',
                'Planning & Controlling' => 'head_planning_controlling',
                'Integrity & Ethics' => 'head_integrity_ethics',
                'Excellence' => 'head_excellence',
                'Compassion' => 'head_compassion',
                'Humility' => 'head_humility',
            ],
            'staff' => [
                'Leads Courageously' => 'staff_leads_courageously',
                'Coach & Develop' => 'staff_coach_develop',
                'Teamwork' => 'staff_teamwork',
                'Trouble Shooting' => 'staff_trouble_shooting',
                'Customer Satisfaction' => 'staff_customer_satisfaction',
                'Cross Functional' => 'staff_cross_functional',
                'Quality Focus' => 'staff_quality_focus',
                'Continuous Improvement' => 'staff_continuous_improvement',
                'Planning & Monitoring' => 'staff_planning_monitoring',
                'Personal Integrity' => 'staff_personal_integrity',
                'Drive for Result' => 'staff_drive_for_result',
                'Learning' => 'staff_learning',
            ],
            'unit_head_group_head' => [
                'Directiveness' => 'unit_directiveness',
                'Monitoring' => 'unit_monitoring',
                'Teamwork' => 'unit_teamwork',
                'Quality Customer' => 'unit_quality_customer',
                'Continuous Improvement' => 'unit_continuous_improvement',
                'Personal Integrity' => 'unit_personal_integrity',
            ],
        ];

        // Define db jabatan lookup
        $jabatanLookup = [
            'head' => ['head', 'Head', 'department head', 'Department Head', 'section head', 'Section Head'],
            'staff' => ['staff', 'Staff'],
            'unit_head_group_head' => ['unit head', 'Unit Head', 'group head', 'Group Head'],
        ];

        // Fetch all distinct years from IDPs that have penilaian data
        $availableYears = Idp::whereHas('penilaian')
            ->whereNotNull('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Selected year filter: 'latest' (default) or a specific year
        $selectedYear = $request->query('idp_year', 'latest');

        $IDP_LEVEL_COMPETENCY = [];
        $IDP_LEVEL_EMPLOYEES = [];

        foreach ($compMaps as $level => $map) {
            $dbJabatan = $jabatanLookup[$level];

            // Get idp penilaian for this level, latest first
            $query = IdpPenilain::whereHas('idp.karyawan', function ($q) use ($dbJabatan) {
                $q->whereHas('position', function ($qPos) use ($dbJabatan) {
                    $qPos->whereIn('nama_jabatan', $dbJabatan);
                });
            })->with(['idp.karyawan.department', 'idp.karyawan.position']);

            // Apply year filter if specific year selected
            if ($selectedYear !== 'latest' && ! empty($selectedYear)) {
                $query->whereHas('idp', function ($q) use ($selectedYear) {
                    $q->where('tahun', $selectedYear);
                });
            }

            $rawRecords = $query->orderBy('id', 'desc')->get();

            // Prioritize records that actually have filled evaluation scores over empty ones
            $sortedRecords = $rawRecords->sortByDesc(function ($item) use ($map) {
                $hasScore = false;
                foreach ($map as $col) {
                    if ($item->$col !== null && $item->$col !== '') {
                        $hasScore = true;
                        break;
                    }
                }

                return ($hasScore ? 1000000 : 0) + $item->id;
            });

            // Unique record per employee
            $penilaianRecords = $sortedRecords->unique(function ($item) {
                return $item->idp ? $item->idp->id_karyawan : $item->id;
            });

            // Only consider records that have at least one score evaluated
            $evaluatedRecords = $penilaianRecords->filter(function ($item) use ($map) {
                foreach ($map as $col) {
                    if ($item->$col !== null && $item->$col !== '') {
                        return true;
                    }
                }

                return false;
            });

            $totalEmployees = $evaluatedRecords->count();

            // Calculate aggregate competency scores
            $compAverages = [];
            $allScoresSum = 0;
            $itemsCount = count($map);

            foreach ($map as $label => $column) {
                $avg = $totalEmployees > 0 ? $evaluatedRecords->avg($column) : 0;
                $compAverages[] = [
                    'label' => $label,
                    'score' => round($avg ?? 0, 2),
                    'target' => 5.0,
                ];
                $allScoresSum += ($avg ?? 0);
            }

            $overallAvg = $itemsCount > 0 ? ($allScoresSum / $itemsCount) : 0;

            if ($level === 'unit_head_group_head') {
                $displayTitle = 'Unit Head / Group Head';
            } elseif ($level === 'head') {
                $displayTitle = 'Department Head';
            } else {
                $displayTitle = ucfirst(str_replace('_', ' ', $level));
            }

            $IDP_LEVEL_COMPETENCY[$level] = [
                'title' => $displayTitle,
                'total_employees' => $totalEmployees,
                'overall_average' => round($overallAvg, 2),
                'competencies' => $compAverages,
            ];

            // Calculate per employee details
            $employeesList = [];
            foreach ($evaluatedRecords as $record) {
                $emp = $record->idp ? $record->idp->karyawan : null;
                if (! $emp) {
                    continue;
                }

                $empScores = [];
                $empSum = 0;
                $empScoresArray = [];
                $weaknessItems = [];

                foreach ($map as $label => $column) {
                    $val = $record->$column;
                    $numVal = $val ?? 0;

                    $empScores[$label] = $numVal;
                    $empSum += $numVal;

                    if ($val !== null && $val > 0) {
                        $empScoresArray[] = ['label' => $label, 'score' => (float) $val];
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

                $empAvg = $itemsCount > 0 ? ($empSum / $itemsCount) : 0;

                $employeesList[] = [
                    'id' => $emp->id,
                    'nama' => $emp->nama,
                    'foto' => $emp->foto ? asset('storage/'.$emp->foto) : null,
                    'department' => $emp->department ? $emp->department->nama_departemen : '-',
                    'tahun' => $record->idp?->tahun,
                    'periode' => $record->idp?->periode,
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
