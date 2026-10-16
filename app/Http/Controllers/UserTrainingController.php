<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\RiwayatTraining;
use App\Models\Training;
use Illuminate\Http\Request;

class UserTrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'trainings'])->orderBy('nama', 'asc');

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

        return view('user.training.index', compact('employees', 'departments'));
    }

    public function show($id)
    {
        $employee = Karyawan::with('department')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $training = Training::firstOrCreate(
            ['id_karyawan' => $id],
            ['target_mandatory' => 0, 'target_skill' => 0, 'target_managerial' => 0]
        );

        $riwayatMandatory = RiwayatTraining::where('id_karyawan', $id)->where('kategori', 'mandatory')->get();
        $riwayatSkill = RiwayatTraining::where('id_karyawan', $id)->where('kategori', 'skill')->get();
        $riwayatManagerial = RiwayatTraining::where('id_karyawan', $id)->where('kategori', 'managerial')->get();

        $completedMandatory = RiwayatTraining::where('id_karyawan', $id)->where('kategori', 'mandatory')->where('status_training', 'completed')->count();
        $completedSkill = RiwayatTraining::where('id_karyawan', $id)->where('kategori', 'skill')->where('status_training', 'completed')->count();
        $completedManagerial = RiwayatTraining::where('id_karyawan', $id)->where('kategori', 'managerial')->where('status_training', 'completed')->count();

        $achieveMandatory = $training->target_mandatory > 0 ? min(100, round(($completedMandatory / $training->target_mandatory) * 100)) : 0;
        $achieveSkill = $training->target_skill > 0 ? min(100, round(($completedSkill / $training->target_skill) * 100)) : 0;
        $achieveManagerial = $training->target_managerial > 0 ? min(100, round(($completedManagerial / $training->target_managerial) * 100)) : 0;

        return view('user.training.show', compact(
            'employee',
            'training',
            'riwayatMandatory',
            'riwayatSkill',
            'riwayatManagerial',
            'completedMandatory',
            'completedSkill',
            'completedManagerial',
            'achieveMandatory',
            'achieveSkill',
            'achieveManagerial'
        ));
    }
}
