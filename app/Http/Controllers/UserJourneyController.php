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

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $userKaryawan = auth()->check() ? Karyawan::where('npk', auth()->user()->npk)->first() : null;

        if (! $isAdmin) {
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
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

        if ($request->has('position_id') && $request->position_id != '') {
            $query->where('position_id', $request->position_id);
        }

        $karyawans = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        return view('user.journey.index', compact('karyawans', 'departments'));
    }

    public function show($id)
    {
        $karyawan = Karyawan::with([
            'department',
            'perjalananKarirs',
            'assessmentReports' => function ($q) {
                $q->orderByDesc('tahun')->orderByDesc('periode')->orderByDesc('id');
            },
            'idps' => function ($q) {
                $q->latest();
            },
            'idps.penilaian',
            'trainings',
            'certifications',
            'scarf',
            'viaCharacters',
            'strengths',
            'mentorings',
        ])->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $activeReport = $karyawan->assessmentReports->first();

        // Calculate training progress
        $trainings = $karyawan->trainings;

        // Ensure perjalanaKarirs is sorted by year
        $perjalananKarirs = $karyawan->perjalananKarirs->sortBy('tahun_karir');
        $departments = Department::orderBy('nama_departemen', 'asc')->get();

        return view('user.journey.show', compact('karyawan', 'activeReport', 'trainings', 'perjalananKarirs', 'departments'));
    }

    public function exportPdf(Request $request, $id)
    {
        $karyawan = Karyawan::with([
            'department',
            'position',
            'perjalananKarirs',
            'assessmentReports' => function ($q) {
                $q->orderByDesc('tahun')->orderByDesc('periode')->orderByDesc('id');
            },
            'idps' => function ($q) {
                $q->orderByDesc('tahun')->orderByDesc('periode')->orderByDesc('id');
            },
            'idps.penilaian',
            'trainings',
            'certifications',
            'scarf',
            'viaCharacters',
            'strengths',
            'mentorings' => function ($q) {
                $q->orderByDesc('tahun')->orderByDesc('periode')->orderByDesc('id');
            },
        ])->findOrFail($id);

        // 1. Assessment Report (prioritaskan request parameter atau ambil data teratas)
        $assessmentId = $request->query('assessment_id');
        $activeReport = $assessmentId
            ? $karyawan->assessmentReports->firstWhere('id', $assessmentId)
            : $karyawan->assessmentReports->first();

        // 2. IDP (prioritaskan request parameter atau cari yang memiliki nilai penilaian)
        $idpId = $request->query('idp_id');
        if ($idpId) {
            $activeIdp = $karyawan->idps->firstWhere('id', $idpId);
        } else {
            $activeIdp = $karyawan->idps->first(function ($item) {
                $p = $item->penilaian;
                if (! $p) {
                    return false;
                }
                $arr = is_array($p) ? $p : (is_object($p) ? (method_exists($p, 'toArray') ? $p->toArray() : (array) $p) : json_decode($p, true));
                if (! $arr) {
                    return false;
                }
                foreach ($arr as $k => $v) {
                    if (is_numeric($v) && (float) $v > 0 && ! in_array($k, ['id', 'id_idp', 'id_karyawan', 'id_penilain'])) {
                        return true;
                    }
                }

                return false;
            }) ?? $karyawan->idps->first();
        }

        // 3. Mentoring (prioritaskan request parameter atau ambil data teratas)
        $mentoringId = $request->query('mentoring_id');
        $latestMentoring = $mentoringId
            ? $karyawan->mentorings->firstWhere('id', $mentoringId)
            : $karyawan->mentorings->first();

        $trainings = $karyawan->trainings;
        $perjalananKarirs = $karyawan->perjalananKarirs->sortBy('tahun_karir');
        $departments = Department::orderBy('nama_departemen', 'asc')->get();

        return view('journey.pdf', compact('karyawan', 'activeReport', 'activeIdp', 'latestMentoring', 'trainings', 'perjalananKarirs', 'departments'));
    }
}
