<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::latest();
            return DataTables::of($brands)
                ->addColumn('checkbox', function ($brand) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $brand->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('image', function ($brand) {
                    $img = $brand->image ? asset($brand->image) : 'https://ui-avatars.com/api/?name=' . urlencode($brand->name);
                    return '<img src="' . $img . '" alt="' . $brand->name . '" width="50" height="50" class="rounded shadow-sm border">';
                })
                ->addColumn('name', function ($brand) {
                    return '<div>
                        <h6 class="mb-0 fs-14">' . $brand->name . '</h6>
                        <p class="text-muted mb-0 fs-12">' . ($brand->specialty ?? 'N/A') . ' | <i class="ri-star-fill text-warning"></i> ' . $brand->rating . '</p>
                    </div>';
                })
                ->addColumn('status', function ($brand) {
                    return getStatusHTML($brand, '#198754', $brand->status == 'active' ? '26px' : '2px');
                })
                ->addColumn('action', function ($brand) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="'.route('backend.brand.edit', $brand->id).'" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.brand.destroy', $brand->id) . '\')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'image', 'name', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.brands.index");
    }

    public function create()
    {
        return view("backend.layout.brands.form");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'specialty', 'rating']);
        $data['slug'] = makeSlug(Brand::class, $request->name);
        $data['status'] = 'active';

        if ($request->hasFile('image')) {
            $data['image'] = fileUpload($request->file('image'), 'brands');
        }

        Brand::create($data);

        return redirect()->route('backend.brand.index')->with('success', 'Brand created successfully');
    }

    public function edit(Brand $brand)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $brand
            ]);
        }
        return view('backend.layout.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'specialty', 'rating']);
        if ($brand->name != $request->name) {
            $data['slug'] = makeSlug(Brand::class, $request->name);
        }

        if ($request->hasFile('image')) {
            $data['image'] = fileUpdate($request->file('image'), 'brands', $brand->image);
        }

        $brand->update($data);

        return redirect()->route('backend.brand.index')->with('success', 'Brand updated successfully');
    }

    public function destroy(Brand $brand)
    {
        try {
            if ($brand->image) {
                fileDelete($brand->image);
            }
            $brand->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete.',
            ]);
        }
    }

    public function status($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->status = $brand->status == 'active' ? 'inactive' : 'active';
        $brand->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated',
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $brands = Brand::whereIn('id', $ids)->get();
            foreach ($brands as $brand) {
                if ($brand->image) {
                    fileDelete($brand->image);
                }
                $brand->delete();
            }
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}
