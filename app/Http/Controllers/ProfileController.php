<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\Position;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $myProfile = null;

        if ($user) {
            $myProfile = Karyawan::where('npk', $user->npk)
                ->orWhere('email', $user->email)
                ->with('department')
                ->first();
        }

        $query = Karyawan::with('department')->orderBy('nama', 'asc');

        if ($myProfile && $myProfile->department_id) {
            $query->where('department_id', $myProfile->department_id);
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

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();
        $positions = Position::orderBy('nama_jabatan')->get();

        return view('user.profile.index', compact('employees', 'myProfile', 'departments', 'positions'));
    }

    public function show(string $id)
    {
        $employee = Karyawan::findOrFail($id);

        return view('user.profile.show', compact('employee'));
    }
}
