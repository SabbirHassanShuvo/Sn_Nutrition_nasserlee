<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MyInformationController extends BaseController
{
    /**
     * Get complete My Information data (Personal Info, Addresses, Fitness Profile, Supplement History).
     */
    public function index()
    {
        try {
            $user = User::with(['profile', 'addresses', 'defaultAddress'])->find(Auth::id());

            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }

            // Ensure profile exists
            $profile = $user->profile;
            if (!$profile) {
                $profile = Profile::create(['user_id' => $user->id]);
            }

            // Calculate BMI
            $bmiData = $this->calculateBmi($profile->height, $profile->weight);

            // Fetch Supplement Intake History from delivered orders
            $supplementHistory = $this->fetchSupplementHistory($user->id);

            $data = [
                'personal_information' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $profile->phone,
                    'gender' => $profile->gender,
                    'date_of_birth' => $profile->date_of_birth,
                    'avatar' => $profile->avatar ? asset($profile->avatar) : null,
                ],
                'fitness_profile' => [
                    'height' => $profile->height ? (float) $profile->height : null,
                    'weight' => $profile->weight ? (float) $profile->weight : null,
                    'activity_level' => $profile->activity_level,
                    'gym_place' => $profile->gym_place,
                    'diet' => $profile->diet,
                    'allergy' => $profile->allergy,
                ],
                'supplement_intake_history' => $supplementHistory,
            ];

            return $this->sendResponse($data, 'My Information fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch user information.', $e->getMessage());
        }
    }

    /**
     * Update Personal Information.
     */
    public function updatePersonalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:male,female,other,Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,svg,ico,bmp,tiff',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = User::with('profile')->find(Auth::id());
            $profile = $user->profile ?: Profile::create(['user_id' => $user->id]);

            if ($request->filled('name')) {
                $user->name = $request->name;
                $user->save();
            }

            if ($request->has('phone')) {
                $profile->phone = $request->phone;
            }

            if ($request->has('gender')) {
                $profile->gender = strtolower($request->gender);
            }

            if ($request->has('date_of_birth')) {
                $profile->date_of_birth = $request->date_of_birth;
            }

            if ($request->hasFile('avatar')) {
                if ($profile->avatar && function_exists('fileDelete')) {
                    fileDelete($profile->avatar);
                }
                if (function_exists('fileUpload')) {
                    $profile->avatar = fileUpload($request->file('avatar'), 'profile/avatar');
                }
            }

            $profile->save();

            return $this->sendResponse([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $profile->phone,
                'gender' => $profile->gender,
                'date_of_birth' => $profile->date_of_birth,
                'avatar' => $profile->avatar ? asset($profile->avatar) : null,
            ], 'Personal information updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to update personal information.', $e->getMessage());
        }
    }

    /**
     * Update Fitness Profile.
     */
    public function updateFitnessProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'height' => 'nullable|numeric|min:50|max:250',
            'weight' => 'nullable|numeric|min:20|max:300',
            'activity_level' => 'nullable|string|max:100',
            'gym_place' => 'nullable|string|max:100',
            'diet' => 'nullable|string|max:100',
            'allergy' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = User::with('profile')->find(Auth::id());
            $profile = $user->profile ?: Profile::create(['user_id' => $user->id]);

            if ($request->has('height')) {
                $profile->height = $request->height;
            }
            if ($request->has('weight')) {
                $profile->weight = $request->weight;
            }
            if ($request->has('activity_level')) {
                $profile->activity_level = $request->activity_level;
            }
            if ($request->has('gym_place')) {
                $profile->gym_place = $request->gym_place;
            }
            if ($request->has('diet')) {
                $profile->diet = $request->diet;
            }
            if ($request->has('allergy')) {
                $profile->allergy = $request->allergy;
            }

            $profile->save();

            return $this->sendResponse([
                'fitness_profile' => [
                    'height' => $profile->height ? (float) $profile->height : null,
                    'weight' => $profile->weight ? (float) $profile->weight : null,
                    'activity_level' => $profile->activity_level,
                    'gym_place' => $profile->gym_place,
                    'diet' => $profile->diet,
                    'allergy' => $profile->allergy,
                ],
            ], 'Fitness profile updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to update fitness profile.', $e->getMessage());
        }
    }

    /**
     * Get BMI Gauge Score & Category.
     */
    public function getBmiGauge()
    {
        try {
            $user = User::with('profile')->find(Auth::id());
            $profile = $user ? $user->profile : null;

            $bmiData = $this->calculateBmi($profile ? $profile->height : null, $profile ? $profile->weight : null);

            return $this->sendResponse($bmiData, 'BMI gauge score fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch BMI gauge.', $e->getMessage());
        }
    }

    /**
     * Get Supplement Intake History.
     */
    public function getSupplementHistory()
    {
        try {
            $history = $this->fetchSupplementHistory(Auth::id());
            return $this->sendResponse($history, 'Supplement intake history fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch supplement history.', $e->getMessage());
        }
    }

    /**
     * Helper to calculate BMI and Category.
     */
    private function calculateBmi($height, $weight)
    {
        if (!$height || !$weight || $height <= 0) {
            return [
                'score' => null,
                'category' => 'Unknown',
            ];
        }

        $heightInMeters = $height / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 1);

        $category = 'Normal';
        if ($bmi < 18.5) {
            $category = 'Underweight';
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $category = 'Normal';
        } elseif ($bmi >= 25.0 && $bmi <= 29.9) {
            $category = 'Overweight';
        } elseif ($bmi >= 30.0 && $bmi <= 34.9) {
            $category = 'Obese class I';
        } else {
            $category = 'Obese class II';
        }

        return [
            'score' => $bmi,
            'category' => $category,
        ];
    }

    /**
     * Helper to fetch supplement intake history from delivered orders.
     */
    private function fetchSupplementHistory($userId)
    {
        $deliveredOrders = Order::where('user_id', $userId)
            ->whereIn('status', ['delivered', 'Delivered'])
            ->with(['items.product'])
            ->latest()
            ->get();

        $historyMap = [];

        foreach ($deliveredOrders as $order) {
            foreach ($order->items as $item) {
                if (!$item->product) {
                    continue;
                }

                $productId = $item->product_id;

                if (!isset($historyMap[$productId])) {
                    $historyMap[$productId] = [
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'image' => $item->product->main_image ? asset($item->product->main_image) : null,
                        'brand' => $item->product->brand,
                        'total_units' => 0,
                        'order_count' => 0,
                        'last_taken' => $order->updated_at ? $order->updated_at->format('Y-m-d') : $order->created_at->format('Y-m-d'),
                    ];
                }

                $historyMap[$productId]['total_units'] += (int) $item->quantity;
                $historyMap[$productId]['order_count'] += 1;
            }
        }

        return array_values($historyMap);
    }
}
