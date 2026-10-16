<?php

namespace App\Http\Controllers;

use App\Models\AssessmentReport;
use App\Models\Department;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssessmentReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_assessment', only: ['index', 'show']),
            new Middleware('can:tambah_assessment', only: ['create', 'store']),
            new Middleware('can:edit_assessment', only: ['edit', 'update']),
            new Middleware('can:hapus_assessment', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'assessmentReports'])->orderBy('nama', 'asc');

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

        return view('assessment_report.index', compact('employees', 'departments'));
    }

    public function create(Request $request)
    {
        $query = Karyawan::with(['department', 'assessmentReports'])->orderBy('nama', 'asc');

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

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        $employees = Karyawan::with('department')->orderBy('nama')->get();

        return view('assessment_report.create', [
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        $data = $request->except(['_token', 'redirect_to']);

        AssessmentReport::create($data);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Data Assessment Report berhasil ditambahkan.');
        }

        return redirect()->route('assessment-report.index')->with('success', 'Data Assessment Report berhasil ditambahkan.');
    }

    public function show(Request $request, $id)
    {
        $karyawan = Karyawan::with(['department', 'assessmentReports' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        $activeReport = null;
        if ($karyawan->assessmentReports->isNotEmpty()) {
            if ($request->has('report_id')) {
                $activeReport = $karyawan->assessmentReports->where('id', $request->report_id)->first();
            }
            if (! $activeReport) {
                $activeReport = $karyawan->assessmentReports->first();
            }
        }

        return view('assessment_report.show', compact('karyawan', 'activeReport'));
    }

    public function edit(Request $request, $id)
    {
        $report = AssessmentReport::with('karyawan.department')->findOrFail($id);

        $query = Karyawan::with(['department', 'assessmentReports'])->orderBy('nama', 'asc');

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

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        $employees = Karyawan::orderBy('nama')->get();

        return view('assessment_report.edit', [
            'report' => $report,
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        $data = $request->except(['_token', '_method', 'redirect_to']);

        $report = AssessmentReport::findOrFail($id);
        $report->update($data);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Data Assessment Report berhasil diperbarui.');
        }

        return redirect()->route('assessment-report.index')->with('success', 'Data Assessment Report berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $report = AssessmentReport::findOrFail($id);
        $report->delete();

        return redirect()->route('assessment-report.index')->with('success', 'Data Assessment Report berhasil dihapus.');
    }
}
