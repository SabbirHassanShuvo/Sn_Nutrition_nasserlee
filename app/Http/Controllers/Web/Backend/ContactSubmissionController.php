<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ContactSubmissionController extends Controller
{
    /**
     * Display a listing of the contact submissions.
     */
    public function index(Request $request)
    {

        
        if ($request->ajax()) {
            $submissions = ContactSubmission::latest()->get();
            return DataTables::of($submissions)
                ->addColumn('checkbox', function ($submission) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $submission->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('name', fn($submission) => $submission->name)
                ->addColumn('email', fn($submission) => $submission->email)
                ->addColumn('subject', fn($submission) => $submission->subject)
                ->addColumn('created_at', fn($submission) => $submission->created_at->format('M d, Y h:i A'))
                ->addColumn('status', function ($submission) {
                    if ($submission->is_read) {
                        return '<span class="badge bg-success-subtle text-success">Read</span>';
                    }
                    return '<span class="badge bg-danger-subtle text-danger">Unread</span>';
                })
                ->addColumn('action', function ($submission) {
                    $readBtn = '';
                    if (!$submission->is_read) {
                        $readBtn = '<button type="button" onclick="markAsRead(' . $submission->id . ')" class="btn btn-success btn-sm me-1 btn-read-' . $submission->id . '">
                            <i class="ri-mail-open-line align-bottom me-1"></i> Read
                        </button>';
                    }
                    return '
                        <div class="d-flex align-items-center">
                            ' . $readBtn . '
                            <button type="button" onclick="viewDetails(' . htmlspecialchars(json_encode($submission), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-info btn-sm me-1">
                                <i class="ri-eye-line align-bottom"></i>
                            </button>
                            <button type="button" onclick="deleteData(\'' . route('backend.contact-submissions.destroy', $submission->id) . '\')" class="btn btn-danger btn-sm">
                                <i class="ri-delete-bin-line align-bottom"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.contact_submissions.index');
    }

    /**
     * Mark the specified contact submission as read.
     */
    public function markAsRead($id)
    {
        try {
            $submission = ContactSubmission::findOrFail($id);
            if (!$submission->is_read) {
                $submission->is_read = true;
                $submission->save();
            }

            $unreadCount = ContactSubmission::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read successfully.',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark message as read.'
            ], 500);
        }
    }

    /**
     * Remove the specified contact submission from database.
     */
    public function destroy($id)
    {
        try {
            $submission = ContactSubmission::findOrFail($id);
            $submission->delete();

            $unreadCount = ContactSubmission::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully.',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message.'
            ], 500);
        }
    }

    /**
     * Remove multiple contact submissions from database.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            ContactSubmission::whereIn('id', $ids)->delete();
            $unreadCount = ContactSubmission::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'message' => 'Selected messages deleted successfully.',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete selected messages.'
            ], 500);
        }
    }
}
