<?php

namespace App\Http\Controllers;

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

        $IDP_LEVEL_COMPETENCY = [];
        $IDP_LEVEL_EMPLOYEES = [];

        foreach ($compMaps as $level => $map) {
            $dbJabatan = $jabatanLookup[$level];

            // Get all idp penilaian for this level, latest first
            $rawRecords = IdpPenilain::whereHas('idp.karyawan', function ($q) use ($dbJabatan) {
                $q->whereHas('position', function ($qPos) use ($dbJabatan) {
                    $qPos->whereIn('nama_jabatan', $dbJabatan);
                });
            })->with('idp.karyawan.department')->orderBy('id', 'desc')->get();

            // Unique latest record per employee
            $penilaianRecords = $rawRecords->unique(function ($item) {
                return $item->idp ? $item->idp->id_karyawan : $item->id;
            });

            $totalEmployees = $penilaianRecords->count();

            // Calculate aggregate competency scores
            $compAverages = [];
            $allScoresSum = 0;
            $itemsCount = count($map);

            foreach ($map as $label => $column) {
                $avg = $totalEmployees > 0 ? $penilaianRecords->avg($column) : 0;
                $compAverages[] = [
                    'label' => $label,
                    'score' => round($avg, 2),
                    'target' => 5.0,
                ];
                $allScoresSum += $avg;
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
            foreach ($penilaianRecords as $record) {
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
                    'average_score' => round($empAvg, 2),
                    'weakest_competency' => $weakestLabel,
                    'weakest_score' => round($weakestVal, 2),
                    'all_weaknesses' => $allWeaknessesStr,
                    'weakness_items' => $weaknessItems,
                ];
            }

            $IDP_LEVEL_EMPLOYEES[$level] = $employeesList;
        }

        // Stagnasi Kaderisasi
        // Stagnasi Kaderisasi
        // Karyawan yang sudah 2 tahun atau lebih di status yang sama meski telah mengikuti program
        $stagnasiWarnings = Karyawan::where('updated_at', '<=', now()->subYears(2))
            ->where(function ($q) {
                $q->has('idps')->orHas('mentorings')->orHas('trainings')->orHas('assessmentReports');
            })->with('department')->get();

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
            'IDP_LEVEL_COMPETENCY',
            'IDP_LEVEL_EMPLOYEES',
            'stagnasiWarnings'
        ));
    }
}
