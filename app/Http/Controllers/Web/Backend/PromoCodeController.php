<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $promoCodes = PromoCode::latest();
            return DataTables::of($promoCodes)
                ->addIndexColumn()
                ->addColumn('status', function ($promoCode) {
                    return '<div class="form-check form-switch text-center">
                                <input class="form-check-input" type="checkbox" role="switch" onchange="changeStatus('.$promoCode->id.')" '.($promoCode->status ? 'checked' : '').'>
                            </div>';
                })
                ->addColumn('action', function ($promoCode) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" onclick="editPromoCode('.$promoCode->id.')" class="btn btn-soft-info btn-sm" title="Edit">
                                <i class="ri-pencil-line fs-14"></i>
                            </button>
                            <button type="button" onclick="deleteData(\'' . route('backend.promo-code.destroy', $promoCode->id) . '\')" class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="ri-delete-bin-line fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("backend.layout.promo_codes.index");
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
        ]);

        try {
            PromoCode::create($request->all());
            return response()->json(['success' => true, 'message' => 'Promo code created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return response()->json(['success' => true, 'data' => $promoCode]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $id,
            'discount_percent' => 'required|numeric|min:0|max:100',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
        ]);

        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->update($request->all());
            return response()->json(['success' => true, 'message' => 'Promo code updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->delete();
            return response()->json(['success' => true, 'message' => 'Promo code deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete promo code']);
        }
    }

    public function status($id)
    {
        try {
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->status = !$promoCode->status;
            $promoCode->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status']);
        }
    }
}
