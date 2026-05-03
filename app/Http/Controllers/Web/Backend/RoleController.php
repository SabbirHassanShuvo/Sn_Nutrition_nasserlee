<?php

namespace App\Http\Controllers\Web\Backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request){
        $roles = Role::all();
        if($request->ajax()){
            return DataTables::of($roles)
                ->addIndexColumn() // generates the index
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('backend.role.edit',$row->id).'" class="btn btn-sm btn-primary">Edit</a> ';
                    return $btn;
                })
                ->rawColumns(['action']) // allow HTML buttons
                ->make(true);
        }
        return view("backend.layout.roles.index",compact("roles"));
    }

    public function create(Request $request){
        $permissions = Permission::all();
        return view('backend.layout.roles.create', compact('permissions'));
    }

    
    public function edit(Role $role){
        $permissions = Permission::all();
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        return view('backend.layout.roles.form', compact('role', 'rolePermissions', 'permissions'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $role = Role::create($request->only('name'));
        $role->syncPermissions($permissions);
        dd($role->getAllPermissions());
        return redirect()->route('backend.role.index')->with('success','Role created succesffully');
    }

    public function update(Request $request, Role $role){
        // dd($request->all());
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);
        $permissions = Permission::whereIn('name', $request->permissions)->get();
        
        $role->syncPermissions($permissions);
        // dd($role->getAllPermissions());

        return redirect()->route('backend.role.index')->with('success', 'Role Updated Successfully');
    }

    public function destroy(Role $role){
        if($role->name == 'super_admin'){
            return redirect()->route('backend.role.index')->with('error', 'can not delete super admin');
        }
        $role->delete();
        return redirect()->route('backend.role.index')->with('success', 'role successfully deleted');

    }
}
