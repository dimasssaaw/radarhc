<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAspect;
use App\Models\AssessmentCategory;
use App\Models\AssessmentReport;
use App\Models\AssessmentScore;
use App\Models\Department;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Schema;

class AssessmentReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_assessment', only: ['index', 'show', 'showEmployee']),
            new Middleware('can:tambah_assessment', only: ['create', 'store']),
            new Middleware('can:edit_assessment', only: ['edit', 'update']),
            new Middleware('can:hapus_assessment', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'assessmentReports'])->orderBy('nama', 'asc');

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

        return view('assessment_report.index', compact('employees', 'departments'));
    }

    public function create(Request $request)
    {
        $query = Karyawan::with(['department', 'assessmentReports'])->orderBy('nama', 'asc');

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

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        $employees = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Karyawan::with('department')->where('department_id', $userKaryawan->department_id)->orderBy('nama')->get()
            : Karyawan::with('department')->orderBy('nama')->get();

        $employee = null;
        $reports = collect();
        if ($request->filled('employee_id')) {
            $employee = Karyawan::with(['department', 'position', 'assessmentReports'])->find($request->employee_id);
            if ($employee) {
                $reports = $employee->assessmentReports()->orderBy('tahun', 'desc')->get();
            }
        }

        $allCategories = AssessmentCategory::with('aspects')->orderBy('order')->orderBy('id')->get();

        return view('assessment_report.create', [
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
            'employee' => $employee,
            'reports' => $reports,
            'allCategories' => $allCategories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            $targetKaryawan = Karyawan::find($request->id_karyawan);
            if (! $userKaryawan || ! $targetKaryawan || $targetKaryawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $data = $request->except(['_token', 'redirect_to', 'aspect_scores']);
        $selectedCharts = $request->input('selected_charts') ?? $request->input('selected_categories');
        if (is_array($selectedCharts)) {
            $data['selected_charts'] = array_map('intval', $selectedCharts);
        }
        unset($data['selected_categories'], $data['has_selected_categories']);

        $report = AssessmentReport::create($data);

        if ($request->has('aspect_scores') && is_array($request->aspect_scores)) {
            foreach ($request->aspect_scores as $aspectId => $scoreVal) {
                if ($scoreVal !== '' && $scoreVal !== null) {
                    $val = (float) $scoreVal;
                    AssessmentScore::create([
                        'assessment_report_id' => $report->id,
                        'assessment_aspect_id' => $aspectId,
                        'score' => $val,
                    ]);

                    $aspect = AssessmentAspect::find($aspectId);
                    if ($aspect && $aspect->code && Schema::hasColumn('assessment_report', $aspect->code)) {
                        $report->{$aspect->code} = $val;
                    }
                }
            }
            $report->save();
        }

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Assessment report data added successfully.');
        }

        $indexRoute = $isAdmin ? 'assessment-report.index' : 'user-assessment-report.index';

        return redirect()->route($indexRoute)->with('success', 'Assessment report data added successfully.');
    }

    public function showEmployee($employee_id)
    {
        $employee = Karyawan::with('assessmentReports')->findOrFail($employee_id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $reports = $employee->assessmentReports()->orderBy('tahun', 'desc')->get();

        return view('assessment_report.employee', compact('employee', 'reports'));
    }

    public function show(Request $request, $id)
    {
        $karyawan = Karyawan::with(['department', 'assessmentReports' => function ($q) {
            $q->with('aspectScores')->latest();
        }])->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $activeReport = null;
        if ($karyawan->assessmentReports->isNotEmpty()) {
            if ($request->has('report_id')) {
                $activeReport = $karyawan->assessmentReports->where('id', $request->report_id)->first();
            }
            if (! $activeReport) {
                $activeReport = $karyawan->assessmentReports->first();
            }
        }

        $categories = AssessmentCategory::with('aspects')->orderBy('order')->orderBy('id')->get();

        return view('assessment_report.show', compact('karyawan', 'activeReport', 'categories'));
    }

    public function edit(Request $request, $id)
    {
        $report = AssessmentReport::with('karyawan.department')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $userKaryawan = auth()->check() ? Karyawan::where('npk', auth()->user()->npk)->first() : null;

        if (! $isAdmin) {
            if (! $userKaryawan || ! $report->karyawan || $report->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $query = Karyawan::with(['department', 'assessmentReports'])->orderBy('nama', 'asc');

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

        $tableEmployees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        $employees = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Karyawan::with('department')->where('department_id', $userKaryawan->department_id)->orderBy('nama')->get()
            : Karyawan::with('department')->orderBy('nama')->get();

        $employee = null;
        $reports = collect();
        $targetEmployeeId = $request->input('employee_id', $report->id_karyawan);
        if ($targetEmployeeId) {
            $employee = Karyawan::with(['department', 'position', 'assessmentReports'])->find($targetEmployeeId);
            if ($employee) {
                $reports = $employee->assessmentReports()->orderBy('tahun', 'desc')->get();
            }
        }

        return view('assessment_report.edit', [
            'report' => $report,
            'employees' => $tableEmployees,
            'allEmployees' => $employees,
            'departments' => $departments,
            'employee' => $employee,
            'reports' => $reports,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        $report = AssessmentReport::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $report->karyawan || $report->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $data = $request->except(['_token', '_method', 'redirect_to', 'aspect_scores']);
        $report->update($data);

        if ($request->has('aspect_scores') && is_array($request->aspect_scores)) {
            foreach ($request->aspect_scores as $aspectId => $scoreVal) {
                if ($scoreVal !== '' && $scoreVal !== null) {
                    $val = (float) $scoreVal;
                    AssessmentScore::updateOrCreate(
                        [
                            'assessment_report_id' => $report->id,
                            'assessment_aspect_id' => $aspectId,
                        ],
                        ['score' => $val]
                    );

                    $aspect = AssessmentAspect::find($aspectId);
                    if ($aspect && $aspect->code && Schema::hasColumn('assessment_report', $aspect->code)) {
                        $report->{$aspect->code} = $val;
                    }
                }
            }
            $report->save();
        }

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Assessment report data updated successfully.');
        }

        $indexRoute = $isAdmin ? 'assessment-report.index' : 'user-assessment-report.index';

        return redirect()->route($indexRoute)->with('success', 'Assessment report data updated successfully.');
    }

    public function destroy($id)
    {
        $report = AssessmentReport::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $report->karyawan || $report->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

        $report->delete();

        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))->with('success', 'Assessment report data deleted successfully.');
        }

        $indexRoute = $isAdmin ? 'assessment-report.index' : 'user-assessment-report.index';

        return redirect()->route($indexRoute)->with('success', 'Assessment report data deleted successfully.');
    }

    public function updateSelectedCharts(Request $request, $id)
    {
        $report = AssessmentReport::findOrFail($id);
        $selected = $request->input('selected_charts', []);
        $report->selected_charts = is_array($selected) ? array_map('intval', $selected) : [];
        $report->save();

        return redirect()->back()->with('success', 'Chart Assessment yang dinilai berhasil diperbarui.');
    }
}
