<?php

namespace App\Http\Controllers;

use App\Mail\UserAccountCreated;
use App\Mail\UserPasswordReset;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('dynamic.permission:user'),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user-management.index', compact('users', 'search', 'role', 'status'));
    }

    public function create(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $dbRoles = Role::orderBy('name')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();

        return view('user-management.create', compact('users', 'search', 'role', 'status', 'dbRoles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npk' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except('modal');
        $rawPassword = $data['password'];
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        // Sync direct permissions if provided
        $permissionsToSync = [];
        if ($request->has('permissions')) {
            foreach ($request->input('permissions') as $module) {
                // Hanya tambahkan permission yang benar-benar ada di database (Spatie butuh nama exact)
                $permissionsToSync[] = 'lihat_'.$module;
                $permissionsToSync[] = 'tambah_'.$module;
                $permissionsToSync[] = 'edit_'.$module;
                $permissionsToSync[] = 'hapus_'.$module;
            }
        }
        $user->syncPermissions($permissionsToSync);

        try {
            Mail::to($user->email)->send(new UserAccountCreated($user, $rawPassword));
        } catch (\Exception $e) {
            return redirect()->route('user-management.index')->with('success', 'User berhasil ditambahkan, namun email gagal dikirim (Cek konfigurasi SMTP).');
        }

        return redirect()->route('user-management.index')->with('success', 'User berhasil ditambahkan dan email telah dikirim.');
    }

    public function edit($id, Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $user_management = User::findOrFail($id);

        return view('user-management.edit', [
            'user' => $user_management,
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'dbRoles' => Role::orderBy('name')->get(),
            'permissions' => \Spatie\Permission\Models\Permission::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user_management = User::findOrFail($id);

        $request->validate([
            'npk' => 'required|string|max:255|unique:users,npk,'.$user_management->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user_management->id,
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->except('modal');
        $isPasswordChanged = false;
        $rawPassword = null;

        if ($request->filled('password')) {
            $rawPassword = $data['password'];
            $data['password'] = bcrypt($data['password']);
            $isPasswordChanged = true;
        } else {
            unset($data['password']);
        }

        $user_management->update($data);

        // Sync direct permissions
        $permissionsToSync = [];
        if ($request->has('permissions')) {
            foreach ($request->input('permissions') as $module) {
                $permissionsToSync[] = 'lihat_'.$module;
                $permissionsToSync[] = 'tambah_'.$module;
                $permissionsToSync[] = 'edit_'.$module;
                $permissionsToSync[] = 'hapus_'.$module;
            }
        }
        $user_management->syncPermissions($permissionsToSync);

        // Send reset email if password was changed
        if ($isPasswordChanged) {
            try {
                Mail::to($user_management->email)->send(new UserPasswordReset($user_management, $rawPassword));

                return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui dan notifikasi password baru telah dikirim ke email karyawan.');
            } catch (\Exception $e) {
                return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui, namun email reset password gagal dikirim (Cek konfigurasi SMTP).');
            }
        }

        return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user_management = User::findOrFail($id);
        $user_management->delete();

        return redirect()->route('user-management.index')->with('success', 'User berhasil dihapus.');
    }
}
