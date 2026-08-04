<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offers = Offer::with('products')->latest();
            return DataTables::of($offers)
                ->addColumn('checkbox', function ($offer) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $offer->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('products_count', function ($offer) {
                    return $offer->products->count();
                })
                ->addColumn('status', function ($offer) {
                    return '<div class="form-check form-switch text-center">
                                <input class="form-check-input" type="checkbox" role="switch" onchange="changeStatus(' . $offer->id . ')" ' . ($offer->status ? 'checked' : '') . '>
                            </div>';
                })
                ->addColumn('action', function ($offer) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" onclick="editOffer(' . $offer->id . ')" class="btn btn-soft-info btn-sm" title="Edit">
                                <i class="ri-pencil-line fs-14"></i>
                            </button>
                            <button type="button" onclick="deleteData(\'' . route('backend.offer.destroy', $offer->id) . '\')" class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="ri-delete-bin-line fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }
        
        $products = Product::where('status', 'active')->get();
        return view("backend.layout.offers.index", compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'sub_title'        => 'nullable|string|max:500',
            'badge'            => 'nullable|string|max:100',
            'promo_code'       => 'nullable|string|max:100',
            'bg_color'         => 'required|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'expire_date'      => 'nullable|date',
            'position'         => 'required|in:top_banner,normal_deal',
            'products'         => 'nullable|array',
            'products.*'       => 'exists:products,id',
            'banner_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        try {
            $data = $request->except(['products', 'banner_image']);
            
            if ($request->hasFile('banner_image')) {
                $data['banner_image'] = fileUpload($request->file('banner_image'), 'offers/banners');
            }

            $offer = Offer::create($data);

            if ($request->filled('products')) {
                $offer->products()->sync($request->products);
            }

            return response()->json(['success' => true, 'message' => 'Offer campaign created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $offer = Offer::with('products')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $offer]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'sub_title'        => 'nullable|string|max:500',
            'badge'            => 'nullable|string|max:100',
            'promo_code'       => 'nullable|string|max:100',
            'bg_color'         => 'required|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'expire_date'      => 'nullable|date',
            'position'         => 'required|in:top_banner,normal_deal',
            'products'         => 'nullable|array',
            'products.*'       => 'exists:products,id',
            'banner_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        try {
            $offer = Offer::findOrFail($id);
            $data = $request->except(['products', 'banner_image']);

            if ($request->hasFile('banner_image')) {
                if ($offer->banner_image) {
                    fileDelete($offer->banner_image);
                }
                $data['banner_image'] = fileUpload($request->file('banner_image'), 'offers/banners');
            }

            $offer->update($data);

            // Sync relation
            $products = $request->input('products', []);
            $offer->products()->sync($products);

            return response()->json(['success' => true, 'message' => 'Offer campaign updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $offer = Offer::findOrFail($id);
            if ($offer->banner_image) {
                fileDelete($offer->banner_image);
            }
            $offer->products()->detach();
            $offer->delete();
            return response()->json(['success' => true, 'message' => 'Offer campaign deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete offer campaign']);
        }
    }

    public function status($id)
    {
        try {
            $offer = Offer::findOrFail($id);
            $offer->status = !$offer->status;
            $offer->save();
            return response()->json(['success' => true, 'message' => 'Offer status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status']);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $offers = Offer::whereIn('id', $ids)->get();
            foreach ($offers as $offer) {
                if ($offer->banner_image) {
                    fileDelete($offer->banner_image);
                }
                $offer->products()->detach();
                $offer->delete();
            }
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}
