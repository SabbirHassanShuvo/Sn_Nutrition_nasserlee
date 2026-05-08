<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $data['total_users'] = \App\Models\User::where('is_admin_user', 0)->count();
        $data['total_admins'] = \App\Models\User::where('is_admin_user', 1)->count();
        $data['total_roles'] = \Spatie\Permission\Models\Role::count();
        $data['health_professionals'] = \App\Models\User::where('role', 'health_professional')->count();
        $data['customers'] = \App\Models\User::where('role', 'user')->count();
        $data['total_products'] = \App\Models\Product::count();

        return view("backend.index", $data);
    }
}
