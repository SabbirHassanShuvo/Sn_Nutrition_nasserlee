<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends BaseController
{
    /**
     * Display a listing of orders for logged in user.
     * Supported status values: 'all', 'processing', 'shipped', 'delivered', 'canceled'
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Order::with(['items.product'])->where('user_id', $user->id);

            // Search by order_number
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where('order_number', 'like', "%{$search}%");
            }

            // Filter by status
            if ($request->filled('status')) {
                $status = strtolower(trim($request->status));
                if ($status !== 'all') {
                    if ($status === 'processing') {
                        $query->whereIn('status', ['pending', 'processing']);
                    } elseif (in_array($status, ['shipped', 'shipping', 'in_transit', 'in-transit'])) {
                        $query->whereIn('status', ['shipping', 'shipped']);
                    } elseif ($status === 'delivered') {
                        $query->where('status', 'delivered');
                    } elseif (in_array($status, ['canceled', 'cancelled'])) {
                        $query->whereIn('status', ['cancelled', 'canceled']);
                    } else {
                        $query->where('status', $status);
                    }
                }
            }

            // Calculate summary metrics before pagination
            $totalOrdersCount = (clone $query)->count();
            $totalRevenue = round((float) (clone $query)->sum('total'), 2);
            $totalCommission = round((float) (clone $query)->sum('commission_amount'), 2);

            $perPage = (int) $request->input('per_page', 8);
            if ($perPage <= 0) {
                $perPage = 8;
            }

            $orders = $query->latest()->paginate($perPage);

            $transformedOrders = $orders->getCollection()->map(function ($order) {
                $itemsCount = $order->items->sum('quantity');
                
                // Get thumbnails for preview (up to 4 unique product images)
                $thumbnails = $order->items->map(function ($item) {
                    return $item->product && $item->product->main_image
                        ? asset($item->product->main_image)
                        : null;
                })->filter()->unique()->values()->take(4)->toArray();

                $statusLabel = match (strtolower($order->status)) {
                    'pending', 'processing' => 'Processing',
                    'shipping', 'shipped' => 'In Transit',
                    'delivered' => 'Delivered',
                    'cancelled', 'canceled' => 'Canceled',
                    default => ucfirst($order->status),
                };

                $totalFloat = round((float) $order->total, 2);

                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'status_label' => $statusLabel,
                    'date' => $order->created_at ? $order->created_at->format('M d, Y') : null,
                    'total' => $totalFloat,
                    'total_formatted' => number_format($totalFloat, 2, '.', '') . ' MAD',
                    'currency' => 'MAD',
                    'items_count' => $itemsCount,
                    'items_count_label' => $itemsCount === 1 ? '1 item' : "{$itemsCount} items",
                    'thumbnails' => $thumbnails,
                    'actions' => [
                        'can_invoice' => true,
                        'can_view_details' => true,
                    ],
                ];
            })->values();

            $data = [
                'total_order' => $totalOrdersCount,
                'total_revenue' => $totalRevenue,
                'total_commission' => $totalCommission,
                'orders' => $transformedOrders,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'prev_page_url' => $orders->previousPageUrl(),
                    'next_page_url' => $orders->nextPageUrl(),
                ]
            ];

            return $this->sendResponse($data, 'Orders fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch orders.', $e->getMessage());
        }
    }

    /**
     * Display single order details.
     */
    public function show($identifier)
    {
        try {
            $user = Auth::user();
            $order = Order::with(['items.product'])
                ->where('user_id', $user->id)
                ->where(function ($q) use ($identifier) {
                    $q->where('order_number', $identifier)->orWhere('id', $identifier);
                })
                ->first();

            if (!$order) {
                return $this->sendError('Order not found.', [], 404);
            }

            $items = $order->items->map(function ($item) {
                $product = $item->product;
                return [
                    'item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $product ? $product->name : 'Product',
                    'slug' => $product ? $product->slug : null,
                    'image' => $product && $product->main_image ? asset($product->main_image) : null,
                    'price' => (float) $item->price,
                    'quantity' => (int) $item->quantity,
                    'total' => (float) ($item->price * $item->quantity),
                ];
            });

            $statusLabel = match (strtolower($order->status)) {
                'pending', 'processing' => 'Processing',
                'shipping', 'shipped' => 'In Transit',
                'delivered' => 'Delivered',
                'cancelled', 'canceled' => 'Canceled',
                default => ucfirst($order->status),
            };

            $data = [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $statusLabel,
                'date' => $order->created_at ? $order->created_at->format('M d, Y h:i A') : null,
                'shipping_address' => [
                    'full_name' => $order->full_name,
                    'phone' => $order->phone,
                    'email' => $order->email,
                    'address' => $order->address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code,
                    'country' => $order->country,
                ],
                'delivery_method' => $order->delivery_method,
                'payment_method' => $order->payment_method,
                'items' => $items,
                'summary' => [
                    'subtotal' => (float) $order->subtotal,
                    'delivery_fee' => (float) $order->delivery_fee,
                    'discount' => (float) $order->discount,
                    'total' => (float) $order->total,
                    'currency' => 'MAD',
                ],
            ];

            return $this->sendResponse($data, 'Order details fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch order details.', $e->getMessage());
        }
    }

    /**
     * Get Invoice PDF URL for an order.
     */
    public function invoice(Request $request, $identifier)
    {
        try {
            $user = Auth::user();
            $order = Order::where('user_id', $user->id)
                ->where(function ($q) use ($identifier) {
                    $q->where('order_number', $identifier)->orWhere('id', $identifier);
                })
                ->first();

            if (!$order) {
                return $this->sendError('Order not found.', [], 404);
            }

            $token = $request->bearerToken();
            $pdfUrl = url("/api/user/orders/{$order->order_number}/invoice-view") . ($token ? '?token=' . $token : '');

            return $this->sendResponse([
                'order_number' => $order->order_number,
                'invoice_number' => 'INV-' . str_replace('SN-', '', $order->order_number),
                'invoice_url' => $pdfUrl,
            ], 'Invoice URL generated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to generate invoice URL.', $e->getMessage());
        }
    }

    /**
     * Render Printable / Downloadable Invoice View.
     */
    public function invoiceView(Request $request, $identifier)
    {
        try {
            $user = Auth::user();
            $order = Order::with(['user', 'items.product'])->where('user_id', $user->id)
                ->where(function ($q) use ($identifier) {
                    $q->where('order_number', $identifier)->orWhere('id', $identifier);
                })
                ->first();

            if (!$order) {
                return abort(404, 'Order not found.');
            }

            return response()->view('invoices.order_invoice', compact('order'));
        } catch (\Exception $e) {
            return abort(500, 'Failed to load invoice view.');
        }
    }
}
