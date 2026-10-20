<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $karyawan = null;

        if ($user) {
            $karyawan = Karyawan::where('npk', $user->npk)
                ->orWhere('email', $user->email)
                ->with('department')
                ->first();
        }

        if (! $karyawan) {
            // Fallback for demo/dev if user not found
            $karyawan = Karyawan::with('department')->first();
        }

        $departmentId = $karyawan->department_id;
        $timDepartment = Karyawan::where('department_id', $departmentId)->with('department')->orderBy('nama', 'asc')->get();

        $totalAnggota = $timDepartment->count();
        $kaderSiap = $timDepartment->filter(function ($k) {
            return $k->readiness === 'R3 ready';
        })->count();

        // Check if Certification model exists, else fallback to 0
        $totalSertifikasi = class_exists('\App\Models\Certification')
            ? Certification::whereIn('id_karyawan', $timDepartment->pluck('id'))->count()
            : 0;

        return view('user.dashboard', compact(
            'karyawan',
            'timDepartment',
            'totalAnggota',
            'kaderSiap',
            'totalSertifikasi'
        ));
    }
}
