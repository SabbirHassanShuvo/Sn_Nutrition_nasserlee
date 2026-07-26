<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Mail\ConsultationBookingApprovedMail;
use App\Models\ConsultationBooking;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Log;

class ConsultationBookingController extends Controller
{
    protected ZoomService $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    /**
     * Display list of consultation bookings.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ConsultationBooking::with(['specialist', 'user'])
                ->select('consultation_bookings.*')
                ->orderBy('consultation_bookings.id', 'desc');

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('consultation_bookings.status', $request->status);
            }

            return DataTables::of($query)
                ->orderColumn('booking_info', 'consultation_bookings.booking_number $1')
                ->orderColumn('user_details', 'consultation_bookings.user_name $1')
                ->orderColumn('schedule', 'consultation_bookings.booking_date $1')
                ->orderColumn('status_badge', 'consultation_bookings.status $1')
                ->addColumn('booking_info', function ($row) {
                    $isAudio = $row->call_type === 'audio_call';
                    $badgeClass = $isAudio ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success';
                    $icon = $isAudio ? 'ri-phone-line' : 'ri-video-line';
                    $callTypeLabel = $isAudio ? 'Audio Call' : 'Video Call';

                    return '<div>
                        <span class="fw-bold text-dark fs-13">#' . e($row->booking_number) . '</span><br>
                        <span class="badge ' . $badgeClass . ' mt-1"><i class="' . $icon . ' me-1"></i>' . $callTypeLabel . '</span>
                    </div>';
                })
                ->addColumn('user_details', function ($row) {
                    $phoneHtml = $row->user_phone ? '<small class="text-muted d-block"><i class="ri-phone-line me-1"></i>' . e($row->user_phone) . '</small>' : '';
                    return '<div>
                        <h6 class="mb-1 fs-13 fw-semibold text-dark"><i class="ri-user-3-line text-primary me-1"></i>' . e($row->user_name) . '</h6>
                        <small class="text-muted d-block"><i class="ri-mail-line me-1"></i>' . e($row->user_email) . '</small>
                        ' . $phoneHtml . '
                    </div>';
                })
                ->addColumn('specialist_info', function ($row) {
                    if (!$row->specialist) {
                        return '<span class="badge bg-light text-muted">Unassigned</span>';
                    }
                    return '<div>
                        <h6 class="mb-0 fs-13 fw-semibold text-success"><i class="ri-user-star-line me-1"></i>' . e($row->specialist->name) . '</h6>
                        <small class="text-muted">' . e($row->specialist->title ?? 'Specialist') . '</small>
                    </div>';
                })
                ->addColumn('schedule', function ($row) {
                    $dateStr = Carbon::parse($row->booking_date)->format('M d, Y');
                    return '<div>
                        <span class="fw-medium text-dark"><i class="ri-calendar-event-line text-primary me-1"></i>' . $dateStr . '</span><br>
                        <small class="text-muted d-block mt-1"><i class="ri-time-line text-warning me-1"></i>' . e($row->booking_time) . '</small>
                    </div>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'pending' => '<span class="badge bg-warning-subtle text-warning fs-12 px-3 py-1 rounded-pill">Pending</span>',
                        'approved' => '<span class="badge bg-success-subtle text-success fs-12 px-3 py-1 rounded-pill">Approved</span>',
                        'rejected' => '<span class="badge bg-danger-subtle text-danger fs-12 px-3 py-1 rounded-pill">Rejected</span>',
                        'cancelled' => '<span class="badge bg-secondary-subtle text-secondary fs-12 px-3 py-1 rounded-pill">Cancelled</span>',
                        'completed' => '<span class="badge bg-info-subtle text-info fs-12 px-3 py-1 rounded-pill">Completed</span>',
                    ];
                    return $badges[$row->status] ?? '<span class="badge bg-primary">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('zoom_link', function ($row) {
                    if ($row->zoom_join_url) {
                        $passcode = !empty($row->zoom_password) ? $row->zoom_password : 'Auto';
                        return '<div class="text-center">
                            <div class="d-inline-flex gap-1 align-items-center mb-1">
                                <a href="' . e($row->zoom_join_url) . '" target="_blank" class="btn btn-sm btn-soft-success fw-medium shadow-none">
                                    <i class="ri-video-chat-line me-1"></i> Join Zoom
                                </a>
                                <button type="button" class="btn btn-sm btn-soft-primary copy-zoom-link" data-url="' . e($row->zoom_join_url) . '" title="Copy Zoom Link">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block fs-11"><i class="ri-key-2-line text-warning me-1"></i> Passcode: <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1 copy-passcode" style="cursor:pointer;" data-passcode="' . e($passcode) . '" title="Click to copy passcode">' . e($passcode) . '</span></small>
                        </div>';
                    }
                    return '<span class="badge bg-light text-muted fw-normal">Not Generated</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex gap-1 justify-content-center">';

                    if ($row->status === 'pending' || !$row->zoom_join_url) {
                        $btn .= '<button type="button" class="btn btn-sm btn-success shadow-sm generate-zoom-btn d-inline-flex align-items-center gap-1" data-id="' . $row->id . '">
                            <i class="ri-video-chat-fill"></i> Approve & Create Zoom
                        </button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-sm btn-soft-info resend-email-btn d-inline-flex align-items-center gap-1" data-id="' . $row->id . '" title="Resend Email">
                            <i class="ri-send-plane-line"></i> Resend Email
                        </button>';
                    }

                    if ($row->status !== 'rejected') {
                        $btn .= '<button type="button" class="btn btn-sm btn-soft-danger reject-btn d-inline-flex align-items-center" data-id="' . $row->id . '" title="Reject Booking">
                            <i class="ri-close-circle-line fs-14"></i>
                        </button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['booking_info', 'user_details', 'specialist_info', 'schedule', 'status_badge', 'zoom_link', 'action'])
                ->make(true);
        }

        return view('backend.layout.consultations.index');
    }

    /**
     * Generate Zoom Meeting Link & Approve Booking & Send Email to User
     */
    public function approveAndGenerateZoom($id)
    {
        try {
            $booking = ConsultationBooking::with('specialist')->findOrFail($id);

            $specialistName = $booking->specialist ? $booking->specialist->name : 'Nutrition Specialist';
            $topic = "Consultation Session (#{$booking->booking_number}) with {$specialistName}";

            // Format start time for Zoom API
            $startTimeIso = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time)->toIso8601String();

            // 1. Create Zoom Meeting via ZoomService
            $zoomResult = $this->zoomService->createMeeting($topic, $startTimeIso, 45);

            // 2. Update Consultation Booking record
            $booking->update([
                'status' => 'approved',
                'zoom_meeting_id' => $zoomResult['meeting_id'] ?? null,
                'zoom_join_url' => $zoomResult['join_url'] ?? null,
                'zoom_start_url' => $zoomResult['start_url'] ?? null,
                'zoom_password' => $zoomResult['password'] ?? null,
                'approved_at' => now(),
            ]);

            // 3. Send automatic email notification to user's Gmail
            $emailSent = false;
            try {
                Mail::to($booking->user_email)->send(new ConsultationBookingApprovedMail($booking));
                $emailSent = true;
            } catch (Exception $mailEx) {
                Log::error("Failed to send consultation approval email to {$booking->user_email}: " . $mailEx->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Zoom meeting generated, booking approved, and notification sent to user email (' . $booking->user_email . ')!',
                'booking' => $booking,
                'email_sent' => $emailSent
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error approving booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject or cancel booking.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled,completed'
        ]);

        $booking = ConsultationBooking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking status updated to ' . ucfirst($request->status)
        ]);
    }
}
