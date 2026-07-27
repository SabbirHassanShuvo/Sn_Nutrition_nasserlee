<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends BaseController
{
    /**
     * Display a listing of user addresses.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $addresses = UserAddress::where('user_id', $user->id)
                ->orderBy('is_default', 'desc')
                ->latest()
                ->get();

            return $this->sendResponse($addresses, 'User addresses fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch user addresses.', $e->getMessage());
        }
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:100',
            'label' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address_line' => 'required_without_all:street_address,address|nullable|string|max:500',
            'street_address' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
            'set_as_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = Auth::user();
            $isDefault = $request->boolean('is_default') || $request->boolean('set_as_default');

            // If user has no existing addresses, force the first address to be default
            $existingCount = UserAddress::where('user_id', $user->id)->count();
            if ($existingCount === 0) {
                $isDefault = true;
            }

            if ($isDefault) {
                UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
            }

            $title = $request->input('title', $request->input('label', 'Home'));
            $addressLine = $request->input('address_line', $request->input('street_address', $request->input('address')));
            $fullName = $request->input('full_name', $user->name);
            $phone = $request->input('phone', optional($user->profile)->phone);

            $address = UserAddress::create([
                'user_id' => $user->id,
                'title' => $title ?: 'Home',
                'full_name' => $fullName,
                'phone' => $phone,
                'address_line' => $addressLine,
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'country' => $request->input('country', 'Morocco'),
                'is_default' => $isDefault,
            ]);

            return $this->sendResponse($address, 'Address created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to create address.', $e->getMessage());
        }
    }

    /**
     * Display the specified address.
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id)->where('id', $id)->first();

            if (!$address) {
                return $this->sendError('Address not found.', [], 404);
            }

            return $this->sendResponse($address, 'Address fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch address.', $e->getMessage());
        }
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:100',
            'label' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address_line' => 'nullable|string|max:500',
            'street_address' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
            'set_as_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id)->where('id', $id)->first();

            if (!$address) {
                return $this->sendError('Address not found.', [], 404);
            }

            $isDefault = $request->has('set_as_default') ? $request->boolean('set_as_default') : ($request->has('is_default') ? $request->boolean('is_default') : null);

            if ($isDefault !== null && $isDefault) {
                UserAddress::where('user_id', $user->id)->where('id', '!=', $id)->update(['is_default' => false]);
                $address->is_default = true;
            }

            if ($request->has('title') || $request->has('label')) {
                $address->title = $request->input('title', $request->input('label'));
            }

            if ($request->has('address_line') || $request->has('street_address') || $request->has('address')) {
                $address->address_line = $request->input('address_line', $request->input('street_address', $request->input('address')));
            }

            if ($request->has('full_name')) {
                $address->full_name = $request->full_name;
            }

            if ($request->has('phone')) {
                $address->phone = $request->phone;
            }

            if ($request->has('city')) {
                $address->city = $request->city;
            }

            if ($request->has('postal_code')) {
                $address->postal_code = $request->postal_code;
            }

            if ($request->has('country')) {
                $address->country = $request->country;
            }

            $address->save();

            return $this->sendResponse($address, 'Address updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to update address.', $e->getMessage());
        }
    }

    /**
     * Remove the specified address.
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id)->where('id', $id)->first();

            if (!$address) {
                return $this->sendError('Address not found.', [], 404);
            }

            $wasDefault = $address->is_default;
            $address->delete();

            // If deleted address was default, set the latest remaining address as default
            if ($wasDefault) {
                $firstAddress = UserAddress::where('user_id', $user->id)->latest()->first();
                if ($firstAddress) {
                    $firstAddress->update(['is_default' => true]);
                }
            }

            return $this->sendResponse(null, 'Address deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete address.', $e->getMessage());
        }
    }

    /**
     * Set a specific address as default.
     */
    public function setDefault($id)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id)->where('id', $id)->first();

            if (!$address) {
                return $this->sendError('Address not found.', [], 404);
            }

            UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            return $this->sendResponse($address, 'Default address set successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to set default address.', $e->getMessage());
        }
    }
}
