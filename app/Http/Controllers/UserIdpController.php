<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Idp;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class UserIdpController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'position', 'idps' => function ($q) {
            $q->latest();
        }])->orderBy('nama', 'asc');

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

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%")
                    ->orWhere('readiness', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jabatan')) {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('nama_jabatan', 'like', "%{$request->jabatan}%");
            });
        }

        $employees = $query->paginate(10)->withQueryString();
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        return view('user.idp.index', compact('employees', 'departments'));
    }

    public function show(Request $request, $id)
    {
        $Idp = Idp::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $Idp->karyawan || $Idp->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        return view('user.idp.show', compact('Idp'));
    }

    public function showEmployee($employee_id)
    {
        $employee = Karyawan::with('idps')->findOrFail($employee_id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $idps = $employee->idps()->orderBy('tahun', 'desc')->get();

        return view('user.idp.employee', compact('employee', 'idps'));
    }
}
