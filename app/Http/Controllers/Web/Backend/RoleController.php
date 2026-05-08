<?php

namespace App\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::where('name', '!=', 'super_admin')->get();
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('permissions', function ($role) {
                    return $role->permissions->map(function($permission) {
                        return '<span class="badge bg-soft-info text-info me-1">' . $permission->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($role) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="' . route('backend.role.edit', $role->id) . '" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.role.destroy', $role->id) . '\')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }
        return view("backend.layout.roles.index");
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($item) {
            // Group by the first word of the permission name (e.g. 'user_management' -> 'user')
            return explode('_', $item->name)[0];
        });
        return view('backend.layout.roles.form', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->syncPermissions($request->permissions);

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
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('backend.role.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        try {
            if ($role->name == 'super_admin') {
                return response()->json(['success' => false, 'message' => 'Super Admin cannot be deleted.']);
            }
            $role->delete();
            return response()->json(['success' => true, 'message' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete role.']);
        }
    }
}
