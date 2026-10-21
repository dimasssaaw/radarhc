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
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal_lahir' => 'nullable|date',
            'lama_bekerja' => 'nullable|date',
            'pendidikan' => 'nullable|string',
            'kontak' => 'nullable|string',
            'email' => 'nullable|email|unique:karyawan',
            'grade' => 'nullable|string',
            'iq' => 'nullable|integer',
            'readiness' => 'nullable|in:R1 ready,R2 ready,R3 ready,R4 ready,R5 ready,not_ready,R1,R2,R3,R4,R5',
            'disc' => 'nullable|string',
            'hav' => 'nullable|in:star,high potential,potential,excellent career person,career person,problem employee,deadwood',
        ]);

        if (! empty($validated['readiness']) && in_array($validated['readiness'], ['R1', 'R2', 'R3', 'R4', 'R5'], true)) {
            $validated['readiness'] .= ' ready';
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('gambar', 'hrg');
            $validated['foto'] = '/HRG/'.$path;
        }

        $validated['tanggal_lahir'] = $validated['tanggal_lahir'] ?? null;
        $validated['masuk_kerja'] = $validated['lama_bekerja'] ?? null;
        unset($validated['lama_bekerja']);

        $validated['pendidikan'] = $validated['pendidikan'] ?? '-';
        $validated['kontak'] = $validated['kontak'] ?? '-';
        $validated['email'] = ! empty($validated['email']) ? $validated['email'] : null;
        $validated['grade'] = $validated['grade'] ?? '-';
        $validated['iq'] = isset($validated['iq']) && $validated['iq'] !== '' ? (int) $validated['iq'] : null;
        $validated['readiness'] = ! empty($validated['readiness']) ? $validated['readiness'] : 'not_ready';
        $validated['disc'] = $validated['disc'] ?? '-';
        $validated['hav'] = ! empty($validated['hav']) ? $validated['hav'] : 'potential';

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
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal_lahir' => 'nullable|date',
            'lama_bekerja' => 'nullable|date',
            'pendidikan' => 'nullable|string',
            'kontak' => 'nullable|string',
            'email' => 'nullable|email|unique:karyawan,email,'.$employee->id,
            'grade' => 'nullable|string',
            'iq' => 'nullable|integer',
            'readiness' => 'nullable|in:R1 ready,R2 ready,R3 ready,R4 ready,R5 ready,not_ready,R1,R2,R3,R4,R5',
            'disc' => 'nullable|string',
            'hav' => 'nullable|in:star,high potential,potential,excellent career person,career person,problem employee,deadwood',
        ]);

        if (! empty($validated['readiness']) && in_array($validated['readiness'], ['R1', 'R2', 'R3', 'R4', 'R5'], true)) {
            $validated['readiness'] .= ' ready';
        }

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

        $validated['masuk_kerja'] = $validated['lama_bekerja'] ?? $employee->masuk_kerja;
        unset($validated['lama_bekerja']);

        $validated['email'] = ! empty($validated['email']) ? $validated['email'] : null;
        $validated['pendidikan'] = $validated['pendidikan'] ?? $employee->pendidikan ?? '-';
        $validated['kontak'] = $validated['kontak'] ?? $employee->kontak ?? '-';
        $validated['grade'] = $validated['grade'] ?? $employee->grade ?? '-';
        $validated['iq'] = isset($validated['iq']) && $validated['iq'] !== '' ? (int) $validated['iq'] : $employee->iq;
        $validated['readiness'] = ! empty($validated['readiness']) ? $validated['readiness'] : ($employee->readiness ?: 'not_ready');
        $validated['disc'] = $validated['disc'] ?? $employee->disc ?? '-';
        $validated['hav'] = ! empty($validated['hav']) ? $validated['hav'] : ($employee->hav ?: 'potential');

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
            $import = new EmployeeImport;
            Excel::import($import, $request->file('file'));

            $message = 'Data karyawan berhasil diimpor!';
            if (! empty($import->warnings)) {
                $uniqueWarnings = array_values(array_unique($import->warnings));
                $sampleWarnings = array_slice($uniqueWarnings, 0, 3);
                $message .= ' Catatan: '.implode(' | ', $sampleWarnings);
                if (count($uniqueWarnings) > 3) {
                    $message .= ' (dan '.(count($uniqueWarnings) - 3).' catatan lainnya)';
                }
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: '.$e->getMessage());
        }
    }
}
