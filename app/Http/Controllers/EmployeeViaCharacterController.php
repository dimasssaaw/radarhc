<?php

namespace App\Http\Controllers;

use App\Models\EmployeeViaCharacter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeViaCharacterController extends Controller implements HasMiddleware
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
            'overuse_strength' => 'required|string|max:255',
            'underuse_strength' => 'required|string|max:255',
        ]);

        EmployeeViaCharacter::create($request->all());

        return back()->with('success', 'Data Via Character berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'overuse_strength' => 'required|string|max:255',
            'underuse_strength' => 'required|string|max:255',
        ]);

        $item = EmployeeViaCharacter::findOrFail($id);
        $item->update($request->only(['overuse_strength', 'underuse_strength']));

        return back()->with('success', 'Data Via Character berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = EmployeeViaCharacter::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Data Via Character berhasil dihapus.');
    }
}
