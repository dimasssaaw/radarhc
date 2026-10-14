<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DepartmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('dynamic.permission:departemen'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('nama_departemen')->paginate(10);

        return view('department.index', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_departemen' => 'required|string|unique:departments,nama_departemen',
        ]);

        Department::create($validated);

        return redirect()->route('department.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);

        return view('department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'nama_departemen' => 'required|string|unique:departments,nama_departemen,'.$department->id,
        ]);

        $department->update($validated);

        return redirect()->route('department.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        // Prevent deletion if employees are still associated with this department
        if ($department->karyawan()->count() > 0) {
            return redirect()->route('department.index')->with('error', 'Departemen tidak dapat dihapus karena masih ada karyawan yang terkait.');
        }

        $department->delete();

        return redirect()->route('department.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
