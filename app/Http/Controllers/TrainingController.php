<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Karyawan;
use App\Models\RiwayatTraining;
use App\Models\Training;
use Carbon\Carbon;
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

        if ($request->filled('readiness')) {
            $query->where('readiness', $request->readiness);
        }

        $employees = $query->paginate(10)->appends($request->query());
        $departments = ! $isAdmin && $userKaryawan && $userKaryawan->department_id
            ? Department::where('id', $userKaryawan->department_id)->get()
            : Department::orderBy('nama_departemen')->get();

        return view('training.index', compact('employees', 'departments'));
    }

    public function show($id)
    {
        $employee = Karyawan::with('department')->findOrFail($id);

        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            if (! $userKaryawan || $employee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }

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

    private function authorizeDepartmentAccess($employeeId): void
    {
        $isAdmin = auth()->check() && strtolower((string) auth()->user()->role) === 'admin';
        if (! $isAdmin) {
            $userKaryawan = Karyawan::where('npk', auth()->user()->npk)->first();
            $targetEmployee = Karyawan::find($employeeId);
            if (! $userKaryawan || ! $targetEmployee) {
                abort(403, 'Access not authorized for other departments.');
            }
            if ($userKaryawan->id !== $targetEmployee->id && $targetEmployee->department_id !== $userKaryawan->department_id) {
                abort(403, 'Access not authorized for other departments.');
            }
        }
    }

    public function updateTarget(Request $request, $id)
    {
        $this->authorizeDepartmentAccess($id);

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

        if ($request->wantsJson()) {
            session()->flash('success', 'Training target updated successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Training target updated successfully.',
                'data' => $training,
            ]);
        }

        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))->with('success', 'Training target updated successfully.');
        }

        return redirect()->back()->with('success', 'Training target updated successfully.');
    }

    public function storeHistory(Request $request, $employee_id)
    {
        $this->authorizeDepartmentAccess($employee_id);

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
            'tanggal_training' => $request->tanggal_training ? Carbon::parse($request->tanggal_training)->format('Y-m-d') : null,
            'eva1_status' => $request->eva1_status ?: null,
            'eva2_status' => $request->eva2_status ?: null,
            'eva3_status' => $request->eva3_status ?: null,
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

        if ($request->wantsJson()) {
            session()->flash('success', 'Training record added successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Training record added successfully.',
                'data' => $riwayat,
            ]);
        }

        return redirect()->back()->with('success', 'Training record added successfully.');
    }

    public function updateHistory(Request $request, $id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);
        $this->authorizeDepartmentAccess($riwayat->id_karyawan);

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
        $riwayat->tanggal_training = $request->tanggal_training ? Carbon::parse($request->tanggal_training)->format('Y-m-d') : null;
        $riwayat->eva1_status = $request->eva1_status ?: null;
        $riwayat->eva2_status = $request->eva2_status ?: null;
        $riwayat->eva3_status = $request->eva3_status ?: null;

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

        if ($request->wantsJson()) {
            session()->flash('success', 'Training record updated successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Training record updated successfully.',
                'data' => $riwayat,
            ]);
        }

        return redirect()->back()->with('success', 'Training record updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);
        $this->authorizeDepartmentAccess($riwayat->id_karyawan);

        $rules = [];
        $data = [];

        if ($request->exists('status_training')) {
            $rules['status_training'] = 'required|in:completed,in_progress,remedial,not_started';
            $data['status_training'] = $request->status_training;
        }

        foreach (['eva1_status', 'eva2_status', 'eva3_status'] as $field) {
            if ($request->exists($field)) {
                $rules[$field] = 'nullable|in:completed,in_progress,remedial,not_started,pending';
                $data[$field] = $request->input($field) ?: null;
            }
        }

        if (! empty($rules)) {
            $request->validate($rules);
            $riwayat->update($data);
        }

        if ($request->wantsJson()) {
            session()->flash('success', 'Status updated successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'data' => $riwayat,
            ]);
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroyHistory($id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);
        $this->authorizeDepartmentAccess($riwayat->id_karyawan);

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

        if (request()->header('HX-Request')) {
            session()->flash('success', 'Training record deleted successfully.');

            return response('', 200)->header('HX-Location', url()->previous());
        }

        return redirect()->back()->with('success', 'Training record deleted successfully.');
    }

    public function uploadEva(Request $request, $id)
    {
        $riwayat = RiwayatTraining::findOrFail($id);
        $this->authorizeDepartmentAccess($riwayat->id_karyawan);

        $request->validate([
            'eva' => 'required|in:eva1,eva2,eva3',
            'file' => 'required|mimes:pdf|max:10240',
        ]);

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

        return redirect()->back()->with('success', 'EVA attachment uploaded successfully.');
    }
}
