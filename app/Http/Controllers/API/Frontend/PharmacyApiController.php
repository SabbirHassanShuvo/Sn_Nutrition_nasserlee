<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Exception;

class PharmacyApiController extends Controller
{
    /**
     * Get nearby pharmacies sorted by distance using Haversine Great-Circle formula.
     */
    public function getNearbyPharmacies(Request $request)
    {
        // Default to user coordinates or Casablanca center
        $userLat = $request->filled('latitude') ? (float) $request->latitude : 33.5700000;
        $userLng = $request->filled('longitude') ? (float) $request->longitude : -7.5850000;

        try {
            // Haversine Formula distance calculation in MySQL (Radius R = 6371 km)
            $pharmacies = Pharmacy::where('is_active', true)
                ->selectRaw("pharmacies.*, (
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
                ->map(function ($pharmacy) {
                    $distKm = round((float) ($pharmacy->distance_in_km ?? 0.2), 1);
                    
                    $image = "https://images.unsplash.com/photo-1586015555751-63bb77f4322a?w=300&q=80";
                    if (!empty($pharmacy->image)) {
                        if (filter_var($pharmacy->image, FILTER_VALIDATE_URL) || str_starts_with($pharmacy->image, 'http://') || str_starts_with($pharmacy->image, 'https://')) {
                            $image = $pharmacy->image;
                        } else {
                            $image = asset(ltrim($pharmacy->image, '/'));
                        }
                    }

                    return [
                        'id' => $pharmacy->id,
                        'name' => $pharmacy->name,
                        'address' => $pharmacy->address,
                        'phone' => $pharmacy->phone ?? '+212 522 123 456',
                        'distance' => $distKm . ' km',
                        'distance_km' => $distKm,
                        'distance_text' => $distKm . ' km',
                        'rating' => (float) ($pharmacy->rating ?? 4.8),
                        'hours' => $pharmacy->opening_hours ?? '08:00 - 22:00',
                        'opening_hours' => $pharmacy->opening_hours ?? '08:00 - 22:00',
                        'tags' => $pharmacy->services ?? ['24h Available', 'Delivery', 'Vaccines'],
                        'services' => $pharmacy->services ?? ['24h Available', 'Delivery', 'Vaccines'],
                        'image' => $image,
                        'open' => true,
                        'open_status' => 'Open',
                        'status' => 'Open',
                        'latitude' => (float) $pharmacy->latitude,
                        'longitude' => (float) $pharmacy->longitude,
                        'directions_url' => "https://www.google.com/maps/dir/?api=1&destination={$pharmacy->latitude},{$pharmacy->longitude}",
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Nearby pharmacies retrieved successfully',
                'total_found' => count($pharmacies),
                'user_location' => [
                    'latitude' => $userLat,
                    'longitude' => $userLng,
                ],
                'pharmacies' => $pharmacies
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve nearby pharmacies: ' . $e->getMessage()
            ], 500);
        }
    }
}
