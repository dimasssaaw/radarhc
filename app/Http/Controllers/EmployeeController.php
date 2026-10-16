<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeTemplateExport;
use App\Imports\EmployeeImport;
use App\Models\Department;
use App\Models\Karyawan;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('dynamic.permission:karyawan'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
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
        } elseif ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%")
                    ->orWhereHas('position', function ($q3) use ($search) {
                        $q3->where('nama_jabatan', 'like', "%{$search}%");
                    })
                    ->orWhere('readiness', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('nama_departemen', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();
        $positions = Position::orderBy('nama_jabatan')->get();

        return view('employee.index', compact('employees', 'departments', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        } elseif (request()->filled('department_id')) {
            $query->where('department_id', request()->department_id);
        }

        if (request()->filled('search')) {
            $search = request()->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%")
                    ->orWhereHas('position', function ($q3) use ($search) {
                        $q3->where('nama_jabatan', 'like', "%{$search}%");
                    })
                    ->orWhere('readiness', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('nama_departemen', 'like', "%{$search}%");
                    });
            });
        }

        if (request()->filled('position_id')) {
            $query->where('position_id', request()->position_id);
        }

        if (request()->filled('readiness')) {
            $query->where('readiness', request()->readiness);
        }

        $employees = $query->paginate(10)->appends(request()->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();
        $positions = Position::orderBy('nama_jabatan')->get();

        return view('employee.create', compact('employees', 'departments', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'npk' => 'required|string|unique:karyawan',
            'nama' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal_lahir' => 'required|date',
            'pendidikan' => 'required|string',
            'kontak' => 'required|string|min:10',
            'email' => 'required|email|unique:karyawan',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'grade' => 'required|string',
            'lama_bekerja' => 'required|date',
            'iq' => 'required|integer',
            'readiness' => 'required|in:R1 ready,R2 ready,R3 ready,R4 ready,R5 ready,not_ready',
            'disc' => 'required|string',
            'hav' => 'required|in:star,high potential,potential,excellent career person,career person,problem employee,deadwood',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('gambar', 'hrg');
            $validated['foto'] = '/HRG/'.$path;
        }

        $validated['masuk_kerja'] = $validated['lama_bekerja'];
        unset($validated['lama_bekerja']);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($userKaryawan && $userKaryawan->department_id) {
                $validated['department_id'] = $userKaryawan->department_id;
            } else {
                abort(403, 'Akses tidak diizinkan.');
            }
        }

        Karyawan::create($validated);

        return redirect()->route('employee.index')->with('success', 'Data Karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Karyawan::findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Karyawan::findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        $userKaryawan = auth()->check() ? Karyawan::where('npk', auth()->user()->npk)->first() : null;

        if (! $isAdmin) {
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $query = Karyawan::with('department')->orderBy('nama', 'asc');

        if (! $isAdmin) {
            if ($userKaryawan && $userKaryawan->department_id) {
                $query->where('department_id', $userKaryawan->department_id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif (request()->filled('department_id')) {
            $query->where('department_id', request()->department_id);
        }

        if (request()->filled('search')) {
            $search = request()->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%")
                    ->orWhereHas('position', function ($q3) use ($search) {
                        $q3->where('nama_jabatan', 'like', "%{$search}%");
                    })
                    ->orWhere('readiness', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('nama_departemen', 'like', "%{$search}%");
                    });
            });
        }

        if (request()->filled('position_id')) {
            $query->where('position_id', request()->position_id);
        }

        if (request()->filled('readiness')) {
            $query->where('readiness', request()->readiness);
        }

        $employees = $query->paginate(10)->appends(request()->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();
        $positions = Position::orderBy('nama_jabatan')->get();

        return view('employee.edit', compact('employee', 'employees', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Karyawan::findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $validated = $request->validate([
            'npk' => 'required|string|unique:karyawan,npk,'.$employee->id,
            'nama' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal_lahir' => 'required|date',
            'pendidikan' => 'required|string',
            'kontak' => 'required|string|min:10',
            'email' => 'required|email|unique:karyawan,email,'.$employee->id,
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'grade' => 'required|string',
            'lama_bekerja' => 'required|date',
            'iq' => 'required|integer',
            'readiness' => 'required|in:R1 ready,R2 ready,R3 ready,R4 ready,R5 ready,not_ready',
            'disc' => 'required|string',
            'hav' => 'required|in:star,high potential,potential,excellent career person,career person,problem employee,deadwood',
        ]);

        if (! $isAdmin) {
            $validated['department_id'] = $userKaryawan->department_id;
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (optional)
            if ($employee->foto && file_exists(public_path($employee->foto))) {
                @unlink(public_path($employee->foto));
            }
            $file = $request->file('foto');
            $path = $file->store('gambar', 'hrg');
            $validated['foto'] = '/HRG/'.$path;
        }

        $validated['masuk_kerja'] = $validated['lama_bekerja'];
        unset($validated['lama_bekerja']);

        $employee->update($validated);

        return redirect()->route('employee.index')->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Karyawan::findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $employee->delete();

        return back()->with('success', 'Data Karyawan berhasil dihapus.');
    }

    /**
     * Download employee excel template.
     */
    public function template()
    {
        return Excel::download(new EmployeeTemplateExport, 'template_data_karyawan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:15360',
        ]);

        try {
            Excel::import(new EmployeeImport, $request->file('file'));

            return back()->with('success', 'Data karyawan berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: '.$e->getMessage());
        }
    }
}
