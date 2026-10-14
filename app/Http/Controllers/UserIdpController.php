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

        if (auth()->check() && strtolower(auth()->user()->role) !== 'admin') {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            }
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

        if ($request->filled('department_id')) {
            $query->where('id_departemen', $request->department_id);
        }

        if ($request->filled('jabatan')) {
            $query->whereHas('position', function ($q) use ($request) {
                $q->where('nama_jabatan', 'like', "%{$request->jabatan}%");
            });
        }

        $employees = $query->paginate(10)->withQueryString();
        $departments = Department::all();

        return view('user.idp.index', compact('employees', 'departments'));
    }

    public function show(Request $request, $id)
    {
        $Idp = Idp::with('karyawan')->findOrFail($id);

        return view('user.idp.show', compact('Idp'));
    }

    public function showEmployee($employee_id)
    {
        $karyawan = Karyawan::findOrFail($employee_id);
        $Idp = Idp::where('id_karyawan', $employee_id)->latest()->first() ?? new Idp;
        $Idp->karyawan = $karyawan;

        return view('user.idp.show', compact('Idp'));
    }
}
