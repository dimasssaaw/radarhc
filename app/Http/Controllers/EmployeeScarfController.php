<?php

namespace App\Http\Controllers;

use App\Models\EmployeeScarf;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeScarfController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('dynamic.permission:karyawan'),
        ];
    }

    public function update(Request $request, $id_karyawan)
    {
        $request->validate([
            'status' => 'required|numeric|min:0|max:100',
            'certainty' => 'required|numeric|min:0|max:100',
            'autonomy' => 'required|numeric|min:0|max:100',
            'relatedness' => 'required|numeric|min:0|max:100',
            'fairness' => 'required|numeric|min:0|max:100',
        ]);

        EmployeeScarf::updateOrCreate(
            ['id_karyawan' => $id_karyawan],
            $request->only(['status', 'certainty', 'autonomy', 'relatedness', 'fairness'])
        );

        return back()->with('success', 'SCARF Profile berhasil diperbarui.');
    }
}
