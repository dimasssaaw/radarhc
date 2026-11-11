<?php

namespace App\Http\Controllers;

use App\Mail\UserAccountCreated;
use App\Mail\UserPasswordReset;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

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
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        })
            ->when($role, function ($query, $role) {
                return $query->whereRaw('LOWER(role) = ?', [strtolower($role)]);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $dbRoles = Role::orderBy('name')->get();

        return view('user-management.index', compact('users', 'search', 'role', 'status', 'dbRoles'));
    }

    public function create(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        })
            ->when($role, function ($query, $role) {
                return $query->whereRaw('LOWER(role) = ?', [strtolower($role)]);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $dbRoles = Role::orderBy('name')->get();
        $permissions = Permission::all();

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
        $user->syncRoles([$data['role']]);

        // Sync direct permissions if provided
        $permissionsToSync = [];
        if ($request->has('permissions')) {
            foreach ($request->input('permissions') as $module) {
                $permissionsToSync[] = 'lihat_'.$module;
                $permissionsToSync[] = 'tambah_'.$module;
                $permissionsToSync[] = 'edit_'.$module;
                $permissionsToSync[] = 'hapus_'.$module;
            }
            $existingPermissions = Permission::pluck('name')->toArray();
            $permissionsToSync = array_values(array_intersect($permissionsToSync, $existingPermissions));
        }
        $user->syncPermissions($permissionsToSync);

        $msg = 'User added successfully and notification email has been sent.';
        try {
            Mail::to($user->email)->send(new UserAccountCreated($user, $rawPassword));
        } catch (\Exception $e) {
            $msg = 'User added successfully, but email failed to send (Check SMTP configuration).';
        }

        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', route('user-management.index'));
        }

        return redirect()->route('user-management.index')->with('success', $msg);
    }

    public function edit($id, Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('npk', 'like', "%{$search}%");
            });
        })
            ->when($role, function ($query, $role) {
                return $query->whereRaw('LOWER(role) = ?', [strtolower($role)]);
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
            'permissions' => Permission::all(),
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

        if ($user_management->id === auth()->id() && $request->input('status') === 'nonaktif') {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.')->withInput();
        }

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
        $user_management->syncRoles([$data['role']]);

        // Sync direct permissions
        $permissionsToSync = [];
        if ($request->has('permissions')) {
            foreach ($request->input('permissions') as $module) {
                $permissionsToSync[] = 'lihat_'.$module;
                $permissionsToSync[] = 'tambah_'.$module;
                $permissionsToSync[] = 'edit_'.$module;
                $permissionsToSync[] = 'hapus_'.$module;
            }
            $existingPermissions = Permission::pluck('name')->toArray();
            $permissionsToSync = array_values(array_intersect($permissionsToSync, $existingPermissions));
        }
        $user_management->syncPermissions($permissionsToSync);

        $msg = 'User updated successfully.';
        if ($isPasswordChanged) {
            try {
                Mail::to($user_management->email)->send(new UserPasswordReset($user_management, $rawPassword));
                $msg = 'User updated successfully and new password notification has been sent to employee\'s email.';
            } catch (\Exception $e) {
                $msg = 'User updated successfully, but reset password email failed to send (Check SMTP configuration).';
            }
        }

        if ($request->header('HX-Request')) {
            session()->flash('success', $msg);

            return response('', 200)->header('HX-Location', route('user-management.index'));
        }

        return redirect()->route('user-management.index')->with('success', $msg);
    }

    public function destroy($id)
    {
        $user_management = User::findOrFail($id);

        if ($user_management->id === auth()->id()) {
            if (request()->header('HX-Request')) {
                session()->flash('error', 'You cannot delete your own account.');

                return response('', 200)->header('HX-Location', route('user-management.index'));
            }

            return redirect()->route('user-management.index')->with('error', 'You cannot delete your own account.');
        }

        $user_management->delete();

        if (request()->header('HX-Request')) {
            session()->flash('success', 'User deleted successfully.');

            return response('', 200)->header('HX-Location', route('user-management.index'));
        }

        return redirect()->route('user-management.index')->with('success', 'User deleted successfully.');
    }
}
