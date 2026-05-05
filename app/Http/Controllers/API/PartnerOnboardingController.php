<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerOnboardingController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can access onboarding
        $this->middleware('auth:api');
    }

    /**
     * Step 1: Tell us about yourself
     */
    public function step1(Request $request)
    {
        $request->validate([
            'professional_role' => 'nullable|string',
            'years_of_experience' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        $profile = auth()->user()->partnerProfile()->firstOrCreate(
            ['user_id' => auth()->id()]
        );

        $profile->update([
            'professional_role' => $request->professional_role,
            'years_of_experience' => $request->years_of_experience,
            'bio' => $request->bio,
            'onboarding_step' => 2
        ]);

        return response()->json(['message' => 'Step 1 completed', 'profile' => $profile]);
    }

    /**
     * Step 2: Where do you work from?
     */
    public function step2(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
        ]);

        $profile = auth()->user()->partnerProfile()->firstOrCreate(
            ['user_id' => auth()->id()]
        );

        $profile->update([
            'location' => $request->location,
            'phone' => $request->phone,
            'website' => $request->website,
            'onboarding_step' => 3
        ]);

        return response()->json(['message' => 'Step 2 completed', 'profile' => $profile]);
    }

    /**
     * Step 3: What are you known for? (Specialties)
     */
    public function step3(Request $request)
    {
        $request->validate([
            'specialties' => 'nullable|array',
        ]);

        $profile = auth()->user()->partnerProfile()->firstOrCreate(
            ['user_id' => auth()->id()]
        );

        $profile->update([
            'specialties' => $request->specialties,
            'onboarding_step' => 4
        ]);

        return response()->json(['message' => 'Step 3 completed', 'profile' => $profile]);
    }

    /**
     * Step 4: What are you known for? (Certifications)
     */
    public function step4(Request $request)
    {
        $request->validate([
            'certifications' => 'nullable|array',
        ]);

        $profile = auth()->user()->partnerProfile()->firstOrCreate(
            ['user_id' => auth()->id()]
        );

        $profile->update([
            'certifications' => $request->certifications,
            'onboarding_step' => 5 // or 0 to indicate complete
        ]);

        return response()->json(['message' => 'Onboarding completed', 'profile' => $profile]);
    }
}
