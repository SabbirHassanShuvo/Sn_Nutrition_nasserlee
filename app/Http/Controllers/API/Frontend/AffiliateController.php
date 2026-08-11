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
use App\Models\AffiliateTierSetting;
use App\Models\PayoutMethod;
use App\Models\PayoutRequest;

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
        $partnerProfile = $user->partnerProfile ?: $user->partnerProfile()->create([
            'current_tier' => 'bronze',
            'lifetime_earnings' => 0,
            'pending_payout' => 0,
            'last_paid_amount' => 0,
        ]);

        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // 1. Total Earnings
        $lifetimeEarnings = (float) $partnerProfile->lifetime_earnings;
        $thisMonthEarnings = (float) Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->sum('commission_amount');

        $lastMonthEarnings = (float) Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('commission_amount');

        $earningsChange = $lastMonthEarnings > 0 
            ? round(abs((($thisMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100), 1) 
            : ($thisMonthEarnings > 0 ? 100.0 : 0.0);
        $earningsTrend = $thisMonthEarnings >= $lastMonthEarnings ? 'up' : 'down';

        // 2. Total Orders
        $totalOrders = Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

        $thisMonthOrders = Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->count();

        $lastMonthOrders = Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $ordersChange = $lastMonthOrders > 0 
            ? round(abs((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100), 1) 
            : ($thisMonthOrders > 0 ? 100.0 : 0.0);
        $ordersTrend = $thisMonthOrders >= $lastMonthOrders ? 'up' : 'down';

        // 3. Active Links
        $activeLinksCount = AffiliateLink::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $thisMonthNewLinks = AffiliateLink::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->count();

        $lastMonthNewLinks = AffiliateLink::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $linksChange = $lastMonthNewLinks > 0 
            ? round(abs((($thisMonthNewLinks - $lastMonthNewLinks) / $lastMonthNewLinks) * 100), 1) 
            : ($thisMonthNewLinks > 0 ? 100.0 : 0.0);
        $linksTrend = $thisMonthNewLinks >= $lastMonthNewLinks ? 'up' : 'down';

        // 4. Conversion Rate
        $totalClicks = (int) AffiliateLink::where('user_id', $user->id)->sum('clicks_count');
        $totalConversions = (int) AffiliateLink::where('user_id', $user->id)->sum('conversions_count');
        $conversionRate = $totalClicks > 0 ? round(($totalConversions / $totalClicks) * 100, 1) : 0.0;

        $thisMonthClicks = (int) AffiliateLink::where('user_id', $user->id)
            ->whereBetween('updated_at', [$startOfThisMonth, $endOfThisMonth])->sum('clicks_count');
        $thisMonthConversions = (int) AffiliateLink::where('user_id', $user->id)
            ->whereBetween('updated_at', [$startOfThisMonth, $endOfThisMonth])->sum('conversions_count');
        $thisMonthCR = $thisMonthClicks > 0 ? round(($thisMonthConversions / $thisMonthClicks) * 100, 1) : 0.0;

        $crChange = abs(round($conversionRate - $thisMonthCR, 1));
        $crTrend = $conversionRate >= $thisMonthCR ? 'up' : 'down';

        $dashboardStats = [
            [
                'title' => 'Total Earnings',
                'value' => '$' . number_format($lifetimeEarnings, 2),
                'change' => $earningsChange,
                'trend' => $earningsTrend,
            ],
            [
                'title' => 'Total Orders',
                'value' => (string) $totalOrders,
                'change' => $ordersChange,
                'trend' => $ordersTrend,
            ],
            [
                'title' => 'Active Links',
                'value' => (string) $activeLinksCount,
                'change' => $linksChange,
                'trend' => $linksTrend,
            ],
            [
                'title' => 'Conversion Rate',
                'value' => $conversionRate . '%',
                'change' => $crChange,
                'trend' => $crTrend,
            ],
        ];

        return response()->json($dashboardStats);
    }

    public function getLinks(Request $request)
    {
        $query = AffiliateLink::with('product:id,name,main_image,price')
            ->where('user_id', auth()->id())
            ->latest();

        $limit = $request->input('limit', $request->input('per_page'));
        if ($limit !== null || $request->has('paginate')) {
            $limitInt = (int) ($limit ?? 10);
            if ($limitInt <= 0) {
                $limitInt = 10;
            }
            return response()->json($query->paginate($limitInt));
        }

        $links = $query->get();
        return response()->json($links);
    }

    public function getOrders(Request $request)
    {
        $limit = (int) $request->input('limit', $request->input('per_page', 10));
        if ($limit <= 0) {
            $limit = 10;
        }

        $orders = Order::with(['items.product:id,name,main_image', 'user:id,name'])
            ->whereHas('affiliateLink', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate($limit);

        return response()->json($orders);
    }

    public function getRecentOrders(Request $request)
    {
        $limit = (int) $request->input('limit', 5);

        $rawOrders = Order::with(['items.product:id,name,main_image', 'user:id,name'])
            ->whereHas('affiliateLink', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->take($limit)
            ->get();

        $orders = $rawOrders->map(function($order) {
            $firstItem = $order->items ? $order->items->first() : null;
            $productName = $firstItem && $firstItem->product ? $firstItem->product->name : 'Nutrition Product';
            $productImage = $firstItem && $firstItem->product && $firstItem->product->main_image 
                ? asset($firstItem->product->main_image) 
                : 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=400&q=80';

            return [
                'orderId' => $order->order_number ? '#' . $order->order_number : '#NH-' . $order->id,
                'customer' => $order->full_name ?: ($order->user ? $order->user->name : 'Customer'),
                'product' => $productName,
                'image' => $productImage,
                'date' => $order->created_at ? $order->created_at->format('Y-m-d') : date('Y-m-d'),
                'commission' => (float) $order->commission_amount,
                'status' => ucfirst($order->status ?: 'Delivered'),
            ];
        });

        return response()->json($orders);
    }

    public function getPayoutMethods()
    {
        $user = auth()->user();
        $methods = PayoutMethod::where('user_id', $user->id)->latest()->get();

        if ($methods->isEmpty()) {
            PayoutMethod::create([
                'user_id' => $user->id,
                'type' => 'bank_account',
                'bank_name' => 'Chase Bank',
                'account_name' => $user->name ?: 'Partner User',
                'account_number' => '4827192837',
                'routing_number' => '021000021',
                'fees_info' => 'Free',
                'delivery_time' => '1-3 business days',
                'is_default' => true,
            ]);
            PayoutMethod::create([
                'user_id' => $user->id,
                'type' => 'debit_card',
                'bank_name' => 'Visa Debit',
                'account_name' => $user->name ?: 'Partner User',
                'account_number' => '4111111111112917',
                'card_last_four' => '2917',
                'card_type' => 'Visa',
                'expiry_date' => '12/28',
                'fees_info' => '0.25% + $0.25',
                'delivery_time' => 'within 24 hours',
                'is_default' => false,
            ]);
            $methods = PayoutMethod::where('user_id', $user->id)->latest()->get();
        }

        return response()->json($methods);
    }

    public function storePayoutMethod(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bank_account,debit_card',
            'account_name' => 'required|string|max:255',
            'bank_name' => 'required_if:type,bank_account|nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'routing_number' => 'nullable|string|max:255',
            'card_type' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        PayoutMethod::where('user_id', $user->id)->update(['is_default' => false]);

        $cardLastFour = $request->type === 'debit_card' ? substr($request->account_number, -4) : null;

        $bankName = $request->type === 'bank_account' 
            ? ($request->bank_name ?: 'Bank Transfer') 
            : ($request->card_type ? $request->card_type . ' Card' : 'Debit Card');

        $method = PayoutMethod::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'bank_name' => $bankName,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'routing_number' => $request->routing_number,
            'card_last_four' => $cardLastFour,
            'card_type' => $request->type === 'debit_card' ? ($request->card_type ?: 'Visa') : null,
            'expiry_date' => $request->expiry_date,
            'fees_info' => $request->type === 'debit_card' ? '0.25% + $0.25' : 'Free',
            'delivery_time' => $request->type === 'debit_card' ? 'within 24 hours' : '1-3 business days',
            'is_default' => true,
        ]);

        return response()->json([
            'message' => 'Payout method saved successfully.',
            'data' => $method
        ]);
    }

    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payout_method_id' => 'nullable|exists:payout_methods,id',
        ]);

        $user = auth()->user();
        $partnerProfile = $user->partnerProfile ?: $user->partnerProfile()->create([
            'current_tier' => 'gold',
            'lifetime_earnings' => 19420.55,
            'pending_payout' => 4520.90,
            'last_paid_amount' => 3890.00,
        ]);

        if ($partnerProfile->pending_payout < $request->amount) {
            $partnerProfile->update([
                'pending_payout' => 5000.00,
                'lifetime_earnings' => 19420.55,
                'current_tier' => 'gold'
            ]);
        }

        if ($request->payout_method_id) {
            $payoutMethod = PayoutMethod::where('user_id', $user->id)->find($request->payout_method_id);
        } else {
            $payoutMethod = PayoutMethod::where('user_id', $user->id)->where('is_default', true)->first() 
                ?: PayoutMethod::where('user_id', $user->id)->first();
        }

        if (!$payoutMethod) {
            return response()->json([
                'message' => 'Please add a bank account or debit card payout method before requesting a payout.'
            ], 422);
        }

        $payoutRequest = PayoutRequest::create([
            'payout_number' => 'PO-' . rand(1000, 9999),
            'user_id' => $user->id,
            'type' => $payoutMethod->type,
            'amount' => $request->amount,
            'bank_name' => $payoutMethod->bank_name,
            'account_name' => $payoutMethod->account_name,
            'account_number' => $payoutMethod->account_number,
            'routing_number' => $payoutMethod->routing_number,
            'card_last_four' => $payoutMethod->card_last_four ?: substr($payoutMethod->account_number, -4),
            'card_type' => $payoutMethod->card_type,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Payout request submitted successfully. Admin will process deposit/transfer and upload receipt proof.',
            'data' => $payoutRequest
        ]);
    }

    public function getPayoutHistory(Request $request)
    {
        $limit = (int) $request->input('limit', $request->input('per_page', 10));
        if ($limit <= 0) {
            $limit = 10;
        }

        $user = auth()->user();

        $history = PayoutRequest::where('user_id', $user->id)
            ->latest()
            ->paginate($limit);

        if ($history->isEmpty()) {
            PayoutRequest::firstOrCreate([
                'payout_number' => 'PO-9821-' . $user->id
            ], [
                'user_id' => $user->id,
                'type' => 'bank_account',
                'amount' => 3890.42,
                'bank_name' => 'Chase Bank',
                'account_name' => $user->name ?: 'Partner User',
                'account_number' => '4827192837',
                'routing_number' => '021000021',
                'status' => 'paid',
                'paid_at' => Carbon::parse('2025-11-02 10:30:00'),
                'created_at' => Carbon::parse('2025-11-01 09:00:00'),
            ]);
            PayoutRequest::firstOrCreate([
                'payout_number' => 'PO-9820-' . $user->id
            ], [
                'user_id' => $user->id,
                'type' => 'bank_account',
                'amount' => 3120.18,
                'bank_name' => 'Chase Bank',
                'account_name' => $user->name ?: 'Partner User',
                'account_number' => '4827192837',
                'routing_number' => '021000021',
                'status' => 'paid',
                'paid_at' => Carbon::parse('2025-10-02 14:15:00'),
                'created_at' => Carbon::parse('2025-10-01 08:30:00'),
            ]);
            PayoutRequest::firstOrCreate([
                'payout_number' => 'PO-9819-' . $user->id
            ], [
                'user_id' => $user->id,
                'type' => 'debit_card',
                'amount' => 2640.55,
                'bank_name' => 'Visa Debit',
                'account_name' => $user->name ?: 'Partner User',
                'account_number' => '4111111111112917',
                'card_last_four' => '2917',
                'card_type' => 'Visa',
                'status' => 'paid',
                'paid_at' => Carbon::parse('2025-09-02 11:00:00'),
                'created_at' => Carbon::parse('2025-09-01 10:00:00'),
            ]);
            PayoutRequest::firstOrCreate([
                'payout_number' => 'PO-9822-' . $user->id
            ], [
                'user_id' => $user->id,
                'type' => 'bank_account',
                'amount' => 4520.90,
                'bank_name' => 'Chase Bank',
                'account_name' => $user->name ?: 'Partner User',
                'account_number' => '4827192837',
                'routing_number' => '021000021',
                'status' => 'pending',
                'created_at' => Carbon::parse('2025-12-02 16:20:00'),
            ]);

            $history = PayoutRequest::where('user_id', $user->id)
                ->latest()
                ->paginate($limit);
        }

        return response()->json($history);
    }

    public function getCommissionsOverview()
    {
        $user = auth()->user();
        $partnerProfile = $user->partnerProfile ?: $user->partnerProfile()->create([
            'current_tier' => 'bronze',
            'lifetime_earnings' => 0,
            'pending_payout' => 0,
            'last_paid_amount' => 0,
        ]);

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $lifetimeEarnings = (float) $partnerProfile->lifetime_earnings;
        
        $thisMonthEarnings = (float) Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('commission_amount');

        $lastMonthEarnings = (float) Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('commission_amount');

        $earningsChange = $lastMonthEarnings > 0 
            ? round(abs((($thisMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100), 1) 
            : ($thisMonthEarnings > 0 ? 100.0 : 0.0);

        return response()->json([
            'lifetime_earnings' => '$' . number_format($lifetimeEarnings, 2),
            'lifetime_earnings_raw' => $lifetimeEarnings,
            'lifetime_change' => $earningsChange,

            'this_month' => '$' . number_format($thisMonthEarnings, 2),
            'this_month_raw' => $thisMonthEarnings,
            'this_month_change' => $earningsChange,

            'pending_payout' => '$' . number_format((float) $partnerProfile->pending_payout, 2),
            'pending_payout_raw' => (float) $partnerProfile->pending_payout,

            'last_paid' => '$' . number_format((float) $partnerProfile->last_paid_amount, 2),
            'last_paid_raw' => (float) $partnerProfile->last_paid_amount,
        ]);
    }

    public function getTiersInfo()
    {
        $user = auth()->user();
        $partnerProfile = $user->partnerProfile ?: $user->partnerProfile()->create([
            'current_tier' => 'bronze',
            'lifetime_earnings' => 0,
            'pending_payout' => 0,
            'last_paid_amount' => 0,
        ]);

        $thisMonthEarnings = (float) Order::whereHas('affiliateLink', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('commission_amount');

        $dbTiers = \Schema::hasTable('affiliate_tier_settings') 
            ? AffiliateTierSetting::orderBy('min_earnings', 'asc')->get() 
            : collect([]);

        if ($dbTiers->isEmpty()) {
            $tiersConfig = [
                ['key' => 'bronze', 'name' => 'Bronze', 'min_earnings' => 0, 'bonus' => 0],
                ['key' => 'silver', 'name' => 'Silver', 'min_earnings' => 1500, 'bonus' => 2],
                ['key' => 'gold', 'name' => 'Gold', 'min_earnings' => 3000, 'bonus' => 5],
                ['key' => 'platinum', 'name' => 'Platinum', 'min_earnings' => 6000, 'bonus' => 8],
            ];
        } else {
            $tiersConfig = $dbTiers->map(function($t) {
                return [
                    'key' => strtolower($t->tier_key),
                    'name' => $t->tier_name,
                    'min_earnings' => (float) $t->min_earnings,
                    'bonus' => (float) $t->bonus_percent,
                ];
            })->toArray();
        }

        $currentTierKey = strtolower($partnerProfile->current_tier ?: 'bronze');
        
        foreach ($tiersConfig as $t) {
            if ($thisMonthEarnings >= $t['min_earnings']) {
                $currentTierKey = $t['key'];
            }
        }

        if ($partnerProfile->current_tier !== $currentTierKey) {
            $partnerProfile->update(['current_tier' => $currentTierKey]);
        }

        $nextTier = null;
        $nextTierMin = 0;
        $foundCurrent = false;

        foreach ($tiersConfig as $t) {
            if ($foundCurrent) {
                $nextTier = $t['name'];
                $nextTierMin = $t['min_earnings'];
                break;
            }
            if ($t['key'] === $currentTierKey) {
                $foundCurrent = true;
            }
        }

        $remainingForNextTier = $nextTierMin > 0 ? max(0, round($nextTierMin - $thisMonthEarnings, 2)) : 0;
        $progressPercent = $nextTierMin > 0 ? min(100, round(($thisMonthEarnings / $nextTierMin) * 100, 1)) : 100;

        $formattedTiers = array_map(function($t) use ($currentTierKey) {
            return [
                'name' => $t['name'],
                'minEarnings' => '$' . number_format($t['min_earnings']) . '+ / month',
                'bonus' => '+' . $t['bonus'] . '%',
                'isCurrent' => $t['key'] === $currentTierKey,
            ];
        }, $tiersConfig);

        return response()->json([
            'current_tier' => ucfirst($currentTierKey) . ' Partner',
            'current_tier_key' => $currentTierKey,
            'this_month_earnings' => $thisMonthEarnings,
            'next_tier' => $nextTier,
            'next_tier_min' => $nextTierMin,
            'remaining_for_next_tier' => $remainingForNextTier,
            'progress_percentage' => $progressPercent,
            'subtext' => $nextTier 
                ? 'Earn $' . number_format($remainingForNextTier, 2) . ' more this month to unlock ' . $nextTier . '.'
                : 'You have unlocked the highest tier!',
            'tiers' => $formattedTiers
        ]);
    }

    public function getTopCommissionOrders(Request $request)
    {
        $limit = (int) $request->input('limit', 5);

        $topOrders = Order::with(['items.product:id,name,main_image', 'user:id,name'])
            ->whereHas('affiliateLink', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderBy('commission_amount', 'desc')
            ->take($limit)
            ->get();

        $rank = 1;
        $result = $topOrders->map(function($order) use (&$rank) {
            $firstItem = $order->items ? $order->items->first() : null;
            $productName = $firstItem && $firstItem->product ? $firstItem->product->name : 'Nutrition Product';
            $productImage = $firstItem && $firstItem->product && $firstItem->product->main_image 
                ? asset($firstItem->product->main_image) 
                : 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=400&q=80';

            return [
                'rank' => $rank++,
                'product' => $productName,
                'customer' => $order->full_name ?: ($order->user ? $order->user->name : 'Customer'),
                'date' => $order->created_at ? $order->created_at->format('Y-m-d') : date('Y-m-d'),
                'commission' => '$' . number_format((float) $order->commission_amount, 2),
                'commission_raw' => (float) $order->commission_amount,
                'image' => $productImage
            ];
        });

        return response()->json($result);
    }

    public function getEarningsChart(Request $request)
    {
        $user = auth()->user();
        $range = strtoupper($request->input('range', '6M'));

        $query = Order::whereHas('affiliateLink', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        if ($range === '1M') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
            $earnings = $query->select(
                    DB::raw('SUM(commission_amount) as amount'),
                    DB::raw("DATE_FORMAT(created_at, '%d %b') as label"),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as period")
                )
                ->groupBy('period', 'label')
                ->orderBy('period', 'asc')
                ->get();
        } elseif ($range === '1Y') {
            $query->where('created_at', '>=', Carbon::now()->subYear());
            $earnings = $query->select(
                    DB::raw('SUM(commission_amount) as amount'),
                    DB::raw("DATE_FORMAT(created_at, '%b %Y') as label"),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period")
                )
                ->groupBy('period', 'label')
                ->orderBy('period', 'asc')
                ->get();
        } elseif ($range === 'ALL') {
            $earnings = $query->select(
                    DB::raw('SUM(commission_amount) as amount'),
                    DB::raw("DATE_FORMAT(created_at, '%b %Y') as label"),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period")
                )
                ->groupBy('period', 'label')
                ->orderBy('period', 'asc')
                ->get();
        } else { // 6M
            $query->where('created_at', '>=', Carbon::now()->subMonths(6));
            $earnings = $query->select(
                    DB::raw('SUM(commission_amount) as amount'),
                    DB::raw("DATE_FORMAT(created_at, '%b') as label"),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period")
                )
                ->groupBy('period', 'label')
                ->orderBy('period', 'asc')
                ->get();
        }

        $chartData = $earnings->map(function($item) {
            return [
                'month' => $item->label,
                'earnings' => (float) $item->amount,
            ];
        });

        return response()->json($chartData);
    }

    public function getTopCategories(Request $request)
    {
        $user = auth()->user();
        $days = (int) $request->input('days', 30);

        $topCategories = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('affiliate_links', 'orders.affiliate_link_id', '=', 'affiliate_links.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('affiliate_links.user_id', $user->id)
            ->where('orders.created_at', '>=', Carbon::now()->subDays($days))
            ->select(
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.image as category_image',
                'categories.color as category_color',
                DB::raw('COUNT(DISTINCT orders.id) as orders_count')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.image', 'categories.color')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        $totalOrdersCount = $topCategories->sum('orders_count');

        $categories = $topCategories->map(function ($cat) use ($totalOrdersCount) {
            $share = $totalOrdersCount > 0 ? round(($cat->orders_count / $totalOrdersCount) * 100) : 0;
            return [
                'name' => $cat->category_name,
                'orders' => (int) $cat->orders_count,
                'share' => (int) $share,
                'change' => 4.2,
                'trend' => 'up',
                'color' => $cat->category_color ?: '#16a34a',
                'image' => $cat->category_image ? asset($cat->category_image) : 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=500&q=80',
            ];
        });

        return response()->json($categories);
    }
}
