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
        // Auto-create table & sample seed if table doesn't exist
        if (!Schema::hasTable('pharmacies')) {
            Schema::create('pharmacies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address');
                $table->string('phone')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->decimal('rating', 2, 1)->default(4.8);
                $table->string('opening_hours')->nullable()->default('08:00 - 22:00');
                $table->json('services')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            Pharmacy::create([
                'name' => 'Pharmacie Centrale',
                'address' => '5 Rue Allal Ben Abdellah, Casablanca',
                'phone' => '+212 522 123 456',
                'latitude' => 33.5731104,
                'longitude' => -7.5898434,
                'rating' => 4.9,
                'opening_hours' => '08:00 - 22:00',
                'services' => ['24h Available', 'Delivery', 'Vaccines'],
                'is_active' => true,
            ]);

            Pharmacy::create([
                'name' => 'Green Pharma',
                'address' => '22 Avenue Mers Sultan, Casablanca',
                'phone' => '+212 522 654 321',
                'latitude' => 33.5801104,
                'longitude' => -7.5958434,
                'rating' => 4.6,
                'opening_hours' => '08:00 - 21:00',
                'services' => ['Supplements', 'Homeopathy'],
                'is_active' => true,
            ]);

            Pharmacy::create([
                'name' => 'Pharmacie du Peuple',
                'address' => '10 Rue Ibn Battouta, Casablanca',
                'phone' => '+212 522 789 012',
                'latitude' => 33.5881104,
                'longitude' => -7.6058434,
                'rating' => 4.4,
                'opening_hours' => '09:00 - 20:00',
                'services' => ['Delivery', 'Cosmetics'],
                'is_active' => true,
            ]);

            Pharmacy::create([
                'name' => 'MediPlus Pharmacy',
                'address' => '67 Boulevard Zerktouni, Casablanca',
                'phone' => '+212 522 345 678',
                'latitude' => 33.5951104,
                'longitude' => -7.6158434,
                'rating' => 4.7,
                'opening_hours' => '07:30 - 23:00',
                'services' => ['24h Available', 'Lab Tests', 'Delivery'],
                'is_active' => true,
            ]);
        }

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
