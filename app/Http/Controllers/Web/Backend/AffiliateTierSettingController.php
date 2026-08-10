<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\AffiliateTierSetting;
use Illuminate\Http\Request;

class AffiliateTierSettingController extends Controller
{
    public function index()
    {
        $tiers = AffiliateTierSetting::orderBy('min_earnings', 'asc')->get();

        if ($tiers->isEmpty()) {
            AffiliateTierSetting::insert([
                ['tier_key' => 'bronze', 'tier_name' => 'Bronze', 'min_earnings' => 0.00, 'bonus_percent' => 0.00, 'badge_color' => '#cd7f32', 'created_at' => now(), 'updated_at' => now()],
                ['tier_key' => 'silver', 'tier_name' => 'Silver', 'min_earnings' => 1500.00, 'bonus_percent' => 2.00, 'badge_color' => '#c0c0c0', 'created_at' => now(), 'updated_at' => now()],
                ['tier_key' => 'gold', 'tier_name' => 'Gold', 'min_earnings' => 3000.00, 'bonus_percent' => 5.00, 'badge_color' => '#ffd700', 'created_at' => now(), 'updated_at' => now()],
                ['tier_key' => 'platinum', 'tier_name' => 'Platinum', 'min_earnings' => 6000.00, 'bonus_percent' => 8.00, 'badge_color' => '#e5e4e2', 'created_at' => now(), 'updated_at' => now()],
            ]);
            $tiers = AffiliateTierSetting::orderBy('min_earnings', 'asc')->get();
        }

        return view('backend.layout.affiliate.settings', compact('tiers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tiers' => 'required|array',
            'tiers.*.id' => 'required|exists:affiliate_tier_settings,id',
            'tiers.*.min_earnings' => 'required|numeric|min:0',
            'tiers.*.bonus_percent' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->tiers as $tierData) {
            $tier = AffiliateTierSetting::find($tierData['id']);
            if ($tier) {
                $tier->update([
                    'min_earnings' => $tierData['min_earnings'],
                    'bonus_percent' => $tierData['bonus_percent'],
                ]);
            }
        }

        return redirect()->back()->with('t-success', 'Affiliate Tier settings updated successfully.');
    }
}
