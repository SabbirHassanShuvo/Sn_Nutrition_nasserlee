<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\User;
use App\Models\PartnerProfile;
use App\Models\AffiliateLink;
use App\Models\Order;
use App\Models\PayoutHistory;
use App\Models\PayoutRequest;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Fetch all health professional users from database
$users = User::where('role', 'health_professional')->get();

$allProfessionalsData = [];

foreach ($users as $user) {
    $allProfessionalsData[] = [
        'user' => $user->toArray(),
        'partner_profile' => $user->partnerProfile ? $user->partnerProfile->toArray() : null,
        'affiliate_links' => AffiliateLink::where('user_id', $user->id)->with('product')->get()->toArray(),
        'payout_requests' => PayoutRequest::where('user_id', $user->id)->get()->toArray(),
        'payout_history' => PayoutHistory::where('user_id', $user->id)->get()->toArray(),
        'recent_orders' => Order::whereHas('affiliateLink', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('items.product')->latest()->limit(10)->get()->toArray()
    ];
}

file_put_contents('affiliate_user_data.json', json_encode($allProfessionalsData, JSON_PRETTY_PRINT));
echo "All " . count($allProfessionalsData) . " health professionals data written to affiliate_user_data.json successfully.\n";
