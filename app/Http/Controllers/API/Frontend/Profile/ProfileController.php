<?php

namespace App\Http\Controllers\Api\Frontend\Profile;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLink;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function sendResponse($message, $result)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
 
        return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    public function detailsProfile(Request $request)
    {
        $user           = User::with('partnerProfile')->find(auth()->id());
        $totalOrder     = Order::whereIn('affiliate_link_id', $user->affiliateLinks()->select('id'))->count();
        $completedOrder = Order::whereIn('affiliate_link_id', $user->affiliateLinks()->select('id'))->where('status', 'delivered')->count();
        $conversionRate = $totalOrder > 0 ? round(($completedOrder / $totalOrder) * 100, 1) : 0;

        $data = [
            'user' => $user,
            'count' => [
                'total_order'       => $totalOrder,
                'active_link'       => AffiliateLink::where('user_id', $user->id)->where('status', 'active')->count(),
                'total_click'       => AffiliateLink::where('user_id', $user->id)->sum('clicks_count'),
                // 'total_Conversion'  => $completedOrder,
                'conversion_rate'   => $conversionRate,
            ]
        ];
        return $this->sendResponse('User profile details.', $data);
    }
    public function updateProfile(Request $request)
    {
       
        $user = auth()->user();

        $request->validate([
            'name'              => 'nullable|string|max:255',
            'email'             => 'nullable|email|unique:users,email,' . $user->id,
            'professional_role' => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:50',
            'location'          => 'nullable|string|max:255',
            'website'           => 'nullable|string|max:255',
            'avatar'            => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif,svg,webp,ico,bmp,tiff|max:2048',
        ]);

        
        $user->update([
            'name'  => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ]);


        $partnerProfile = $user->partnerProfile;
        $avatarPath = $partnerProfile ? $partnerProfile->getRawOriginal('avatar') : null;

         
        if ($request->hasFile('avatar')) {
            
            if ($avatarPath) {
                fileDelete($avatarPath);
            }
            $avatarPath = fileUpload($request->file('avatar'), 'partner/avatar');
        }

        $user->partnerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'professional_role' => $request->professional_role,
                'phone'             => $request->phone,
                'location'          => $request->location,
                'website'           => $request->website,
                'avatar'            => $avatarPath,
            ]
        );

        $updatedUser = User::with('partnerProfile')->find($user->id);

        return $this->sendResponse('Profile updated successfully.', $updatedUser);
    }


}
