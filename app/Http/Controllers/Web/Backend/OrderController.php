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
            $orders = Order::with(['user', 'affiliateLink.user'])->latest();
            return DataTables::of($orders)
                ->addColumn('checkbox', function ($order) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $order->id . '">';
                })
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
                ->addColumn('referred_by', function ($order) {
                    if ($order->affiliateLink && $order->affiliateLink->user) {
                        return '<div>
                            <h6 class="mb-0 fs-13 text-success">' . $order->affiliateLink->user->name . '</h6>
                            <p class="text-muted mb-0 fs-11">Comm: ' . number_format($order->commission_amount, 2) . ' MAD</p>
                        </div>';
                    }
                    return '<span class="text-muted">Direct</span>';
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
                ->addColumn('sendit_shipment', function ($order) {
                    return $order->sendit_delivery_code ?: '<span class="text-muted">Not Shipped</span>';
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
                ->rawColumns(['checkbox', 'order_number', 'customer', 'referred_by', 'amount', 'payment', 'sendit_shipment', 'status', 'action'])
                ->make(true);
        }
        $brands = \App\Models\Brand::where('status', 'active')->get();
        $products = \App\Models\Product::where('status', 'active')->with('brandData')->get();
        return view("backend.layout.orders.index", compact('brands', 'products'));
    }

    /**
     * Get list of districts/villes from Sendit.
     */
    public function getDistricts(Request $request)
    {
        $senditService = app(\App\Services\SenditService::class);
        $districts = $senditService->getDistricts($request->query('q'));
        return response()->json($districts);
    }

    /**
     * Store a manually created order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required|in:cod,bank_transfer',
            'preferred_delivery_date' => 'nullable|date',
            'delivery_fee' => 'required|numeric',
            'discount' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        try {
            return \DB::transaction(function () use ($request) {
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                $total = $subtotal + $request->delivery_fee - $request->discount;

                $fullName = trim($request->first_name . ' ' . $request->last_name);

                // Generate order number
                $orderNumber = 'SN-' . strtoupper(\Illuminate\Support\Str::random(6));

                $order = Order::create([
                    'user_id' => null, // Manual order
                    'order_number' => $orderNumber,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $request->delivery_fee,
                    'discount' => $request->discount,
                    'total' => $total,
                    'status' => 'pending',
                    'phone' => $request->phone,
                    'full_name' => $fullName,
                    'email' => $request->email,
                    'city' => $request->city,
                    'address' => $request->address,
                    'payment_method' => $request->payment_method,
                    'preferred_delivery_date' => $request->preferred_delivery_date,
                ]);

                foreach ($request->items as $item) {
                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                // Send shipment to Sendit
                $senditService = app(\App\Services\SenditService::class);
                $result = $senditService->createDelivery($order->load('items.product'));
                if (!$result['success']) {
                    throw new \Exception('Sendit API Error: ' . ($result['message'] ?? 'Unknown error'));
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully and sent to Sendit. Code: ' . $order->sendit_delivery_code
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 422);
        }
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user', 'bankTransfer', 'affiliateLink.user'])->find($id);
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

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            Order::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}
