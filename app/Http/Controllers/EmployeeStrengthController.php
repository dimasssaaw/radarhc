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

        return back()->with('success', 'Data Strength & Weakness berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'strength' => 'required|string|max:255',
            'weakness' => 'required|string|max:255',
        ]);

        $item = EmployeeStrength::findOrFail($id);
        $item->update($request->only(['strength', 'weakness']));

        return back()->with('success', 'Data Strength & Weakness berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = EmployeeStrength::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Data Strength & Weakness berhasil dihapus.');
    }
}
