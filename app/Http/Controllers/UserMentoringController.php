<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\Mentoring;
use Illuminate\Http\Request;

class UserMentoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'mentorings'])->orderBy('nama', 'asc');

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

        if ($request->filled('jabatan')) {
            $jabatan = $request->jabatan;
            $query->whereHas('position', function ($q) use ($jabatan) {
                $q->where('nama_jabatan', 'like', "%{$jabatan}%");
            });
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        return view('user.mentoring.index', compact('employees', 'departments'));
    }

    public function show(Request $request, $id)
    {
        $mentoring = Mentoring::with('karyawan.department')->find($id);
        if ($mentoring) {
            $karyawan = $mentoring->karyawan;
        } else {
            $karyawan = Karyawan::with(['department', 'mentorings' => fn ($q) => $q->latest()])->findOrFail($id);
            if ($request->has('mentoring_id')) {
                $mentoring = $karyawan->mentorings->where('id', $request->mentoring_id)->first();
            }
            if (! $mentoring) {
                $mentoring = $karyawan->mentorings->first();
            }
            if ($mentoring) {
                $mentoring->karyawan = $karyawan;
            }
        }

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $karyawan || $karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        return view('user.mentoring.show', compact('karyawan', 'mentoring'));
    }

    public function showEmployee($employee_id)
    {
        $employee = Karyawan::with(['department', 'position', 'mentorings'])->findOrFail($employee_id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $mentorings = $employee->mentorings()->orderBy('tahun', 'desc')->get();

        return view('user.mentoring.employee', compact('employee', 'mentorings'));
    }
}
