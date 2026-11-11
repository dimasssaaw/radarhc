<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Idp;
use App\Models\IdpCompetency;
use App\Models\IdpCompetencyGroup;
use App\Models\IdpCompetencyScore;
use App\Models\IdpPenilain;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class IdpController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_idp', only: ['index', 'show', 'showEmployee']),
            new Middleware('can:tambah_idp', only: ['create', 'store']),
            new Middleware('can:edit_idp', only: ['edit', 'update', 'updateMetrics', 'updateEmployeeMetrics', 'updatePenilaian', 'updateEmployeePenilaian', 'updateGrow', 'updateEmployeeGrow', 'updateInfo', 'updateEmployeeInfo', 'updateJadwal', 'updateEmployeeJadwal', 'updateCompetencyScores', 'updateEmployeeCompetencyScores']),
            new Middleware('can:hapus_idp', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'idps'])->orderBy('nama', 'asc');

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $userKaryawan = auth()->check() ? Karyawan::where('npk', auth()->user()->npk)->first() : null;

        if (! $isAdmin) {
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%")
                    ->orWhereHas('position', function ($qPos) use ($search) {
                        $qPos->where('nama_jabatan', 'like', "%{$search}%");
                    })
                    ->orWhere('readiness', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('nama_departemen', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        return view('idp.index', compact('employees', 'departments'));
    }

    public function create(Request $request)
    {
        $query = Karyawan::with(['department', 'idps'])->orderBy('nama', 'asc');

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $userKaryawan = auth()->check() ? Karyawan::where('npk', auth()->user()->npk)->first() : null;

        if (! $isAdmin) {
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        }
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }
        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        $employees = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Karyawan::where('department_id', $userKaryawan->department_id)->orderBy('nama')->get()
            : Karyawan::orderBy('nama')->get();

        $employee = null;
        $idps = collect();
        $availableChartGroups = collect();
        if ($request->filled('employee_id')) {
            $employee = Karyawan::with(['department', 'position', 'idps'])->find($request->employee_id);
            if ($employee) {
                $idps = $employee->idps()->orderBy('tahun', 'desc')->get();
                $posLower = strtolower(trim((string) ($employee->position?->nama_jabatan ?? '')));
                if ($posLower !== '') {
                    $availableChartGroups = IdpCompetencyGroup::whereRaw('LOWER(target_position) = ?', [$posLower])
                        ->when(str_contains($posLower, 'section head'), fn ($q) => $q->orWhereRaw('LOWER(target_position) = ?', ['department head']))
                        ->orderBy('order')->orderBy('id')->get();
                }
            }
        }

        return view('idp.create', [
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
            'employee' => $employee,
            'idps' => $idps,
            'allChartGroups' => $availableChartGroups,
            'availableChartGroups' => $availableChartGroups,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'status_idp' => 'nullable|in:pending,completed,in_progress',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
            'target' => 'nullable|date',
            'head_managing_change' => 'nullable|numeric',
            'head_empowerment' => 'nullable|numeric',
            'head_fostering_teamwork' => 'nullable|numeric',
            'head_analysis_decision' => 'nullable|numeric',
            'head_planning_controlling' => 'nullable|numeric',
            'head_integrity_ethics' => 'nullable|numeric',
            'head_excellence' => 'nullable|numeric',
            'head_compassion' => 'nullable|numeric',
            'head_humility' => 'nullable|numeric',
            'staff_leads_courageously' => 'nullable|numeric',
            'staff_coach_develop' => 'nullable|numeric',
            'staff_teamwork' => 'nullable|numeric',
            'staff_trouble_shooting' => 'nullable|numeric',
            'staff_customer_satisfaction' => 'nullable|numeric',
            'staff_cross_functional' => 'nullable|numeric',
            'staff_quality_focus' => 'nullable|numeric',
            'staff_continuous_improvement' => 'nullable|numeric',
            'staff_planning_monitoring' => 'nullable|numeric',
            'staff_personal_integrity' => 'nullable|numeric',
            'staff_drive_for_result' => 'nullable|numeric',
            'staff_learning' => 'nullable|numeric',
            'unit_directiveness' => 'nullable|numeric',
            'unit_monitoring' => 'nullable|numeric',
            'unit_teamwork' => 'nullable|numeric',
            'unit_quality_customer' => 'nullable|numeric',
            'unit_continuous_improvement' => 'nullable|numeric',
            'unit_personal_integrity' => 'nullable|numeric',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
            'selected_charts' => 'nullable|array',
            'selected_groups' => 'nullable|array',
        ]);

        $selectedCharts = $request->input('selected_charts') ?? $request->input('selected_groups');
        if (is_array($selectedCharts)) {
            $validated['selected_charts'] = array_map('intval', $selectedCharts);
        }
        unset($validated['selected_groups']);

        $idp = Idp::create($validated);

        $penilaianFields = [
            'head_managing_change', 'head_empowerment', 'head_fostering_teamwork', 'head_analysis_decision', 'head_planning_controlling', 'head_integrity_ethics', 'head_excellence', 'head_compassion', 'head_humility',
            'staff_leads_courageously', 'staff_coach_develop', 'staff_teamwork', 'staff_trouble_shooting', 'staff_customer_satisfaction', 'staff_cross_functional', 'staff_quality_focus', 'staff_continuous_improvement', 'staff_planning_monitoring', 'staff_personal_integrity', 'staff_drive_for_result', 'staff_learning',
            'unit_directiveness', 'unit_monitoring', 'unit_teamwork', 'unit_quality_customer', 'unit_continuous_improvement', 'unit_personal_integrity',
        ];

        $penilaianData = [
            'id_idp' => $idp->id,
            'id_karyawan' => $idp->id_karyawan,
            'id_penilain' => $idp->id, // Bypass missing default value
        ];
        foreach ($penilaianFields as $field) {
            if (array_key_exists($field, $validated)) {
                $penilaianData[$field] = $validated[$field];
            }
        }
        IdpPenilain::create($penilaianData);

        $msg = 'IDP data added successfully.';
        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $targetUrl = $request->filled('redirect_to')
            ? $request->redirect_to
            : route($isAdmin ? 'idp.index' : 'user-idp.index');

        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', $targetUrl);
        }

        return redirect($targetUrl)->with('success', $msg);
    }

    protected function getLegacyMapping(): array
    {
        return [
            'Managing Change' => 'head_managing_change',
            'Empowerment' => 'head_empowerment',
            'Fostering Teamwork' => 'head_fostering_teamwork',
            'Analysis & Decision Making' => 'head_analysis_decision',
            'Planning & Controlling' => 'head_planning_controlling',
            'Integrity & Ethics' => 'head_integrity_ethics',
            'Excellence' => 'head_excellence',
            'Excellence & Commitment' => 'head_excellence',
            'Compassion' => 'head_compassion',
            'Compassion & Respect' => 'head_compassion',
            'Humility' => 'head_humility',
            'Humility & Openness' => 'head_humility',
            'Leads Courageously' => 'staff_leads_courageously',
            'Coach & Develop Others' => 'staff_coach_develop',
            'Teamwork' => 'staff_teamwork',
            'Trouble Shooting' => 'staff_trouble_shooting',
            'Customer Satisfaction' => 'staff_customer_satisfaction',
            'Cross Functional Capability' => 'staff_cross_functional',
            'Quality Focus' => 'staff_quality_focus',
            'Continuous Improvement' => 'staff_continuous_improvement',
            'Planning Monitoring' => 'staff_planning_monitoring',
            'Personal Integrity' => 'staff_personal_integrity',
            'Drive For Result' => 'staff_drive_for_result',
            'Learning' => 'staff_learning',
            'Directiveness' => 'unit_directiveness',
            'Monitoring' => 'unit_monitoring',
            'Quality & Customer Focus' => 'unit_quality_customer',
        ];
    }

    public function show($id)
    {
        $Idp = Idp::with(['karyawan.position', 'penilaian', 'competencyScores'])->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $Idp->karyawan || $Idp->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $jabatan = strtolower(trim((string) ($Idp->karyawan->position?->nama_jabatan ?? '')));

        // Query SEMUA chart group yang tersedia untuk posisi ini
        $allChartGroups = IdpCompetencyGroup::with(['competencies' => function ($q) {
            $q->orderBy('order')->orderBy('id');
        }])
            ->where(function ($q) use ($jabatan) {
                if ($jabatan !== '') {
                    $q->whereRaw('LOWER(target_position) = ?', [$jabatan]);
                    if (str_contains($jabatan, 'section head')) {
                        $q->orWhereRaw('LOWER(target_position) = ?', ['department head']);
                    }
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $savedScores = $Idp->competencyScores->pluck('score', 'idp_competency_id')->toArray();
        $legacyMap = $this->getLegacyMapping();

        // Satukan skor: jika belum ada di idp_competency_scores, coba fallback dari idp_penilain
        $competencyScores = [];
        foreach ($allChartGroups as $group) {
            foreach ($group->competencies as $comp) {
                if (isset($savedScores[$comp->id])) {
                    $competencyScores[$comp->id] = (float) $savedScores[$comp->id];
                } elseif ($Idp->penilaian && isset($legacyMap[$comp->name])) {
                    $legacyCol = $legacyMap[$comp->name];
                    $competencyScores[$comp->id] = isset($Idp->penilaian->$legacyCol) ? (float) $Idp->penilaian->$legacyCol : 0.0;
                } else {
                    $competencyScores[$comp->id] = 0.0;
                }
            }
        }

        // Tentukan grup yang memiliki nilai terisi (> 0) pada IDP ini
        $groupsWithScores = $allChartGroups->filter(function ($grp) use ($competencyScores) {
            foreach ($grp->competencies as $comp) {
                if (! empty($competencyScores[$comp->id]) && (float) $competencyScores[$comp->id] > 0) {
                    return true;
                }
            }

            return false;
        })->pluck('id')->toArray();

        // Filter chart yang ditampilkan:
        // 1. Jika pengguna secara eksplisit memilih chart (selected_charts tidak null)
        // 2. Jika belum pernah memilih: hanya tampilkan chart yang memang ada datanya pada IDP ini.
        //    Bila belum ada data sama sekali (IDP baru), tampilkan chart pertama sebagai default.
        if ($Idp->selected_charts !== null) {
            $selectedIds = (array) $Idp->selected_charts;
            $chartGroups = $allChartGroups->filter(fn ($g) => in_array($g->id, $selectedIds))->values();
        } else {
            if (! empty($groupsWithScores)) {
                $chartGroups = $allChartGroups->filter(fn ($g) => in_array($g->id, $groupsWithScores))->values();
            } else {
                $chartGroups = $allChartGroups->take(1)->values();
            }
        }
        $allChartGroups = $allChartGroups->values();

        return view('idp.show', compact('Idp', 'chartGroups', 'allChartGroups', 'competencyScores', 'groupsWithScores'));
    }

    public function showEmployee($employee_id)
    {
        $employee = Karyawan::with('idps')->findOrFail($employee_id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $idps = $employee->idps()->orderBy('tahun', 'desc')->get();

        return view('idp.employee', compact('employee', 'idps'));
    }

    public function edit(Request $request, $id)
    {
        $Idp = Idp::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $userKaryawan = auth()->check() ? Karyawan::where('npk', auth()->user()->npk)->first() : null;

        if (! $isAdmin) {
            if (! $userKaryawan || ! $Idp->karyawan || $Idp->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $query = Karyawan::with(['department', 'idps'])->orderBy('nama', 'asc');

        if (! $isAdmin) {
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        }
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }
        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        $employees = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Karyawan::where('department_id', $userKaryawan->department_id)->orderBy('nama')->get()
            : Karyawan::orderBy('nama')->get();

        $employee = null;
        $idps = collect();
        $targetEmployeeId = $request->input('employee_id', $Idp->id_karyawan);
        if ($targetEmployeeId) {
            $employee = Karyawan::with(['department', 'position', 'idps'])->find($targetEmployeeId);
            if ($employee) {
                $idps = $employee->idps()->orderBy('tahun', 'desc')->get();
            }
        }

        return view('idp.edit', [
            'Idp' => $Idp,
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
            'employee' => $employee,
            'idps' => $idps,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'status_idp' => 'nullable|in:pending,completed,in_progress',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
            'target' => 'nullable|date',
            'head_managing_change' => 'nullable|numeric',
            'head_empowerment' => 'nullable|numeric',
            'head_fostering_teamwork' => 'nullable|numeric',
            'head_analysis_decision' => 'nullable|numeric',
            'head_planning_controlling' => 'nullable|numeric',
            'head_integrity_ethics' => 'nullable|numeric',
            'head_excellence' => 'nullable|numeric',
            'head_compassion' => 'nullable|numeric',
            'head_humility' => 'nullable|numeric',
            'staff_leads_courageously' => 'nullable|numeric',
            'staff_coach_develop' => 'nullable|numeric',
            'staff_teamwork' => 'nullable|numeric',
            'staff_trouble_shooting' => 'nullable|numeric',
            'staff_customer_satisfaction' => 'nullable|numeric',
            'staff_cross_functional' => 'nullable|numeric',
            'staff_quality_focus' => 'nullable|numeric',
            'staff_continuous_improvement' => 'nullable|numeric',
            'staff_planning_monitoring' => 'nullable|numeric',
            'staff_personal_integrity' => 'nullable|numeric',
            'staff_drive_for_result' => 'nullable|numeric',
            'staff_learning' => 'nullable|numeric',
            'unit_directiveness' => 'nullable|numeric',
            'unit_monitoring' => 'nullable|numeric',
            'unit_teamwork' => 'nullable|numeric',
            'unit_quality_customer' => 'nullable|numeric',
            'unit_continuous_improvement' => 'nullable|numeric',
            'unit_personal_integrity' => 'nullable|numeric',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        $Idp = Idp::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $Idp->karyawan || $Idp->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $Idp->update($validated);

        $msg = 'IDP data updated successfully.';
        $targetUrl = $request->filled('redirect_to')
            ? $request->redirect_to
            : route($isAdmin ? 'idp.index' : 'user-idp.index');

        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', $targetUrl);
        }

        return redirect($targetUrl)->with('success', $msg);
    }

    public function destroy($id)
    {
        $Idp = Idp::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $Idp->karyawan || $Idp->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $Idp->delete();

        $msg = 'IDP data deleted successfully.';
        $targetUrl = request()->filled('redirect_to')
            ? request()->redirect_to
            : route($isAdmin ? 'idp.index' : 'user-idp.index');

        if (request()->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', $targetUrl);
        }

        return redirect($targetUrl)->with('success', $msg);
    }

    public function updateMetrics(Request $request, $id)
    {
        $validated = $request->validate([
            'competency' => 'nullable|in:Rendah,Tinggi',
            'commitment' => 'nullable|in:Rendah,Tinggi',
        ]);

        $Idp = Idp::findOrFail($id);
        $Idp->update($validated);

        return response()->json(['success' => true, 'Idp' => $Idp]);
    }

    public function updateEmployeeMetrics(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'competency' => 'nullable|in:Rendah,Tinggi',
            'commitment' => 'nullable|in:Rendah,Tinggi',
        ]);

        $Idp = Idp::create([
            'id_karyawan' => $employee_id,
            'competency' => $validated['competency'] ?? null,
            'commitment' => $validated['commitment'] ?? null,
        ]);

        return response()->json(['success' => true, 'Idp' => $Idp]);
    }

    public function updatePenilaian(Request $request, $id)
    {
        $Idp = Idp::findOrFail($id);

        $penilaianData = $request->except(['_token', '_method']);

        foreach ($penilaianData as $key => $value) {
            if ($value === '') {
                $penilaianData[$key] = null;
            }
        }

        if ($Idp->penilaian) {
            $Idp->penilaian->update($penilaianData);
        } else {
            $penilaianData['id_idp'] = $Idp->id;
            $penilaianData['id_karyawan'] = $Idp->id_karyawan;
            $penilaianData['id_penilain'] = $Idp->id; // Bypass missing default value
            IdpPenilain::create($penilaianData);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Spider Chart updated successfully.');
    }

    public function updateEmployeePenilaian(Request $request, $employee_id)
    {
        $Idp = Idp::create([
            'id_karyawan' => $employee_id,
        ]);

        $penilaianData = $request->except(['_token', '_method']);

        foreach ($penilaianData as $key => $value) {
            if ($value === '') {
                $penilaianData[$key] = null;
            }
        }

        $penilaianData['id_idp'] = $Idp->id;
        $penilaianData['id_karyawan'] = $Idp->id_karyawan;
        $penilaianData['id_penilain'] = $Idp->id; // Bypass missing default value
        IdpPenilain::create($penilaianData);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('idp.show', $Idp->id)->with('success', 'Spider Chart updated successfully.');
    }

    public function updateCompetencyScores(Request $request, $id)
    {
        $Idp = Idp::findOrFail($id);

        $scores = $request->input('scores', []);
        $legacyMap = $this->getLegacyMapping();
        $legacyUpdateData = [];

        foreach ($scores as $competencyId => $scoreValue) {
            if ($scoreValue !== null && $scoreValue !== '') {
                $floatScore = min(5.0, max(0.0, (float) $scoreValue));

                IdpCompetencyScore::updateOrCreate(
                    [
                        'id_idp' => $Idp->id,
                        'idp_competency_id' => $competencyId,
                    ],
                    [
                        'id_karyawan' => $Idp->id_karyawan,
                        'score' => $floatScore,
                    ]
                );

                $compModel = IdpCompetency::find($competencyId);
                if ($compModel && isset($legacyMap[$compModel->name])) {
                    $legacyCol = $legacyMap[$compModel->name];
                    $legacyUpdateData[$legacyCol] = $floatScore;
                }
            }
        }

        if (! empty($legacyUpdateData)) {
            if ($Idp->penilaian) {
                $Idp->penilaian->update($legacyUpdateData);
            } else {
                $legacyUpdateData['id_idp'] = $Idp->id;
                $legacyUpdateData['id_karyawan'] = $Idp->id_karyawan;
                $legacyUpdateData['id_penilain'] = $Idp->id;
                IdpPenilain::create($legacyUpdateData);
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Penilaian Spider Chart berhasil diperbarui.');
    }

    public function updateEmployeeCompetencyScores(Request $request, $employee_id)
    {
        $Idp = Idp::create([
            'id_karyawan' => $employee_id,
            'tahun' => date('Y'),
            'status_idp' => 'in_progress',
        ]);

        return $this->updateCompetencyScores($request, $Idp->id);
    }

    public function updateGrow(Request $request, $id)
    {
        $validated = $request->validate([
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        $Idp = Idp::findOrFail($id);
        $Idp->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'GROW Model updated successfully.');
    }

    public function updateEmployeeGrow(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        $Idp = Idp::create([
            'id_karyawan' => $employee_id,
            'grow_goals' => $validated['grow_goals'] ?? null,
            'grow_realitas' => $validated['grow_realitas'] ?? null,
            'grow_options' => $validated['grow_options'] ?? null,
            'grow_wrap_up' => $validated['grow_wrap_up'] ?? null,
            'grow_insight' => $validated['grow_insight'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'GROW Model updated successfully.');
    }

    public function updateInfo(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'status_idp' => 'nullable|in:pending,completed,in_progress',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
        ]);
        $Idp = Idp::findOrFail($id);
        $Idp->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'IDP information updated successfully.');
    }

    public function updateEmployeeInfo(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'status_idp' => 'nullable|in:pending,completed,in_progress',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
        ]);
        $validated['id_karyawan'] = $employee_id;
        Idp::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'IDP information updated successfully.');
    }

    public function updateJadwal(Request $request, $id)
    {
        $validated = $request->validate([
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
        ]);
        $Idp = Idp::findOrFail($id);
        $Idp->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Review schedule updated successfully.');
    }

    public function updateEmployeeJadwal(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
        ]);
        $validated['id_karyawan'] = $employee_id;
        Idp::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Review schedule updated successfully.');
    }

    public function updateSelectedCharts(Request $request, $id)
    {
        $idp = Idp::findOrFail($id);
        $selected = $request->input('selected_charts', []);
        $idp->selected_charts = is_array($selected) ? array_map('intval', $selected) : [];
        $idp->save();

        return redirect()->back()->with('success', 'Chart IDP yang dinilai berhasil diperbarui.');
    }
}
