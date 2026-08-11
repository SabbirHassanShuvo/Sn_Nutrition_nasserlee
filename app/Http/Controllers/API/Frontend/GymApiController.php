<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\Request;
use Exception;

class GymApiController extends Controller
{
    /**
     * Get nearby gyms sorted by distance using Haversine Great-Circle formula.
     */
    public function getNearbyGyms(Request $request)
    {
        // Default to user coordinates or Casablanca center
        $userLat = $request->filled('latitude') ? (float) $request->latitude : 33.5700000;
        $userLng = $request->filled('longitude') ? (float) $request->longitude : -7.5850000;

        try {
            // Haversine Formula distance calculation in MySQL (Radius R = 6371 km)
            $gyms = Gym::where('is_active', true)
                ->selectRaw("gyms.*, (
                    6371 * acos(
                        least(1.0, greatest(-1.0, 
                            cos(radians(?)) 
                            * cos(radians(latitude)) 
                            * cos(radians(longitude) - radians(?)) 
                            + sin(radians(?)) 
                            * sin(radians(latitude))
                        ))
                    )
                ) AS distance_in_km", [$userLat, $userLng, $userLat])
                ->orderBy('distance_in_km', 'asc')
                ->get()
                ->map(function ($gym) {
                    $distKm = round((float) ($gym->distance_in_km ?? 0.4), 1);
                    
                    $image = "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=300&q=80";
                    if (!empty($gym->image)) {
                        if (filter_var($gym->image, FILTER_VALIDATE_URL) || str_starts_with($gym->image, 'http://') || str_starts_with($gym->image, 'https://')) {
                            $image = $gym->image;
                        } else {
                            $image = asset(ltrim($gym->image, '/'));
                        }
                    }

                    return [
                        'id' => $gym->id,
                        'name' => $gym->name,
                        'address' => $gym->address,
                        'distance' => $distKm . ' km',
                        'distance_km' => $distKm,
                        'distance_text' => $distKm . ' km',
                        'rating' => (float) ($gym->rating ?? 4.8),
                        'hours' => $gym->opening_hours ?? '06:00 - 22:00',
                        'opening_hours' => $gym->opening_hours ?? '06:00 - 22:00',
                        'tags' => $gym->facilities ?? ['Cardio', 'Weights', 'Classes'],
                        'facilities' => $gym->facilities ?? ['Cardio', 'Weights', 'Classes'],
                        'image' => $image,
                        'open' => true,
                        'open_status' => 'Open',
                        'status' => 'Open',
                        'latitude' => (float) $gym->latitude,
                        'longitude' => (float) $gym->longitude,
                        'directions_url' => "https://www.google.com/maps/dir/?api=1&destination={$gym->latitude},{$gym->longitude}",
                    ];
                });


            $data = [
                'total_found' => count($gyms),
                'user_location' => [
                    'latitude' => $userLat,
                    'longitude' => $userLng,
                ],
                'gyms' => $gyms
            ];

            return response()->json([
                'status' => true,
                'message' => 'Nearby gyms retrieved successfully',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve nearby gyms: ' . $e->getMessage()
            ], 500);
        }
    }
}
