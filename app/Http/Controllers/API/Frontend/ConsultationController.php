<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class ConsultationController extends Controller
{
    /**
     * Get list of active specialists for booking matching exact frontend structure.
     */
    public function getSpecialists()
    {
        $specialists = Specialist::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($specialist) {
                $avatarUrl = "https://i.pravatar.cc/48?img=" . ($specialist->id + 10);
                if (!empty($specialist->avatar)) {
                    if (filter_var($specialist->avatar, FILTER_VALIDATE_URL) || str_starts_with($specialist->avatar, 'http://') || str_starts_with($specialist->avatar, 'https://')) {
                        $avatarUrl = $specialist->avatar;
                    } else {
                        $avatarUrl = asset(ltrim($specialist->avatar, '/'));
                    }
                }
                $specialtiesArr = !empty($specialist->specialties) && is_array($specialist->specialties) 
                    ? $specialist->specialties 
                    : ["Weight management", "Sports nutrition"];

                return [
                    'id' => $specialist->id,
                    'name' => $specialist->name,
                    'role' => $specialist->title ?? 'Clinical Nutritionist',
                    'tags' => $specialtiesArr,
                    'img' => $avatarUrl,
                    'email' => $specialist->email,
                    'bio' => $specialist->bio,
                ];
            });

        $defaultTimeSlots = ["12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM"];

        return response()->json([
            'status' => true,
            'message' => 'Active specialists retrieved successfully',
            'specialists' => $specialists,
            'time_slots' => $defaultTimeSlots,
        ]);
    }

    /**
     * Store a consultation booking request from user.
     */
    public function book(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required|exists:specialists,id',
            'call_type' => 'required|in:video_call,audio_call',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'booking_date' => 'required',
            'booking_time' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $specialist = Specialist::find($request->specialist_id);
            if (!$specialist) {
                return response()->json([
                    'status' => false,
                    'message' => 'Specialist not found'
                ], 404);
            }

            // Parse date safely
            try {
                $parsedDate = Carbon::parse($request->booking_date)->format('Y-m-d');
            } catch (Exception $dateEx) {
                $parsedDate = date('Y-m-d');
            }

            // Generate unique booking number
            $bookingNumber = 'CB-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            $userId = null;
            try {
                if (auth('api')->check()) {
                    $userId = auth('api')->id();
                }
            } catch (Exception $authEx) {
                $userId = null;
            }

            $booking = ConsultationBooking::create([
                'booking_number' => $bookingNumber,
                'user_id' => $userId,
                'specialist_id' => $specialist->id,
                'call_type' => $request->call_type,
                'user_name' => $request->user_name,
                'user_email' => $request->user_email,
                'user_phone' => $request->user_phone,
                'notes' => $request->notes,
                'booking_date' => $parsedDate,
                'booking_time' => $request->booking_time,
                'status' => 'pending',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Consultation booking requested successfully. Our admin team will review and send you the Zoom meeting link.',
                'data' => $booking->load('specialist')
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logged-in user's consultation bookings.
     */
    public function myBookings()
    {
        $userId = null;
        $userEmail = null;

        try {
            if (auth('api')->check()) {
                $userId = auth('api')->id();
                $userEmail = auth('api')->user() ? auth('api')->user()->email : null;
            }
        } catch (Exception $e) {
            // Unauthenticated
        }

        $bookings = ConsultationBooking::with('specialist')
            ->where(function ($q) use ($userId, $userEmail) {
                if ($userId) {
                    $q->where('user_id', $userId);
                }
                if ($userEmail) {
                    $q->orWhere('user_email', $userEmail);
                }
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'My consultation bookings retrieved successfully',
            'data' => $bookings
        ]);
    }
}
