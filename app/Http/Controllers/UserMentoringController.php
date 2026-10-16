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
        $karyawan = Karyawan::with(['department', 'mentorings' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $mentoring = $karyawan->mentorings->first();
        if ($mentoring) {
            $mentoring->karyawan = $karyawan;
        }

        return view('user.mentoring.show', compact('karyawan', 'mentoring'));
    }

    public function showEmployee($employee_id)
    {
        $karyawan = Karyawan::findOrFail($employee_id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $mentoring = Mentoring::where('id_karyawan', $employee_id)->latest()->first() ?? new Mentoring;
        $mentoring->karyawan = $karyawan;

        return view('user.mentoring.show', compact('mentoring', 'karyawan'));
    }
}
