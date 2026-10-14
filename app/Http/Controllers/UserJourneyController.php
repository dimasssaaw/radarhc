<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class UserJourneyController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with('department')->orderBy('nama', 'asc');

        if (auth()->check() && strtolower(auth()->user()->role) !== 'admin') {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            }
        }

        if ($request->has('search') && $request->search != '') {
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

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('position_id') && $request->position_id != '') {
            $query->where('position_id', $request->position_id);
        }

        $karyawans = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        return view('user.journey.index', compact('karyawans', 'departments'));
    }

    public function show($id)
    {
        $karyawan = Karyawan::with([
            'department',
            'perjalananKarirs',
            'assessmentReports' => function ($q) {
                $q->latest('tahun');
            },
            'idps' => function ($q) {
                $q->latest();
            },
            'trainings',
            'certifications',
            'scarf',
            'viaCharacters',
            'strengths',
            'mentorings',
        ])->findOrFail($id);

        $activeReport = $karyawan->assessmentReports->first();

        // Calculate training progress
        $trainings = $karyawan->trainings;

        // Ensure perjalanaKarirs is sorted by year
        $perjalananKarirs = $karyawan->perjalananKarirs->sortBy('tahun_karir');
        $departments = Department::orderBy('nama_departemen', 'asc')->get();

        return view('user.journey.show', compact('karyawan', 'activeReport', 'trainings', 'perjalananKarirs', 'departments'));
    }
}
