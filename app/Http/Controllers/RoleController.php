<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('dynamic.permission:role'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('name')->paginate(10);

        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->paginate(10);

        return view('role.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        Role::create(['name' => $validated['name']]);

        $msg = 'Role added successfully.';
        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', route('role.index'));
        }

        return redirect()->route('role.index')->with('success', $msg);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $roles = Role::orderBy('name')->paginate(10);

        return view('role.edit', compact('role', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id,
        ]);

        $role->update(['name' => $validated['name']]);

        $msg = 'Role updated successfully.';
        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', route('role.index'));
        }

        return redirect()->route('role.index')->with('success', $msg);
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion if used? There is no relation set up yet, but we can just delete
        $role->delete();

        $msg = 'Role deleted successfully.';
        if (request()->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', route('role.index'));
        }

        return redirect()->route('role.index')->with('success', $msg);
    }
}
