<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\Department;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_certification', only: ['index', 'show']),
            new Middleware('can:tambah_certification', only: ['store']),
            new Middleware('can:edit_certification', only: ['update', 'uploadLampiran']),
            new Middleware('can:hapus_certification', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'certifications'])->orderBy('nama', 'asc');

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
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('nama_departemen', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        return view('certification.index', compact('employees', 'departments'));
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

        $certifications = Certification::where('id_karyawan', $id)->latest()->get();

        return view('certification.show', compact('employee', 'certifications'));
    }

    public function store(Request $request, $employee_id)
    {
        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            $targetEmployee = Karyawan::find($employee_id);
            if (! $userKaryawan || ! $targetEmployee || $targetEmployee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }
        $request->validate([
            'nama_sertifikasi' => 'required|string',
            'penerbit' => 'nullable|string',
            'terbit' => 'nullable|date',
            'kadaluarsa' => 'nullable|date',
            'status_sertifikat' => 'required|in:aktif,nonaktif',
            'lampiran_sertifikasi' => 'nullable|mimes:pdf|max:10240',
        ]);

        $certification = new Certification([
            'id_karyawan' => $employee_id,
            'nama_sertifikasi' => $request->nama_sertifikasi,
            'penerbit' => $request->penerbit,
            'terbit' => $request->terbit,
            'kadaluarsa' => $request->kadaluarsa,
            'status_sertifikat' => $request->status_sertifikat,
        ]);

        $employee = Karyawan::findOrFail($employee_id);
        $folderName = 'Sertifikat/'.$employee->npk.'_'.Str::slug($employee->nama);

        if ($request->hasFile('lampiran_sertifikasi')) {
            $path = $request->file('lampiran_sertifikasi')->store($folderName, 'hrg');
            $certification->lampiran_sertifikasi = '/HRG/'.$path;
        }

        $certification->save();

        return redirect()->back()->with('success', 'Sertifikasi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $certification = Certification::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $certification->karyawan || $certification->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        $request->validate([
            'nama_sertifikasi' => 'required|string',
            'penerbit' => 'nullable|string',
            'terbit' => 'nullable|date',
            'kadaluarsa' => 'nullable|date',
            'status_sertifikat' => 'required|in:aktif,nonaktif',
            'lampiran_sertifikasi' => 'nullable|mimes:pdf|max:10240',
        ]);

        $certification->nama_sertifikasi = $request->nama_sertifikasi;
        $certification->penerbit = $request->penerbit;
        $certification->terbit = $request->terbit;
        $certification->kadaluarsa = $request->kadaluarsa;
        $certification->status_sertifikat = $request->status_sertifikat;

        if ($request->has('hapus_lampiran') && $request->hapus_lampiran == '1') {
            if ($certification->lampiran_sertifikasi) {
                $oldPath = str_replace('/HRG/', '', $certification->lampiran_sertifikasi);
                Storage::disk('hrg')->delete($oldPath);
                $certification->lampiran_sertifikasi = null;
            }
        }

        $employee = Karyawan::findOrFail($certification->id_karyawan);
        $folderName = 'Sertifikat/'.$employee->npk.'_'.Str::slug($employee->nama);

        if ($request->hasFile('lampiran_sertifikasi')) {
            if ($certification->lampiran_sertifikasi) {
                $oldPath = str_replace('/HRG/', '', $certification->lampiran_sertifikasi);
                Storage::disk('hrg')->delete($oldPath);
            }
            $path = $request->file('lampiran_sertifikasi')->store($folderName, 'hrg');
            $certification->lampiran_sertifikasi = '/HRG/'.$path;
        }

        $certification->save();

        return redirect()->back()->with('success', 'Sertifikasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $certification = Certification::with('karyawan')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || ! $certification->karyawan || $certification->karyawan->department_id !== $userKaryawan->department_id) {
                abort(403, 'Akses tidak diizinkan untuk departemen lain.');
            }
        }

        if ($certification->lampiran_sertifikasi) {
            $oldPath = str_replace('/HRG/', '', $certification->lampiran_sertifikasi);
            Storage::disk('hrg')->delete($oldPath);
        }

        $certification->delete();

        return redirect()->back()->with('success', 'Sertifikasi berhasil dihapus');
    }

    public function uploadLampiran(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:10240',
        ]);

        $certification = Certification::findOrFail($id);

        if ($certification->lampiran_sertifikasi) {
            $oldPath = str_replace('/HRG/', '', $certification->lampiran_sertifikasi);
            Storage::disk('hrg')->delete($oldPath);
        }

        $employee = Karyawan::findOrFail($certification->id_karyawan);
        $folderName = 'Sertifikat/'.$employee->npk.'_'.Str::slug($employee->nama);

        $path = $request->file('file')->store($folderName, 'hrg');

        $certification->lampiran_sertifikasi = '/HRG/'.$path;
        $certification->save();

        return redirect()->back()->with('success', 'Lampiran berhasil diunggah');
    }
}
