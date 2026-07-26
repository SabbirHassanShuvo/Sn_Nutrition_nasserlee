<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Exception;

class GymApiController extends Controller
{
    /**
     * Get nearby gyms sorted by distance using Haversine Great-Circle formula.
     */
    public function getNearbyGyms(Request $request)
    {
        // Auto-create table & sample seed if table doesn't exist
        if (!Schema::hasTable('gyms')) {
            Schema::create('gyms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address');
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->decimal('rating', 2, 1)->default(4.5);
                $table->string('opening_hours')->nullable()->default('06:00 - 22:00');
                $table->json('facilities')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            Gym::create([
                'name' => 'FitZone Gym',
                'address' => '12 Rue Mohammed V, Casablanca',
                'latitude' => 33.5731104,
                'longitude' => -7.5898434,
                'rating' => 4.8,
                'opening_hours' => '06:00 - 22:00',
                'facilities' => ['Cardio', 'Weights', 'Classes'],
                'is_active' => true,
            ]);

            Gym::create([
                'name' => 'PowerHouse Fitness',
                'address' => '34 Boulevard Anfa, Casablanca',
                'latitude' => 33.5851104,
                'longitude' => -7.6018434,
                'rating' => 4.5,
                'opening_hours' => '07:00 - 23:00',
                'facilities' => ['CrossFit', 'Sauna', 'Pool'],
                'is_active' => true,
            ]);

            Gym::create([
                'name' => 'Elite Sports Club',
                'address' => '7 Rue Ibnou Sina, Casablanca',
                'latitude' => 33.5921104,
                'longitude' => -7.6128434,
                'rating' => 4.3,
                'opening_hours' => '06:30 - 21:00',
                'facilities' => ['Yoga', 'Boxing', 'Weights'],
                'is_active' => true,
            ]);

            Gym::create([
                'name' => 'Urban Athlete',
                'address' => '88 Avenue Hassan II, Casablanca',
                'latitude' => 33.5981104,
                'longitude' => -7.6208434,
                'rating' => 4.6,
                'opening_hours' => '06:00 - 22:30',
                'facilities' => ['HIIT', 'Cycling', 'Nutrition'],
                'is_active' => true,
            ]);
        }

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
                    $image = $gym->image ? asset($gym->image) : "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=300&q=80";

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
                        'img' => $image,
                        'image' => $image,
                        'open' => true,
                        'open_status' => 'Open',
                        'status' => 'Open',
                        'latitude' => (float) $gym->latitude,
                        'longitude' => (float) $gym->longitude,
                        'directions_url' => "https://www.google.com/maps/dir/?api=1&destination={$gym->latitude},{$gym->longitude}",
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Nearby gyms retrieved successfully',
                'total_found' => count($gyms),
                'user_location' => [
                    'latitude' => $userLat,
                    'longitude' => $userLng,
                ],
                'gyms' => $gyms
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve nearby gyms: ' . $e->getMessage()
            ], 500);
        }
    }
}
