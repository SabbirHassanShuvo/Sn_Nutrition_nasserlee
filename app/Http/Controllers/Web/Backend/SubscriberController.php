<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the subscribers.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subscribers = Subscriber::latest()->get();
            return DataTables::of($subscribers)
                ->addColumn('checkbox', function ($subscriber) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $subscriber->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('email', fn($subscriber) => $subscriber->email)
                ->addColumn('created_at', fn($subscriber) => $subscriber->created_at->format('M d, Y h:i A'))
                ->addColumn('status', function ($subscriber) {
                    if ($subscriber->is_read) {
                        return '<span class="badge bg-success-subtle text-success">Read</span>';
                    }
                    return '<span class="badge bg-danger-subtle text-danger">Unread</span>';
                })
                ->addColumn('action', function ($subscriber) {
                    $readBtn = '';
                    if (!$subscriber->is_read) {
                        $readBtn = '<button type="button" onclick="markAsRead(' . $subscriber->id . ')" class="btn btn-success btn-sm me-1 btn-read-' . $subscriber->id . '">
                            <i class="ri-mail-open-line align-bottom me-1"></i> Read
                        </button>';
                    }
                    return '
                        <div class="d-flex align-items-center">
                            ' . $readBtn . '
                            <button type="button" onclick="deleteData(\'' . route('backend.subscribers.destroy', $subscriber->id) . '\')" class="btn btn-danger btn-sm">
                                <i class="ri-delete-bin-line align-bottom"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.subscribers.index');
    }

    /**
     * Mark the specified subscriber as read.
     */
    public function markAsRead($id)
    {
        try {
            $subscriber = Subscriber::findOrFail($id);
            if (!$subscriber->is_read) {
                $subscriber->is_read = true;
                $subscriber->save();
            }

            $unreadCount = Subscriber::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'message' => 'Subscriber marked as read successfully.',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark subscriber as read.'
            ], 500);
        }
    }

    /**
     * Remove the specified subscriber from database.
     */
    public function destroy($id)
    {
        try {
            $subscriber = Subscriber::findOrFail($id);
            $subscriber->delete();

            $unreadCount = Subscriber::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'message' => 'Subscriber deleted successfully.',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscriber.'
            ], 500);
        }
    }

    /**
     * Remove multiple subscribers from database.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            Subscriber::whereIn('id', $ids)->delete();
            $unreadCount = Subscriber::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'message' => 'Selected subscribers deleted successfully.',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete selected subscribers.'
            ], 500);
        }
    }
}
