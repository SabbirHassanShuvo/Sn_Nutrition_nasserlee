<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use Exception;

class SenditService
{
    protected string $baseUrl;
    protected string $publicKey;
    protected string $privateKey;
    protected int $defaultPickupDistrictId;

    public function __construct()
    {
        $this->baseUrl = config('services.sendit.base_url', 'https://app.sendit.ma/api/v1/');
        $this->publicKey = config('services.sendit.public_key', '');
        $this->privateKey = config('services.sendit.private_key', '');
        $this->defaultPickupDistrictId = (int) config('services.sendit.default_pickup_district_id', 46);
    }

    /**
     * Authenticate and retrieve bearer token.
     */
    public function getAccessToken(): ?string
    {
        $cacheKey = 'sendit_api_token';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::post($this->baseUrl . 'login', [
                'public_key' => $this->publicKey,
                'secret_key' => $this->privateKey,
            ]);

            if ($response->successful()) {
                $token = $response->json('data.token');
                if ($token) {
                    // Cache the token for 23 hours (usually JWT tokens are valid for 24h)
                    Cache::put($cacheKey, $token, now()->addHours(23));
                    return $token;
                }
            }

            Log::error('Sendit Login Failed: ' . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error('Sendit Login Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch district ID by matching the city name.
     */
    public function getDistrictIdByCityName(?string $cityName): int
    {
        if (empty($cityName)) {
            return $this->defaultPickupDistrictId;
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return $this->defaultPickupDistrictId;
        }

        try {
            $response = Http::withToken($token)
                ->get($this->baseUrl . 'districts', [
                    'querystring' => $cityName,
                ]);

            if ($response->successful()) {
                $districts = $response->json('data');
                if (!empty($districts) && is_array($districts)) {
                    // Try to find exact or close match
                    foreach ($districts as $district) {
                        if (isset($district['id']) && isset($district['ville'])) {
                            if (strcasecmp(trim($district['ville']), trim($cityName)) === 0) {
                                return (int) $district['id'];
                            }
                        }
                    }
                    // If no exact match, fallback to the first returned district's id
                    if (isset($districts[0]['id'])) {
                        return (int) $districts[0]['id'];
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Sendit getDistricts Exception: ' . $e->getMessage());
        }

        return $this->defaultPickupDistrictId;
    }

    /**
     * Register a new delivery/shipment on Sendit.
     */
    public function createDelivery(Order $order): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Authentication failed with Sendit API.'
            ];
        }

        // Determine destination district ID
        $districtId = $this->getDistrictIdByCityName($order->city);

        // Build product list format 'code:qty;code2:qty'
        $productsStr = $order->items->map(function ($item) {
            $code = $item->product->slug ?: $item->product->id;
            $code = str_replace([':', ';'], '_', $code); // Clean delimiters
            return $code . ':' . $item->quantity;
        })->implode(';');

        // For non-COD payment methods, collect amount should be 0
        $amountToCollect = strtolower($order->payment_method) === 'cod' ? (float) $order->total : 0.0;

        // Format phone number (strip spaces, dashes, plus, and convert country code to 0)
        $phone = preg_replace('/\D/', '', $order->phone ?: '0600000000');
        if (str_starts_with($phone, '212')) {
            $phone = '0' . substr($phone, 3);
        }
        if (strlen($phone) === 9 && in_array(substr($phone, 0, 1), ['5', '6', '7'])) {
            $phone = '0' . $phone;
        }

        $payload = [
            'pickup_district_id' => $this->defaultPickupDistrictId,
            'district_id'        => $districtId,
            'name'               => $order->full_name ?: 'Client Order ' . $order->order_number,
            'amount'             => $amountToCollect,
            'address'            => $order->address ?: 'N/A',
            'phone'              => $phone,
            'comment'            => 'Order ' . $order->order_number . '. Preferred delivery: ' . ($order->preferred_delivery_date ?: 'N/A'),
            'reference'          => $order->order_number,
            'allow_open'         => 1,
            'allow_try'          => 1,
            'products_from_stock'=> 0,
            'products'           => $productsStr,
            'option_exchange'    => 0,
        ];

        try {
            $response = Http::withToken($token)
                ->post($this->baseUrl . 'deliveries', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract delivery code from the API response
                $deliveryCode = $data['data']['code'] ?? null;
                $deliveryStatus = $data['data']['status'] ?? null;

                if ($deliveryCode) {
                    $statusUpper = strtoupper($deliveryStatus);

                    // Update order details
                    $order->update([
                        'sendit_delivery_code' => $deliveryCode,
                        'sendit_delivery_status' => $statusUpper,
                        'status' => $statusUpper,
                    ]);

                    return [
                        'success' => true,
                        'delivery_code' => $deliveryCode,
                        'status' => $deliveryStatus,
                        'message' => 'Delivery created successfully on Sendit.'
                    ];
                }
            }

            Log::error('Sendit Create Delivery Failed for Order ' . $order->order_number . ': ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json('message') ?: 'Failed to create delivery on Sendit.'
            ];

        } catch (Exception $e) {
            Log::error('Sendit Create Delivery Exception for Order ' . $order->order_number . ': ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get list of districts/villes from Sendit.
     */
    public function getDistricts(string $queryString = null): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $response = Http::withToken($token)
                ->get($this->baseUrl . 'districts', $queryString ? ['querystring' => $queryString] : []);

            if ($response->successful()) {
                return $response->json('data') ?: [];
            }
        } catch (Exception $e) {
            Log::error('Sendit getDistricts list error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Cancel a delivery on Sendit.
     */
    public function cancelDelivery(string $code): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        try {
            // Sendit cancellation endpoint is typically DELETE /deliveries/{code}
            $response = Http::withToken($token)
                ->delete($this->baseUrl . 'deliveries/' . $code);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Delivery cancelled on Sendit successfully.'
                ];
            }

            Log::error('Sendit Cancel Delivery Failed for ' . $code . ': ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json('message') ?: 'Failed to cancel delivery on Sendit.'
            ];
        } catch (Exception $e) {
            Log::error('Sendit Cancel Delivery Exception for ' . $code . ': ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing delivery on Sendit.
     */
    public function updateDelivery(string $code, Order $order): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        // Get district ID based on the order's city
        $districtId = $this->getDistrictIdByCityName($order->city);

        // Format items string
        $productsStr = $order->items->map(function ($item) {
            $code = $item->product ? $item->product->sku : 'N/A';
            $code = str_replace([':', ';'], '_', $code);
            return $code . ':' . $item->quantity;
        })->implode(';');

        $amountToCollect = strtolower($order->payment_method) === 'cod' ? (float) $order->total : 0.0;

        $phone = preg_replace('/\D/', '', $order->phone ?: '0600000000');
        if (str_starts_with($phone, '212')) {
            $phone = '0' . substr($phone, 3);
        }
        if (strlen($phone) === 9 && in_array(substr($phone, 0, 1), ['5', '6', '7'])) {
            $phone = '0' . $phone;
        }

        $payload = [
            'pickup_district_id' => $this->defaultPickupDistrictId,
            'district_id'        => $districtId,
            'name'               => $order->full_name,
            'amount'             => $amountToCollect,
            'address'            => $order->address,
            'phone'              => $phone,
            'comment'            => 'Updated Order ' . $order->order_number . '. Preferred delivery: ' . ($order->preferred_delivery_date ?: 'N/A'),
            'reference'          => $order->order_number,
            'allow_open'         => 1,
            'allow_try'          => 1,
            'products_from_stock'=> 0,
            'products'           => $productsStr,
            'option_exchange'    => 0,
        ];

        try {
            // Sendit update endpoint is typically PUT /deliveries/{code}
            $response = Http::withToken($token)
                ->put($this->baseUrl . 'deliveries/' . $code, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Delivery updated on Sendit successfully.'
                ];
            }

            Log::error('Sendit Update Delivery Failed for ' . $code . ': ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json('message') ?: 'Failed to update delivery on Sendit.'
            ];
        } catch (Exception $e) {
            Log::error('Sendit Update Delivery Exception for ' . $code . ': ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
