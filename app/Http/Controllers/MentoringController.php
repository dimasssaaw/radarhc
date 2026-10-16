<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\Mentoring;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MentoringController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_mentoring', only: ['index', 'show', 'showEmployee']),
            new Middleware('can:tambah_mentoring', only: ['create', 'store']),
            new Middleware('can:edit_mentoring', only: ['edit', 'update', 'updateMetrics', 'updateEmployeeMetrics', 'updateGrow', 'updateEmployeeGrow', 'updateInfo', 'updateEmployeeInfo', 'updateProgress', 'updateEmployeeProgress']),
            new Middleware('can:hapus_mentoring', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'mentorings'])->orderBy('nama', 'asc');

        if (auth()->user()->role !== 'admin') {
            $employee = Karyawan::where('npk', auth()->user()->npk)->first();
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

        return view('mentoring.index', compact('employees', 'departments'));
    }

    public function create(Request $request)
    {
        $query = Karyawan::with(['department', 'mentorings'])->orderBy('nama', 'asc');

        if (auth()->check() && strtolower(auth()->user()->role) !== 'admin') {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            }
        }

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

        return view('mentoring.create', [
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
            'project' => 'nullable|string',
            'status_mentoring' => 'nullable|in:in_progress,completed',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        Mentoring::create($validated);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Data Mentoring berhasil ditambahkan.');
        }

        return redirect()->route('mentoring.index')->with('success', 'Data Mentoring berhasil ditambahkan.');
    }

    public function show($id)
    {
        $mentoring = Mentoring::with('karyawan')->findOrFail($id);

        return view('mentoring.show', compact('mentoring'));
    }

    public function showEmployee($employee_id)
    {
        $karyawan = Karyawan::findOrFail($employee_id);
        $mentoring = Mentoring::where('id_karyawan', $employee_id)->latest()->first() ?? new Mentoring;
        $mentoring->karyawan = $karyawan;

        return view('mentoring.show', compact('mentoring'));
    }

    public function edit(Request $request, $id)
    {
        $mentoring = Mentoring::findOrFail($id);

        $query = Karyawan::with(['department', 'mentorings'])->orderBy('nama', 'asc');

        if (auth()->check() && strtolower(auth()->user()->role) !== 'admin') {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            }
        }

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

        return view('mentoring.edit', [
            'mentoring' => $mentoring,
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
            'project' => 'nullable|string',
            'status_mentoring' => 'nullable|in:pending,completed,cancelled',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->update($validated);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Data Mentoring berhasil diperbarui.');
        }

        return redirect()->route('mentoring.index')->with('success', 'Data Mentoring berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mentoring = Mentoring::findOrFail($id);
        $mentoring->delete();

        if (request()->has('redirect_to')) {
            return redirect(request()->redirect_to)->with('success', 'Data Mentoring berhasil dihapus.');
        }

        return redirect()->route('mentoring.index')->with('success', 'Data Mentoring berhasil dihapus.');
    }

    public function updateMetrics(Request $request, $id)
    {
        $validated = $request->validate([
            'competency' => 'nullable|in:Rendah,Tinggi',
            'commitment' => 'nullable|in:Rendah,Tinggi',
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->update($validated);

        return response()->json(['success' => true, 'mentoring' => $mentoring]);
    }

    public function updateEmployeeMetrics(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'competency' => 'nullable|in:Rendah,Tinggi',
            'commitment' => 'nullable|in:Rendah,Tinggi',
        ]);

        $mentoring = Mentoring::create([
            'id_karyawan' => $employee_id,
            'competency' => $validated['competency'] ?? null,
            'commitment' => $validated['commitment'] ?? null,
        ]);

        return response()->json(['success' => true, 'mentoring' => $mentoring]);
    }

    public function updateGrow(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'status_mentoring' => 'nullable|in:pending,completed,cancelled',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->update($validated);

        return redirect()->back()->with('success', 'GROW Model berhasil diperbarui.');
    }

    public function updateEmployeeGrow(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'status_mentoring' => 'nullable|in:pending,completed,cancelled',
            'mentor' => 'nullable|string',
            'development' => 'nullable|string',
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
            'grow_goals' => 'nullable|string',
            'grow_realitas' => 'nullable|string',
            'grow_options' => 'nullable|string',
            'grow_wrap_up' => 'nullable|string',
            'grow_insight' => 'nullable|string',
        ]);

        $mentoring = Mentoring::create(array_merge([
            'id_karyawan' => $employee_id,
        ], $validated));

        return redirect()->back()->with('success', 'GROW Model berhasil diperbarui.');
    }

    public function updateInfo(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'project' => 'nullable|string',
            'status_mentoring' => 'nullable|in:pending,completed,cancelled',
            'mentor' => 'nullable|string',
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->update($validated);

        return redirect()->back()->with('success', 'Info Mentoring berhasil diperbarui.');
    }

    public function updateEmployeeInfo(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|string',
            'periode' => 'nullable|string',
            'project' => 'nullable|string',
            'status_mentoring' => 'nullable|in:pending,completed,cancelled',
            'mentor' => 'nullable|string',
        ]);

        $mentoring = Mentoring::create(array_merge([
            'id_karyawan' => $employee_id,
        ], $validated));

        return redirect()->back()->with('success', 'Info Mentoring berhasil diperbarui.');
    }

    public function updateProgress(Request $request, $id)
    {
        $validated = $request->validate([
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->update($validated);

        return redirect()->back()->with('success', 'Progress Tracking berhasil diperbarui.');
    }

    public function updateEmployeeProgress(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'target' => 'nullable|date',
            'monitoring' => 'nullable|date',
            'final' => 'nullable|date',
        ]);

        $mentoring = Mentoring::create(array_merge([
            'id_karyawan' => $employee_id,
        ], $validated));

        return redirect()->back()->with('success', 'Progress Tracking berhasil diperbarui.');
    }
}
