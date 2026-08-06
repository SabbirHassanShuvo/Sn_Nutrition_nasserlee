<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SystemUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('is_admin_user', 1)->orderBy('id', 'desc')->get();
            return DataTables::of($users)
                ->addColumn('checkbox', function ($user) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $user->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('email', function ($user) {
                    return $user->email;
                })
                ->addColumn('roles', function ($user) {
                    $rolesBadges = $user->getRoleNames()
                        ->map(fn($role) => "<span class='badge bg-primary text-uppercase me-1'>" . str_replace('_', ' ', $role) . "</span>")
                        ->implode(' ');

                    if (empty($rolesBadges)) {
                        $rolesBadges = "<span class='badge bg-secondary text-uppercase me-1'>NO ROLE</span>";
                    }

                    $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
                    if (count($directPermissions) > 0) {
                        $rolesBadges .= "<br><span class='badge bg-soft-warning text-warning fs-10 text-uppercase mt-1'>" . count($directPermissions) . " Direct Overrides</span>";
                    }
                    return $rolesBadges;
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status ? '26px' : '2px';
                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($data) {
                    $accessBtn = '<button onclick="manageAccess(' . $data->id . ')" type="button" class="btn btn-soft-success btn-sm" data-bs-toggle="tooltip" title="Manage Access">
                        <i class="mdi mdi-shield-account"></i>
                    </button>';

                    $editBtn = '<button onclick="editSystemUser(' . $data->id . ')" type="button" class="btn btn-soft-info btn-sm">
                        <i class="mdi mdi-pencil"></i>
                    </button>';
                    
                    $deleteBtn = '';
                    // Hide delete for super_admin role and the user themselves
                    if (!$data->hasRole('super_admin') && $data->id != Auth::user()->id) {
                        $deleteBtn = '<button type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-soft-danger btn-sm del">
                            <i class="mdi mdi-delete"></i>
                        </button>';
                    }
                    
                    return '<div class="d-flex gap-2 justify-content-center">' . $accessBtn . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['checkbox', 'roles', 'status', 'action'])
                ->make(true);
        }
        $roles = Role::all()->pluck('name')->toArray();
        return view('backend.layout.users.system_users.index', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->is_admin_user = 1;
            $user->password = bcrypt($data['password']);
            
            if ($request->has('role') && !empty($request->role)) {
                $user->role = $request->role[0];
            }
            $user->save();
            
            if ($request->has('role')) {
                $user->syncRoles($request->role);
            }
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            return response()->json(['success' => true, 'message' => 'System User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit(User $system_user)
    {
        $userRoles = $system_user->getRoleNames()->toArray();
        return response()->json([
            'success' => true,
            'data' => $system_user,
            'roles' => $userRoles
        ]);
    }

    public function update(Request $request, User $system_user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $system_user->id,
            'password' => ['nullable', new PasswordRule],
        ]);

        try {
            if (!is_null($request['password'])) {
                $system_user->password = bcrypt($request['password']);
            }
            $system_user->name = $request->name;
            $system_user->email = $request->email;
            
            if ($request->has('role') && !empty($request->role)) {
                $system_user->role = $request->role[0];
            } else {
                $system_user->role = 'user';
            }
            $system_user->save();

            $system_user->syncRoles($request->input('role', []));
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            
            return response()->json(['success' => true, 'message' => 'System User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function status($id)
    {
        try {
            $system_user = User::findOrFail($id);
            $system_user->status = !$system_user->status;
            $system_user->update();
            return response()->json(['status' => 'success', 'message' => 'Status Changed Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Status Change Failed: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $system_user)
    {
        try {
            if ($system_user->hasRole('super_admin')) {
                return response()->json(['status' => 'error', 'message' => 'Cannot delete Super Admin.']);
            }
            if ($system_user->id == Auth::user()->id) {
                return response()->json(['status' => 'error', 'message' => 'Cannot delete your own account.']);
            }
            $system_user->delete();
            return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'User delete Failed: ' . $e->getMessage()]);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            // Filter out super_admin and currently authenticated user
            $users = User::whereIn('id', $ids)->get();
            $deleteIds = [];
            foreach ($users as $user) {
                if (!$user->hasRole('super_admin') && $user->id != Auth::user()->id) {
                    $deleteIds[] = $user->id;
                }
            }
            if (!empty($deleteIds)) {
                User::whereIn('id', $deleteIds)->delete();
                return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'Cannot delete selected users.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }

    public function getUserPermissions($id)
    {
        try {
            $user = User::findOrFail($id);
            $userRoles = $user->getRoleNames()->toArray();
            
            // Get all role names except super_admin (or include all)
            $allRoles = Role::all()->pluck('name')->toArray();
            
            // Collect inherited permissions
            $inheritedPermissions = [];
            if (!empty($userRoles)) {
                $inheritedPermissions = \Spatie\Permission\Models\Permission::role($userRoles)->pluck('name')->toArray();
            }
            
            $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
            
            // Get all permissions
            $allPermissions = \Spatie\Permission\Models\Permission::all();
            
            $formattedPermissions = $allPermissions->map(function ($permission) use ($inheritedPermissions, $directPermissions) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'group' => explode('_', $permission->name)[0],
                    'is_inherited' => in_array($permission->name, $inheritedPermissions),
                    'is_direct' => in_array($permission->name, $directPermissions),
                ];
            });

            // Map each role to its permissions
            $rolePermissionsMap = [];
            $rolesModels = Role::all();
            foreach ($rolesModels as $roleModel) {
                $rolePermissionsMap[$roleModel->name] = $roleModel->permissions()->pluck('name')->toArray();
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $userRoles,
                ],
                'roles' => $allRoles,
                'role_permissions_map' => $rolePermissionsMap,
                'permissions' => $formattedPermissions
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }
    }

    public function syncUserPermissions(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // 1. Sync Roles
            $roles = $request->input('roles', []);
            if (!empty($roles)) {
                $user->role = $roles[0];
            } else {
                $user->role = 'user';
            }
            $user->save();
            $user->syncRoles($roles);
            
            // 2. Sync Direct Permissions
            $permissions = $request->input('permissions', []);
            
            // Filter inherited permissions
            $inheritedPermissions = [];
            if (!empty($roles)) {
                $inheritedPermissions = \Spatie\Permission\Models\Permission::role($roles)->pluck('name')->toArray();
            }
            $directPermissions = array_diff($permissions, $inheritedPermissions);
            
            $user->syncPermissions($directPermissions);
            
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return response()->json(['success' => true, 'message' => 'Access permissions updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to sync permissions: ' . $e->getMessage()]);
        }
    }
}
