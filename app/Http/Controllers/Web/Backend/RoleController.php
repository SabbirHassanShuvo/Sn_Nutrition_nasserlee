<?php

namespace App\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::where('name', '!=', 'super_admin')->get();
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('name', function ($role) {
                    return '<div class="d-flex align-items-center"><i class="ri-shield-user-fill text-primary me-2 fs-16"></i> <span class="fw-bold text-dark fs-14">' . str_replace('_', ' ', ucwords($role->name)) . '</span></div>';
                })
                ->addColumn('permissions', function ($role) {
                    if ($role->permissions->count() == 0) {
                        return '<span class="badge bg-soft-secondary text-muted fs-11">No permissions assigned</span>';
                    }
                    return '<div class="d-flex flex-wrap gap-1" style="max-height: 80px; overflow-y: auto;">' . $role->permissions->map(function($permission) {
                        return '<span class="badge bg-soft-info text-info fs-11 py-1 px-2 text-lowercase fw-normal">' . $permission->name . '</span>';
                    })->implode(' ') . '</div>';
                })
                ->addColumn('action', function ($role) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="' . route('backend.role.edit', $role->id) . '" class="btn btn-soft-info btn-sm px-2 py-1" data-bs-toggle="tooltip" title="Edit Role & Permissions">
                                <i class="ri-pencil-line fs-14 me-1"></i> Edit
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.role.destroy', $role->id) . '\')" class="btn btn-soft-danger btn-sm px-2 py-1" data-bs-toggle="tooltip" title="Delete Role">
                                <i class="ri-delete-bin-line fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['name', 'permissions', 'action'])
                ->make(true);
        }
        return view("backend.layout.roles.index");
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('_', $item->name)[0];
        });
        return view('backend.layout.roles.form', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('backend.role.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        if ($role->name == 'super_admin') {
            return redirect()->route('backend.role.index')->with('error', 'Super Admin cannot be edited.');
        }

        $permissions = Permission::all()->groupBy(function($item) {
            return explode('_', $item->name)[0];
        });
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        return view('backend.layout.roles.form', compact('role', 'rolePermissions', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('backend.role.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        try {
            if ($role->name == 'super_admin') {
                return response()->json(['success' => false, 'message' => 'Super Admin cannot be deleted.']);
            }
            $role->delete();

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete role.']);
        }
    }

    public function permissionIndex(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::all();
            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('name', function ($permission) {
                    return '<div class="d-flex align-items-center"><i class="ri-key-2-line text-warning me-2 fs-15"></i> <span class="fw-medium text-dark">' . $permission->name . '</span></div>';
                })
                ->addColumn('group', function ($permission) {
                    return '<span class="badge bg-soft-primary text-primary text-uppercase fs-11 py-1 px-2">' . explode('_', $permission->name)[0] . '</span>';
                })
                ->addColumn('action', function ($permission) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" onclick="previewPermission(' . $permission->id . ')" class="btn btn-soft-success btn-sm px-2" data-bs-toggle="tooltip" title="View Users & Roles">
                                <i class="ri-eye-line fs-14"></i>
                            </button>
                            <button type="button" onclick="editPermission(' . $permission->id . ', \'' . $permission->name . '\')" class="btn btn-soft-info btn-sm px-2" data-bs-toggle="tooltip" title="Edit Name">
                                <i class="ri-pencil-line fs-14"></i>
                            </button>
                            <button type="button" onclick="deletePermission(\'' . route('backend.permission.destroy', $permission->id) . '\')" class="btn btn-soft-danger btn-sm px-2" data-bs-toggle="tooltip" title="Delete">
                                <i class="ri-delete-bin-line fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['name', 'group', 'action'])
                ->make(true);
        }
        return redirect()->route('backend.role.index');
    }

    public function permissionStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Clean permission name: lowercase, convert spaces/hyphens to underscore
            $name = strtolower(str_replace([' ', '-'], '_', trim($request->name)));

            // Re-validate unique clean name
            if (Permission::where('name', $name)->exists()) {
                return response()->json(['success' => false, 'message' => 'Permission already exists.']);
            }

            $permission = Permission::create(['name' => $name, 'guard_name' => 'web']);

            // Auto-assign to super_admin role if exists
            $superAdmin = Role::where('name', 'super_admin')->first();
            if ($superAdmin) {
                $superAdmin->givePermissionTo($permission);
            }

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json(['success' => true, 'message' => 'Permission created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create permission: ' . $e->getMessage()]);
        }
    }

    public function permissionDestroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json(['success' => true, 'message' => 'Permission deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete permission.']);
        }
    }

    public function permissionShow($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $roles = $permission->roles()->pluck('name')->toArray();
            
            // Get all users who have this permission (either via role or direct permission)
            $users = \App\Models\User::permission($permission->name)->get(['id', 'name', 'email', 'avatar']);

            // If super_admin role exists, super_admin users also have all permissions
            $superAdminUsers = \App\Models\User::role('super_admin')->get(['id', 'name', 'email', 'avatar']);

            $allUsers = $users->concat($superAdminUsers)->unique('id')->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'roles' => $roles,
                    'users' => $allUsers
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Permission not found: ' . $e->getMessage()]);
        }
    }

    public function permissionUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $name = strtolower(str_replace([' ', '-'], '_', trim($request->name)));

            if (Permission::where('name', $name)->where('id', '!=', $id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Permission already exists.']);
            }

            $permission->update(['name' => $name]);

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json(['success' => true, 'message' => 'Permission updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update permission: ' . $e->getMessage()]);
        }
    }
}
