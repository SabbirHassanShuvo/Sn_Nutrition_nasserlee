<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\UserSuspensionMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class AppUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('profile')->where('is_admin_user', 0)->latest();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($user) {
                    return '<div class="form-check"><input class="form-check-input user-checkbox" type="checkbox" value="' . $user->id . '"></div>';
                })
                ->addColumn('user_info', function ($user) {
                    $avatar = $user->profile->avatar ?? null;
                    $img = $avatar ? asset($avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name);
                    return '<div class="d-flex align-items-center user-info-box">
                        <img src="' . $img . '" alt="" class="rounded-circle avatar-xs shadow-sm me-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fs-14 fw-bold">' . $user->name . '</h6>
                        </div>
                    </div>';
                })
                ->addColumn('email', function ($user) {
                    return '<span class="text-muted fs-13">' . $user->email . '</span>';
                })
                ->addColumn('role_badge', function ($user) {
                    $class = $user->role == 'health_professional' ? 'bg-soft-primary text-primary' : 'bg-soft-success text-success';
                    return '<span class="badge ' . $class . ' text-uppercase">' . str_replace('_', ' ', $user->role) . '</span>';
                })
                ->addColumn('status', function ($user) {
                    $backgroundColor  = $user->status ? '#4CAF50' : '#f06548'; // Red if suspended
                    $sliderTranslateX = $user->status ? '26px' : '2px';
                    return getStatusHTML($user, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($user) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" onclick="viewUser(' . $user->id . ')" class="btn btn-soft-primary btn-sm" title="View Profile">
                                <i class="mdi mdi-eye fs-14"></i>
                            </button>
                            <button type="button" onclick="deleteData(\'' . route('backend.app-user.destroy', $user->id) . '\')" class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'user_info', 'email', 'role_badge', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.users.app_users.index");
    }

    public function show($id)
    {
        $user = User::with(['profile', 'partnerProfile'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            $user = User::findOrFail($id);
            $user->status = !$user->status;
            $user->suspension_reason = $request->reason;
            $user->save();

            // Send notification email (queued)
            Mail::to($user->email)->queue(new UserSuspensionMail($user, $request->reason, !$user->status));

            return response()->json([
                'success' => true, 
                'message' => $user->status ? 'User account activated successfully.' : 'User account suspended and notified.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Operation failed: ' . $e->getMessage()]);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        try {
            User::whereIn('id', $request->ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected users deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Deletion failed.']);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete user.']);
        }
    }
}
 