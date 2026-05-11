<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Order;
use App\Models\BankTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with('user')->latest();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('order_number', function ($order) {
                    return '<span class="fw-bold">' . $order->order_number . '</span>';
                })
                ->addColumn('customer', function ($order) {
                    return '<div>
                        <h6 class="mb-0 fs-14">' . ($order->full_name ?? ($order->user ? $order->user->name : 'Guest')) . '</h6>
                        <p class="text-muted mb-0 fs-12">' . $order->email . '</p>
                    </div>';
                })
                ->addColumn('amount', function ($order) {
                    return '<span class="fw-bold">' . number_format($order->total, 2) . ' MAD</span>';
                })
                ->addColumn('payment', function ($order) {
                    $method = strtoupper(str_replace('_', ' ', $order->payment_method));
                    $badge = $order->payment_method == 'cod' ? 'bg-info' : 'bg-warning';
                    $html = '<span class="badge ' . $badge . '">' . $method . '</span>';
                    
                    if ($order->payment_method == 'bank_transfer' && $order->bankTransfer) {
                        $pStatus = $order->bankTransfer->status;
                        $pBadge = $pStatus == 'approved' ? 'bg-success' : ($pStatus == 'rejected' ? 'bg-danger' : 'bg-warning');
                        $html .= '<br><span class="badge ' . $pBadge . ' mt-1" style="font-size: 10px;">Payment: ' . ucfirst($pStatus) . '</span>';
                    }
                    return $html;
                })
                ->addColumn('status', function ($order) {
                    $colors = [
                        'pending' => 'bg-warning',
                        'processing' => 'bg-primary',
                        'shipping' => 'bg-info',
                        'delivered' => 'bg-success',
                        'cancelled' => 'bg-danger'
                    ];
                    $badge = $colors[$order->status] ?? 'bg-secondary';
                    return '<span class="badge ' . $badge . '">' . ucfirst($order->status) . '</span>';
                })
                ->addColumn('action', function ($order) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" onclick="viewOrder('.$order->id.')" class="btn btn-soft-primary btn-sm" title="View Details">
                                <i class="ri-eye-line fs-14"></i>
                            </button>
                            <button type="button" onclick="deleteData(\'' . route('backend.order.destroy', $order->id) . '\')" class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="ri-delete-bin-line fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['order_number', 'customer', 'amount', 'payment', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.orders.index");
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user', 'bankTransfer'])->find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated to ' . $request->status
        ]);
    }

    public function verifyPayment(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $order = Order::with('bankTransfer')->findOrFail($orderId);
        
        if (!$order->bankTransfer) {
            return response()->json(['success' => false, 'message' => 'No bank transfer found']);
        }

        $order->bankTransfer->update(['status' => $request->status]);

        if ($request->status === 'approved' && $order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment ' . $request->status . ' successfully'
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}
