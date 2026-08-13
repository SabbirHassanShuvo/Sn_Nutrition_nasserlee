<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class SenditWebhookController extends BaseController
{
    /**
     * Handle shipment status webhook from Sendit.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Sendit Webhook Received: ', $payload);

        // Sendit webhooks payload structure may vary slightly, we safely look for 'code' and 'status' at multiple levels.
        $code = $payload['code'] ?? $payload['data']['code'] ?? null;
        $status = $payload['status'] ?? $payload['data']['status'] ?? null;

        if (!$code || !$status) {
            return $this->sendError('Invalid webhook payload. Missing delivery code or status.', [], 400);
        }

        // Find the order that has this tracking/delivery code
        $order = Order::where('sendit_delivery_code', $code)->first();

        if (!$order) {
            Log::warning("Sendit Webhook: Order with Sendit code {$code} not found.");
            return $this->sendError('Order not found.', [], 404);
        }

        // Update the shipment status columns
        $order->update([
            'sendit_delivery_status' => $status
        ]);

        // Map Sendit status to Order status
        $mappedStatus = null;
        switch (strtoupper($status)) {
            case 'DELIVERED':
                $mappedStatus = 'delivered';
                break;
            case 'CANCELED':
            case 'REJECTED':
                $mappedStatus = 'cancelled';
                break;
            case 'DELIVERING':
            case 'DISTRIBUTED':
            case 'TRANSIT':
                $mappedStatus = 'shipping';
                break;
            case 'PENDING':
            case 'TO_PREPARE':
            case 'TO_PICKUP':
            case 'PICKEDUP':
            case 'WAREHOUSE':
                $mappedStatus = 'processing';
                break;
        }

        if ($mappedStatus) {
            $order->update([
                'status' => $mappedStatus
            ]);
            
            Log::info("Order #{$order->order_number} status updated to '{$mappedStatus}' via Sendit Webhook.");
        }

        return $this->sendResponse([
            'order_number' => $order->order_number,
            'sendit_code' => $code,
            'sendit_status' => $status,
            'order_status' => $order->status
        ], 'Webhook processed successfully.');
    }
}
