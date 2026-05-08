<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
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
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('email', function ($user) {
                    return $user->email;
                })
                ->addColumn('roles', function ($user) {
                    return $user->getRoleNames()
                        ->map(fn($role) => "<span class='badge bg-primary'>" . str_replace('_', ' ', $role) . "</span>")
                        ->implode(' ');
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status ? '26px' : '2px';
                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($data) {
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
                    
                    return '<div class="d-flex gap-2 justify-content-center">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['roles', 'status', 'action'])
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
            $user->save();
            
            if ($request->has('role')) {
                $user->syncRoles($request->role);
            }
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
            $system_user->update();

            if ($request->has('role')) {
                $system_user->syncRoles($request->role);
            }
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
}
