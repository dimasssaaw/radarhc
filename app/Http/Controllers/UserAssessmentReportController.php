<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class UserAssessmentReportController extends Controller
{
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

        if ($request->filled('jabatan')) {
            $jabatan = $request->jabatan;
            $query->whereHas('position', function ($q) use ($jabatan) {
                $q->where('nama_jabatan', 'like', "%{$jabatan}%");
            });
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        return view('user.assessment_report.index', compact('employees', 'departments'));
    }

    public function show(Request $request, $id)
    {
        $karyawan = Karyawan::with(['department', 'assessmentReports' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        $activeReport = $karyawan->assessmentReports->first();

        return view('user.assessment_report.show', compact('karyawan', 'activeReport'));
    }
}
