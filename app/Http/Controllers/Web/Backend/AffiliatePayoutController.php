<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\PartnerProfile;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AffiliatePayoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PayoutRequest::with('user')->latest();

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addColumn('checkbox', function ($payout) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $payout->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('payout_number', function ($payout) {
                    return '<span class="fw-bold text-primary">' . htmlspecialchars($payout->payout_number) . '</span>';
                })
                ->addColumn('partner_name', function ($payout) {
                    $name = htmlspecialchars($payout->user ? $payout->user->name : ($payout->account_name ?: 'N/A'));
                    $email = htmlspecialchars($payout->user ? $payout->user->email : '');
                    return '<div><div class="fw-bold">' . $name . '</div><small class="text-muted">' . $email . '</small></div>';
                })
                ->addColumn('method', function ($payout) {
                    if ($payout->type == 'debit_card') {
                        return '<span class="badge bg-info-subtle text-info border border-info px-2 py-1"><i class="ri-bank-card-line me-1"></i> Debit Card</span>';
                    }
                    return '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1"><i class="ri-bank-line me-1"></i> Bank Transfer</span>';
                })
                ->addColumn('amount', function ($payout) {
                    return '<span class="fw-bold fs-15 text-success">$' . number_format($payout->amount, 2) . '</span>';
                })
                ->addColumn('payment_details', function ($payout) {
                    if ($payout->type == 'debit_card') {
                        $card = htmlspecialchars($payout->card_type ?: 'Visa');
                        $last4 = htmlspecialchars($payout->card_last_four ?: '2917');
                        $name = htmlspecialchars($payout->account_name);
                        return '<div class="fs-13"><strong>Card:</strong> ' . $card . ' **' . $last4 . '</div>' .
                               '<div class="fs-12 text-muted"><strong>Cardholder:</strong> ' . $name . '</div>';
                    } else {
                        $bank = htmlspecialchars($payout->bank_name ?: 'Bank Transfer');
                        $name = htmlspecialchars($payout->account_name);
                        $acc = htmlspecialchars($payout->account_number);
                        $html = '<div class="fs-13"><strong>Bank:</strong> ' . $bank . '</div>' .
                                '<div class="fs-12 text-muted"><strong>Account Name:</strong> ' . $name . '</div>' .
                                '<div class="fs-12 text-muted"><strong>Acc No:</strong> ' . $acc . '</div>';
                        if ($payout->routing_number) {
                            $html .= '<div class="fs-12 text-muted"><strong>Routing/SWIFT:</strong> ' . htmlspecialchars($payout->routing_number) . '</div>';
                        }
                        return $html;
                    }
                })
                ->addColumn('status', function ($payout) {
                    if ($payout->status == 'paid') {
                        return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1 fs-12">Paid</span>';
                    } elseif ($payout->status == 'pending') {
                        return '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1 fs-12">Pending</span>';
                    }
                    return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 fs-12">Rejected</span>';
                })
                ->addColumn('receipt_proof', function ($payout) {
                    if ($payout->receipt_image) {
                        $assetUrl = asset($payout->receipt_image);
                        $ext = strtolower(pathinfo($payout->receipt_image, PATHINFO_EXTENSION));

                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                            return '<a href="' . $assetUrl . '" target="_blank" data-bs-toggle="tooltip" title="Click to view full image">' .
                                   '<img src="' . $assetUrl . '" class="rounded border shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">' .
                                   '</a>';
                        } else {
                            return '<a href="' . $assetUrl . '" target="_blank" class="btn btn-soft-info btn-sm px-2 py-1 fs-12">' .
                                   '<i class="ri-file-pdf-line me-1"></i> PDF Proof</a>';
                        }
                    }
                    return '<span class="badge bg-light text-muted border px-2 py-1 fs-12">No Receipt</span>';
                })
                ->addColumn('date', function ($payout) {
                    return $payout->created_at ? $payout->created_at->format('M d, Y H:i') : '';
                })
                ->addColumn('action', function ($payout) {
                    $receiptUrl = $payout->receipt_image ? asset($payout->receipt_image) : '';
                    $processBtn = '<button type="button" class="btn btn-primary btn-sm px-2" onclick="openProcessModal(' .
                        "{$payout->id}, " .
                        "'" . addslashes(htmlspecialchars($payout->payout_number)) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->type)) . "', " .
                        "'" . number_format($payout->amount, 2) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->bank_name ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->account_name ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->account_number ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->routing_number ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->card_type ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->card_last_four ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->status)) . "', " .
                        "'" . addslashes(htmlspecialchars($payout->admin_notes ?? '')) . "', " .
                        "'" . addslashes(htmlspecialchars($receiptUrl)) . "'" .
                    ')" data-bs-toggle="tooltip" title="Process Payout"><i class="ri-upload-cloud-line me-1"></i> Process</button>';

                    $deleteBtn = '<button type="button" onclick="deleteData(\'' . route('backend.affiliate-payout.destroy', $payout->id) . '\')" class="btn btn-soft-danger btn-sm px-2" data-bs-toggle="tooltip" title="Delete"><i class="ri-delete-bin-line fs-14"></i></button>';

                    return '<div class="d-flex gap-1 justify-content-end">' . $processBtn . $deleteBtn . '</div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('payout_number', 'like', "%{$search}%")
                              ->orWhere('account_name', 'like', "%{$search}%")
                              ->orWhere('bank_name', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%")
                              ->orWhereHas('user', function ($u) use ($search) {
                                  $u->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                              });
                        });
                    }
                })
                ->rawColumns(['checkbox', 'payout_number', 'partner_name', 'method', 'amount', 'payment_details', 'status', 'receipt_proof', 'action'])
                ->make(true);
        }

        return view('backend.layout.affiliate.payouts.index');
    }

    public function process(Request $request, $id)
    {
        $payout = PayoutRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:paid,rejected',
            'receipt_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'admin_notes' => 'nullable|string',
        ]);

        $imagePath = $payout->receipt_image;

        if ($request->hasFile('receipt_image')) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            $file = $request->file('receipt_image');
            $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/payout_receipts');

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $file->move($uploadDir, $filename);
            $imagePath = 'uploads/payout_receipts/' . $filename;
        }

        $previousStatus = $payout->status;
        $newStatus = $request->status;

        $payout->update([
            'status' => $newStatus,
            'receipt_image' => $imagePath,
            'admin_notes' => $request->admin_notes,
            'paid_at' => $newStatus === 'paid' ? Carbon::now() : null,
        ]);

        if ($newStatus === 'paid' && $previousStatus !== 'paid') {
            $partnerProfile = PartnerProfile::where('user_id', $payout->user_id)->first();
            if ($partnerProfile) {
                $newPending = max(0, $partnerProfile->pending_payout - $payout->amount);
                $partnerProfile->update([
                    'pending_payout' => $newPending,
                    'last_paid_amount' => $payout->amount,
                ]);
            }
        }

        return redirect()->back()->with('t-success', 'Payout request processed successfully and receipt proof uploaded.');
    }

    public function destroy($id)
    {
        try {
            $payout = PayoutRequest::findOrFail($id);
            if ($payout->receipt_image && file_exists(public_path($payout->receipt_image))) {
                @unlink(public_path($payout->receipt_image));
            }
            $payout->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payout request deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payout request.',
            ]);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $payouts = PayoutRequest::whereIn('id', $ids)->get();
            foreach ($payouts as $payout) {
                if ($payout->receipt_image && file_exists(public_path($payout->receipt_image))) {
                    @unlink(public_path($payout->receipt_image));
                }
                $payout->delete();
            }

            return response()->json(['success' => true, 'message' => 'Selected payout requests deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected payout requests.']);
        }
    }
}
