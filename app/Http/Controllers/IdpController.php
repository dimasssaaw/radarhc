<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Idp;
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
            new Middleware('can:edit_idp', only: ['edit', 'update', 'updateMetrics', 'updateEmployeeMetrics', 'updatePenilaian', 'updateEmployeePenilaian', 'updateGrow', 'updateEmployeeGrow', 'updateInfo', 'updateEmployeeInfo', 'updateJadwal', 'updateEmployeeJadwal']),
            new Middleware('can:hapus_idp', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'idps'])->orderBy('nama', 'asc');

        if (auth()->user()->role !== 'admin') {
            $employee = \App\Models\Karyawan::where('npk', auth()->user()->npk)->first();
            if ($employee && $employee->department_id) {
                $query->where('department_id', $employee->department_id);
            }
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

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        return view('idp.index', compact('employees', 'departments'));
    }

    public function create(Request $request)
    {
        $query = Karyawan::with(['department', 'idps'])->orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }
        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        $employees = Karyawan::orderBy('nama')->get();

        return view('idp.create', [
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
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
        ]);

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

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Data Idp berhasil ditambahkan.');
        }
        return redirect()->route('idp.index')->with('success', 'Data Idp berhasil ditambahkan.');
    }

    public function show($id)
    {
        $Idp = Idp::with('karyawan')->findOrFail($id);

        return view('idp.show', compact('Idp'));
    }

    public function showEmployee($employee_id)
    {
        $karyawan = Karyawan::findOrFail($employee_id);
        $Idp = Idp::where('id_karyawan', $employee_id)->latest()->first() ?? new Idp;
        $Idp->karyawan = $karyawan;

        return view('idp.show', compact('Idp'));
    }

    public function edit(Request $request, $id)
    {
        $Idp = Idp::findOrFail($id);

        $query = Karyawan::with(['department', 'idps'])->orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }
        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        $employees = Karyawan::orderBy('nama')->get();

        return view('idp.edit', [
            'Idp' => $Idp,
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
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

        $Idp = Idp::findOrFail($id);
        $Idp->update($validated);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Data Idp berhasil diperbarui.');
        }
        return redirect()->route('idp.index')->with('success', 'Data Idp berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $Idp = Idp::findOrFail($id);
        $Idp->delete();

        if (request()->has('redirect_to')) {
            return redirect(request()->redirect_to)->with('success', 'Data Idp berhasil dihapus.');
        }
        return redirect()->route('idp.index')->with('success', 'Data Idp berhasil dihapus.');
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

        return redirect()->back()->with('success', 'Spider Chart berhasil diperbarui.');
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

        return redirect()->route('idp.show', $Idp->id)->with('success', 'Spider Chart berhasil diperbarui.');
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

        return redirect()->back()->with('success', 'GROW Model berhasil diperbarui.');
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

        return redirect()->back()->with('success', 'GROW Model berhasil diperbarui.');
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

        return redirect()->back()->with('success', 'IDP Information berhasil diperbarui.');
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

        return redirect()->back()->with('success', 'IDP Information berhasil diperbarui.');
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

        return redirect()->back()->with('success', 'Jadwal Review berhasil diperbarui.');
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

        return redirect()->back()->with('success', 'Jadwal Review berhasil diperbarui.');
    }
}
