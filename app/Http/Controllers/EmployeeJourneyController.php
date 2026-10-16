<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\PerjalananKarir;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeJourneyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            (new Middleware('dynamic.permission:journey'))->except(['index', 'show', 'exportPdf']),
        ];
    }

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

        return view('journey.index', compact('karyawans', 'departments'));
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

        return view('journey.show', compact('karyawan', 'activeReport', 'trainings', 'perjalananKarirs', 'departments'));
    }

    public function exportPdf($id)
    {
        $karyawan = Karyawan::with([
            'department',
            'position',
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
        $trainings = $karyawan->trainings;
        $perjalananKarirs = $karyawan->perjalananKarirs->sortBy('tahun_karir');
        $departments = Department::orderBy('nama_departemen', 'asc')->get();

        return view('journey.pdf', compact('karyawan', 'activeReport', 'trainings', 'perjalananKarirs', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:karyawan,id',
            'department_id' => 'required|exists:departments,id',
            'tahun_karir' => 'required|integer',
            'nama_posisi' => 'required|string|max:255',
            'tipe_milestone' => 'required|string',
            'status_pencapaian' => 'required|string',
            'catatan_detail' => 'nullable|string',
            'operational_award' => 'nullable|string',
        ]);

        if ($request->status_pencapaian == 'aktif/tercapai') {
            PerjalananKarir::where('id_karyawan', $request->employee_id)
                ->where('status_pencapaian', 'aktif/tercapai')
                ->update(['status_pencapaian' => 'riwayat/selesai']);
        }

        PerjalananKarir::create([
            'id_karyawan' => $request->employee_id,
            'department_id' => $request->department_id,
            'tahun_karir' => $request->tahun_karir,
            'nama_posisi' => $request->nama_posisi,
            'tipe_milestone' => $request->tipe_milestone,
            'status_pencapaian' => $request->status_pencapaian,
            'catatan_detail' => $request->catatan_detail,
            'operational_award' => $request->operational_award,
        ]);

        return back()->with('success', 'Perjalanan karir berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'tahun_karir' => 'required|integer',
            'nama_posisi' => 'required|string|max:255',
            'tipe_milestone' => 'required|string',
            'status_pencapaian' => 'required|string',
            'catatan_detail' => 'nullable|string',
            'operational_award' => 'nullable|string',
        ]);

        $pk = PerjalananKarir::findOrFail($id);

        if ($request->status_pencapaian == 'aktif/tercapai') {
            PerjalananKarir::where('id_karyawan', $pk->id_karyawan)
                ->where('id', '!=', $id)
                ->where('status_pencapaian', 'aktif/tercapai')
                ->update(['status_pencapaian' => 'riwayat/selesai']);
        }

        $pk->update([
            'department_id' => $request->department_id,
            'tahun_karir' => $request->tahun_karir,
            'nama_posisi' => $request->nama_posisi,
            'tipe_milestone' => $request->tipe_milestone,
            'status_pencapaian' => $request->status_pencapaian,
            'catatan_detail' => $request->catatan_detail,
            'operational_award' => $request->operational_award,
        ]);

        return back()->with('success', 'Perjalanan karir berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pk = PerjalananKarir::findOrFail($id);
        $pk->delete();

        return back()->with('success', 'Perjalanan karir berhasil dihapus.');
    }

    public function storeRecommendation(Request $request, $employee_id)
    {
        $request->validate([
            'keputusan' => 'nullable|string',
            'catatan_rekomendasi_atasan' => 'nullable|string',
        ]);

        // Cari riwayat karir aktif (atau yang terakhir) dari karyawan ini
        $activePk = PerjalananKarir::where('id_karyawan', $employee_id)
            ->where('status_pencapaian', 'aktif/tercapai')
            ->first();

        if (! $activePk) {
            $activePk = PerjalananKarir::where('id_karyawan', $employee_id)
                ->latest('tahun_karir')
                ->first();
        }

        if ($activePk) {
            $activePk->update([
                'keputusan' => $request->keputusan,
                'catatan_rekomendasi_atasan' => $request->catatan_rekomendasi_atasan,
            ]);

            return back()->with('success', 'Kesimpulan & Rekomendasi berhasil disimpan.');
        }

        return back()->with('error', 'Gagal menyimpan! Belum ada data perjalanan karir untuk karyawan ini.');
    }
}
