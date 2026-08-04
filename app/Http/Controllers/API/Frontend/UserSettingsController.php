<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserSettingsController extends BaseController
{
    public function __construct()
    {
        // Ensure notification preference columns exist in profiles table
        if (Schema::hasTable('profiles')) {
            $columns = [
                'order_updates' => true,
                'promotions_offers' => true,
                'payout_confirmations' => true,
                'product_launches_tips' => true,
                'push_notifications' => true,
            ];

            Schema::table('profiles', function (Blueprint $table) use ($columns) {
                foreach ($columns as $column => $default) {
                    if (!Schema::hasColumn('profiles', $column)) {
                        $table->boolean($column)->default($default);
                    }
                }
            });
        }
    }

    /**
     * Get user settings overview (profile summary & notification preferences).
     */
    public function index()
    {
        try {
            $user = User::with('profile')->find(Auth::id());

            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }

            $profile = $user->profile;
            if (!$profile) {
                $profile = Profile::create(['user_id' => $user->id]);
            }

            $avatarUrl = null;
            if (!empty($profile->avatar)) {
                if (filter_var($profile->avatar, FILTER_VALIDATE_URL) || str_starts_with($profile->avatar, 'http://') || str_starts_with($profile->avatar, 'https://')) {
                    $avatarUrl = $profile->avatar;
                } else {
                    $avatarUrl = asset(ltrim($profile->avatar, '/'));
                }
            }

            $data = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $avatarUrl,
                ],
                'notifications' => [
                    'order_updates' => (bool) ($profile->order_updates ?? true),
                    'promotions_offers' => (bool) ($profile->promotions_offers ?? true),
                    'payout_confirmations' => (bool) ($profile->payout_confirmations ?? true),
                    'product_launches_tips' => (bool) ($profile->product_launches_tips ?? true),
                    'push_notifications' => (bool) ($profile->push_notifications ?? true),
                ]
            ];

            return response()->json([
                'status' => true,
                'message' => 'User settings retrieved successfully',
                'data' => $data
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch user settings: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Update User Password.
     */
    public function updatePassword(Request $request)
    {
        // Support both naming conventions (current_password / old_password, password / new_password)
        $currentPassword = $request->input('current_password') ?? $request->input('old_password');
        $newPassword = $request->input('new_password') ?? $request->input('password');
        $confirmPassword = $request->input('confirm_new_password') ?? $request->input('password_confirmation');

        $requestData = [
            'current_password' => $currentPassword,
            'password' => $newPassword,
            'password_confirmation' => $confirmPassword,
        ];

        $validator = Validator::make($requestData, [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 8 characters.',
            'password.confirmed' => 'New password and confirm password do not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::find(Auth::id());

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found or unauthorized'
                ], 401);
            }

            if (!Hash::check($currentPassword, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password does not match our records.']]
                ], 400);
            }

            $user->password = Hash::make($newPassword);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification preferences.
     */
    public function getNotifications()
    {
        try {
            $user = User::with('profile')->find(Auth::id());
            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }

            $profile = $user->profile;
            if (!$profile) {
                $profile = Profile::create(['user_id' => $user->id]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Notification preferences retrieved successfully',
                'data' => [
                    'order_updates' => (bool) ($profile->order_updates ?? true),
                    'promotions_offers' => (bool) ($profile->promotions_offers ?? true),
                    'payout_confirmations' => (bool) ($profile->payout_confirmations ?? true),
                    'product_launches_tips' => (bool) ($profile->product_launches_tips ?? true),
                    'push_notifications' => (bool) ($profile->push_notifications ?? true),
                ]
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch notification preferences: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_updates' => 'nullable|boolean',
            'promotions_offers' => 'nullable|boolean',
            'payout_confirmations' => 'nullable|boolean',
            'product_launches_tips' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::with('profile')->find(Auth::id());
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found or unauthorized'
                ], 401);
            }

            $profile = $user->profile;
            if (!$profile) {
                $profile = Profile::create(['user_id' => $user->id]);
            }

            if ($request->has('order_updates')) {
                $profile->order_updates = filter_var($request->order_updates, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('promotions_offers')) {
                $profile->promotions_offers = filter_var($request->promotions_offers, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('payout_confirmations')) {
                $profile->payout_confirmations = filter_var($request->payout_confirmations, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('product_launches_tips')) {
                $profile->product_launches_tips = filter_var($request->product_launches_tips, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('push_notifications')) {
                $profile->push_notifications = filter_var($request->push_notifications, FILTER_VALIDATE_BOOLEAN);
            }

            $profile->save();

            return response()->json([
                'status' => true,
                'message' => 'Notification preferences updated successfully',
                'data' => [
                    'order_updates' => (bool) $profile->order_updates,
                    'promotions_offers' => (bool) $profile->promotions_offers,
                    'payout_confirmations' => (bool) $profile->payout_confirmations,
                    'product_launches_tips' => (bool) $profile->product_launches_tips,
                    'push_notifications' => (bool) $profile->push_notifications,
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update notification preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user account permanently.
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = User::find(Auth::id());

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found or unauthorized'
                ], 401);
            }

            // Delete associated profile & data
            if ($user->profile) {
                $user->profile->delete();
            }

            // Delete user addresses
            $user->addresses()->delete();

            // Delete user
            $user->delete();

            // Logout/invalidate token
            try {
                auth('api')->logout();
            } catch (Exception $authEx) {
                // Ignore logout exception if already invalid
            }

            return response()->json([
                'status' => true,
                'message' => 'Your account and associated data have been deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete account: ' . $e->getMessage()
            ], 500);
        }
    }
}
