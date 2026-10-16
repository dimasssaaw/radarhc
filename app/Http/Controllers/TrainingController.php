<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\RiwayatTraining;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:lihat_training', only: ['index', 'show']),
            new Middleware('can:tambah_training', only: ['storeHistory']),
            new Middleware('can:edit_training', only: ['updateTarget', 'updateHistory', 'updateStatus', 'uploadEva']),
            new Middleware('can:hapus_training', only: ['destroyHistory']),
        ];
    }

    public function index(Request $request)
    {
        $query = Karyawan::with(['department', 'trainings'])->orderBy('nama', 'asc');

        if (auth()->user()->role !== 'admin') {
            $employee = Karyawan::where('npk', auth()->user()->npk)->first();
            if ($employee && $employee->department_id) {
                $query->where('department_id', $employee->department_id);
            }
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

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = Department::orderBy('nama_departemen')->get();

        return view('training.index', compact('employees', 'departments'));
    }

    public function show($id)
    {

        $employee = Karyawan::with('department')->findOrFail($id);

        $training = Training::where('id_karyawan', $id)->first() ?? new Training([
            'target_mandatory' => 0,
            'target_skill' => 0,
            'target_managerial' => 0,
        ]);

        $riwayat = RiwayatTraining::where('id_karyawan', $id)->latest()->get();

        $riwayatMandatory = $riwayat->where('kategori', 'mandatory');
        $riwayatSkill = $riwayat->where('kategori', 'skill');
        $riwayatManagerial = $riwayat->where('kategori', 'managerial');

        $isCompleted = function ($item) {
            return $item->eva1_status === 'completed' &&
                   $item->eva2_status === 'completed' &&
                   $item->eva3_status === 'completed';
        };

        $completedMandatory = $riwayatMandatory->filter($isCompleted)->count();
        $completedSkill = $riwayatSkill->filter($isCompleted)->count();
        $completedManagerial = $riwayatManagerial->filter($isCompleted)->count();

        $achieveMandatory = $training->target_mandatory > 0 ? min(100, round(($completedMandatory / $training->target_mandatory) * 100)) : 0;
        $achieveSkill = $training->target_skill > 0 ? min(100, round(($completedSkill / $training->target_skill) * 100)) : 0;
        $achieveManagerial = $training->target_managerial > 0 ? min(100, round(($completedManagerial / $training->target_managerial) * 100)) : 0;

        return view('training.show', compact(
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

    public function updateTarget(Request $request, $id)
    {
        $request->validate([
            'target_mandatory' => 'required|integer|min:0',
            'target_skill' => 'required|integer|min:0',
            'target_managerial' => 'required|integer|min:0',
        ]);

        $training = Training::firstOrNew(['id_karyawan' => $id]);
        $training->target_mandatory = $request->target_mandatory;
        $training->target_skill = $request->target_skill;
        $training->target_managerial = $request->target_managerial;
        $training->save();

        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))->with('success', 'Target Training berhasil diperbarui');
        }

        return redirect()->back()->with('success', 'Target Training berhasil diperbarui');
    }

    public function storeHistory(Request $request, $employee_id)
    {
        $request->validate([
            'kategori' => 'required|in:mandatory,skill,managerial',
            'nama_training' => 'required|string',
            'tanggal_training' => 'nullable|date',
            'eva1_status' => 'nullable|in:pending,completed,in_progress,remedial,not_started',
            'eva2_status' => 'nullable|in:pending,completed,in_progress,remedial,not_started',
            'eva3_status' => 'nullable|in:pending,completed,in_progress,remedial,not_started',
            'eva1_file' => 'nullable|mimes:pdf|max:10240',
            'eva2_file' => 'nullable|mimes:pdf|max:10240',
            'eva3_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $training = Training::firstOrCreate(
            ['id_karyawan' => $employee_id],
            ['target_mandatory' => 0, 'target_skill' => 0, 'target_managerial' => 0]
        );

        $riwayat = new RiwayatTraining([
            'id_training' => $training->id,
            'id_karyawan' => $employee_id,
            'kategori' => $request->kategori,
            'nama_training' => $request->nama_training,
            'status_training' => 'completed',
            'tanggal_training' => $request->tanggal_training,
            'eva1_status' => $request->eva1_status,
            'eva2_status' => $request->eva2_status,
            'eva3_status' => $request->eva3_status,
        ]);

        $karyawan = Karyawan::findOrFail($employee_id);
        $folderPath = 'training/'.$karyawan->npk.'_'.Str::slug($karyawan->nama);

        if ($request->hasFile('eva1_file')) {
            $file = $request->file('eva1_file');
            $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $riwayat->eva1_lampiran = $file->storeAs($folderPath, $filename, 'hrg');
        }
        if ($request->hasFile('eva2_file')) {
            $file = $request->file('eva2_file');
            $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $riwayat->eva2_lampiran = $file->storeAs($folderPath, $filename, 'hrg');
        }
        if ($request->hasFile('eva3_file')) {
            $file = $request->file('eva3_file');
            $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $riwayat->eva3_lampiran = $file->storeAs($folderPath, $filename, 'hrg');
        }

        $riwayat->save();

        return redirect()->back()->with('success', 'Riwayat Training berhasil ditambahkan');
    }

    public function updateHistory(Request $request, $id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);

        $request->validate([
            'nama_training' => 'required|string',
            'tanggal_training' => 'nullable|date',
            'eva1_status' => 'nullable|in:pending,completed,in_progress,remedial,not_started',
            'eva2_status' => 'nullable|in:pending,completed,in_progress,remedial,not_started',
            'eva3_status' => 'nullable|in:pending,completed,in_progress,remedial,not_started',
            'eva1_file' => 'nullable|mimes:pdf|max:10240',
            'eva2_file' => 'nullable|mimes:pdf|max:10240',
            'eva3_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        \Log::info('updateHistory called', ['id' => $id, 'data' => $request->all()]);

        $riwayat->nama_training = $request->nama_training;
        $riwayat->tanggal_training = $request->tanggal_training;
        $riwayat->eva1_status = $request->eva1_status;
        $riwayat->eva2_status = $request->eva2_status;
        $riwayat->eva3_status = $request->eva3_status;

        $karyawan = Karyawan::findOrFail($riwayat->id_karyawan);
        $folderPath = 'training/'.$karyawan->npk.'_'.Str::slug($karyawan->nama);

        if ($request->hasFile('eva1_file')) {
            if ($riwayat->eva1_lampiran) {
                Storage::disk('hrg')->delete($riwayat->eva1_lampiran);
            }
            $file = $request->file('eva1_file');
            $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $riwayat->eva1_lampiran = $file->storeAs($folderPath, $filename, 'hrg');
        }
        if ($request->hasFile('eva2_file')) {
            if ($riwayat->eva2_lampiran) {
                Storage::disk('hrg')->delete($riwayat->eva2_lampiran);
            }
            $file = $request->file('eva2_file');
            $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $riwayat->eva2_lampiran = $file->storeAs($folderPath, $filename, 'hrg');
        }
        if ($request->hasFile('eva3_file')) {
            if ($riwayat->eva3_lampiran) {
                Storage::disk('hrg')->delete($riwayat->eva3_lampiran);
            }
            $file = $request->file('eva3_file');
            $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $riwayat->eva3_lampiran = $file->storeAs($folderPath, $filename, 'hrg');
        }

        $riwayat->save();

        return redirect()->back()->with('success', 'Riwayat Training berhasil diperbarui');
    }

    public function updateStatus(Request $request, $id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);

        $rules = [];
        $data = [];

        if ($request->has('status_training')) {
            $rules['status_training'] = 'required|in:completed,in_progress,remedial,not_started';
            $data['status_training'] = $request->status_training;
        } elseif ($request->has('eva1_status')) {
            $rules['eva1_status'] = 'required|in:completed,in_progress,remedial,not_started,pending';
            $data['eva1_status'] = $request->eva1_status;
        } elseif ($request->has('eva2_status')) {
            $rules['eva2_status'] = 'required|in:completed,in_progress,remedial,not_started,pending';
            $data['eva2_status'] = $request->eva2_status;
        } elseif ($request->has('eva3_status')) {
            $rules['eva3_status'] = 'required|in:completed,in_progress,remedial,not_started,pending';
            $data['eva3_status'] = $request->eva3_status;
        }

        if (! empty($rules)) {
            $request->validate($rules);
            $riwayat->update($data);
        }

        return redirect()->back()->with('success', 'Status berhasil diperbarui');
    }

    public function destroyHistory($id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);

        if ($riwayat->eva1_lampiran) {
            Storage::disk('hrg')->delete($riwayat->eva1_lampiran);
        }
        if ($riwayat->eva2_lampiran) {
            Storage::disk('hrg')->delete($riwayat->eva2_lampiran);
        }
        if ($riwayat->eva3_lampiran) {
            Storage::disk('hrg')->delete($riwayat->eva3_lampiran);
        }

        $riwayat->delete();

        return redirect()->back()->with('success', 'Riwayat Training berhasil dihapus');
    }

    public function uploadEva(Request $request, $id)
    {
        $request->validate([
            'eva' => 'required|in:eva1,eva2,eva3',
            'file' => 'required|mimes:pdf|max:10240',
        ]);

        $riwayat = RiwayatTraining::findOrFail($id);

        $eva = $request->eva;

        if ($riwayat->{$eva.'_lampiran'}) {
            Storage::disk('hrg')->delete($riwayat->{$eva.'_lampiran'});
        }

        $karyawan = Karyawan::findOrFail($riwayat->id_karyawan);
        $folderPath = 'training/'.$karyawan->npk.'_'.Str::slug($karyawan->nama);

        $file = $request->file('file');
        $filename = uniqid().'_'.str_replace(' ', '_', $file->getClientOriginalName());
        $path = $file->storeAs($folderPath, $filename, 'hrg');

        $riwayat->{$eva.'_lampiran'} = $path;
        $riwayat->save();

        return redirect()->back()->with('success', 'Lampiran EVA berhasil diunggah');
    }
}
