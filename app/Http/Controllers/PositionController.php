<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PositionController extends Controller implements HasMiddleware
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
        $positions = Position::orderBy('nama_jabatan')->paginate(10);

        return view('position.index', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|unique:positions,nama_jabatan',
        ]);

        Position::create($validated);

        $msg = 'Position added successfully.';
        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)
                ->header('HX-Location', route('position.index'));
        }

        return redirect()->route('position.index')->with('success', $msg);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'nama_jabatan' => 'required|string|unique:positions,nama_jabatan,'.$position->id,
        ]);

        $position->update($validated);

        $msg = 'Position updated successfully.';
        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)
                ->header('HX-Location', route('position.index'));
        }

        return redirect()->route('position.index')->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);

        // Prevent deletion if employees are still associated with this position
        if ($position->karyawan()->count() > 0) {
            $errorMsg = 'Position cannot be deleted because there are still employees associated with it.';
            if (request()->header('HX-Request')) {
                session()->flash('error', $errorMsg);

                return response('', 200)
                    ->header('HX-Location', route('position.index'));
            }

            return redirect()->route('position.index')->with('error', $errorMsg);
        }

        $position->delete();

        $msg = 'Position deleted successfully.';
        if (request()->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)
                ->header('HX-Location', route('position.index'));
        }

        return redirect()->route('position.index')->with('success', $msg);
    }
}
