<?php

namespace App\Http\Controllers;

use App\Models\EmployeeStrength;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeStrengthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('dynamic.permission:karyawan'),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
            'strength' => 'required|string|max:255',
            'weakness' => 'required|string|max:255',
        ]);

        EmployeeStrength::create($request->all());

        if ($request->header('HX-Request')) {
            session()->flash('success', 'Strength & Weakness data added successfully.');

            return response('', 200)->header('HX-Location', url()->previous());
        }

        return back()->with('success', 'Strength & Weakness data added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'strength' => 'required|string|max:255',
            'weakness' => 'required|string|max:255',
        ]);

        $item = EmployeeStrength::findOrFail($id);
        $item->update($request->only(['strength', 'weakness']));

        if ($request->header('HX-Request')) {
            session()->flash('success', 'Strength & Weakness data updated successfully.');

            return response('', 200)->header('HX-Location', url()->previous());
        }

        return back()->with('success', 'Strength & Weakness data updated successfully.');
    }

    public function destroy($id)
    {
        $item = EmployeeStrength::findOrFail($id);
        $item->delete();

        if (request()->header('HX-Request')) {
            session()->flash('success', 'Strength & Weakness data deleted successfully.');

            return response('', 200)->header('HX-Location', url()->previous());
        }

        return back()->with('success', 'Strength & Weakness data deleted successfully.');
    }
}
