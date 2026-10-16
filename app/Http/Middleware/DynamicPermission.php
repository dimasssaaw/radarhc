<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DynamicPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $module
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module)
    {
        // Jika belum login, abaikan (biarkan auth middleware yang menangani)
        if (! auth()->check()) {
            return $next($request);
        }

        // Ambil nama fungsi yang sedang dipanggil di Controller (misal: 'index', 'store', dll)
        $actionMethod = $request->route()->getActionMethod();

        // Peta/Kamus (Map) untuk mengubah nama fungsi menjadi kata kerja permission
        $map = [
            'index' => 'lihat',
            'show' => 'lihat',

            'create' => 'tambah',
            'store' => 'tambah',
            'template' => 'tambah', // Khusus fungsi download template data
            'import' => 'tambah', // Khusus fungsi import data

            'edit' => 'edit',
            'update' => 'edit',

            'destroy' => 'hapus',
        ];

        // Tentukan kata kerja berdasarkan fungsi, default ke null jika tidak ada di map
        $action = $map[$actionMethod] ?? null;

        if ($action) {
            // Merakit nama permission, misal: 'lihat' + '_' + 'karyawan' = 'lihat_karyawan'
            $permissionName = $action.'_'.$module;

            // Cek apakah user punya permission tersebut
            if (! auth()->user()->can($permissionName)) {
                abort(403, "Akses Ditolak. Anda tidak memiliki izin ($permissionName).");
            }
        }

        return $next($request);
    }
}
