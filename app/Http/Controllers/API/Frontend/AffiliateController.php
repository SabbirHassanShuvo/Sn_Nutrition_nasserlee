<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AffiliateLink;
use App\Models\Order;
use App\Models\PayoutHistory;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    public function generateLink(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = auth()->user();
        
        // Check if link already exists for this product
        $existingLink = AffiliateLink::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingLink) {
            $existingLink->load('product');
            return response()->json([
                'message' => 'Tracking link already exists for this product.',
                'tracking_url' => $existingLink->tracking_url,
                'data' => $existingLink
            ]);
        }

        $trackingCode = Str::random(10);
        
        $link = AffiliateLink::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'tracking_code' => $trackingCode,
            'status' => 'active'
        ]);

        $link->load('product');

        return response()->json([
            'message' => 'Tracking link generated successfully.',
            'tracking_url' => $link->tracking_url,
            'data' => $link
        ]);
    }

    public function getDashboardStats()
    {
        $user = auth()->user();
        $partnerProfile = $user->partnerProfile;

        if (!$partnerProfile) {
            return response()->json(['message' => 'Partner profile not found.'], 404);
        }

        $thisMonthEarnings = Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('commission_amount');

        return response()->json([
            'lifetime_earnings' => $partnerProfile->lifetime_earnings,
            'this_month_earnings' => $thisMonthEarnings,
            'pending_payout' => $partnerProfile->pending_payout,
            'last_paid_amount' => $partnerProfile->last_paid_amount,
        ]);
    }

    public function getLinks()
    {
        $links = AffiliateLink::with('product:id,name,main_image,price')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($links);
    }

    public function getOrders(Request $request)
    {
        $orders = Order::with(['items.product:id,name,main_image', 'user:id,name'])
            ->whereHas('affiliateLink', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function getPayoutHistory()
    {
        $history = PayoutHistory::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json($history);
    }

    public function getTiersInfo()
    {
        $user = auth()->user();
        $partnerProfile = $user->partnerProfile;

        $thisMonthEarnings = Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('commission_amount');

        $tiers = [
            'bronze' => ['min' => 0, 'bonus' => 0],
            'silver' => ['min' => 1500, 'bonus' => 2],
            'gold' => ['min' => 3000, 'bonus' => 5],
            'platinum' => ['min' => 6000, 'bonus' => 8],
        ];

        // Determine current tier and next tier
        $currentTier = $partnerProfile ? ($partnerProfile->current_tier ?: 'bronze') : 'bronze';
        $nextTier = null;
        $nextTierMin = 0;

        if ($currentTier == 'bronze') { $nextTier = 'silver'; $nextTierMin = 1500; }
        elseif ($currentTier == 'silver') { $nextTier = 'gold'; $nextTierMin = 3000; }
        elseif ($currentTier == 'gold') { $nextTier = 'platinum'; $nextTierMin = 6000; }

        return response()->json([
            'current_tier' => $currentTier,
            'this_month_earnings' => $thisMonthEarnings,
            'next_tier' => $nextTier,
            'next_tier_min' => $nextTierMin,
            'progress' => $nextTierMin > 0 ? ($thisMonthEarnings / $nextTierMin) * 100 : 100,
            'tiers_config' => $tiers
        ]);
    }

    public function getEarningsChart()
    {
        $user = auth()->user();
        
        $earnings = Order::select(
                DB::raw('SUM(commission_amount) as amount'),
                DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period")
            )
            ->whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('period', 'month')
            ->orderBy('period', 'asc')
            ->get();

        return response()->json($earnings);
    }
}
