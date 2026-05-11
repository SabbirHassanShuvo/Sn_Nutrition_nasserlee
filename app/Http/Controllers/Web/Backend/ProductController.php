<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductFeature;
use App\Models\ProductIngredient;
use App\Models\ProductUsage;
use App\Models\ProductNutrition;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::latest();
            return DataTables::of($products)
                ->addColumn('checkbox', function ($product) {
                    return '<div class="form-check text-center">
                                <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="'.$product->id.'">
                            </div>';
                })
                ->addIndexColumn()
                ->addColumn('image', function ($product) {
                    $img = $product->main_image ? asset($product->main_image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name);
                    return '<img src="' . $img . '" alt="' . $product->name . '" width="50" height="50" class="rounded">';
                })
                ->addColumn('name', function ($product) {
                    $categoryName = $product->category ? $product->category->name : 'No Category';
                    $brandName = 'No Brand';
                    if ($product->brandData) {
                        $brandName = $product->brandData->name . ' (<i class="ri-star-fill text-warning fs-11"></i> ' . $product->brandData->rating . ')';
                    }
                    return '<div>
                        <h6 class="mb-0 fs-14">' . $product->name . '</h6>
                        <p class="text-muted mb-0 fs-12">' . $categoryName . ' | ' . $brandName . '</p>
                    </div>';
                })
                ->addColumn('price', function ($product) {
                    return '<span class="fw-bold text-primary">' . $product->price . ' MAD</span>' . ($product->old_price ? ' <del class="text-muted fs-12">' . $product->old_price . '</del>' : '');
                })
                ->addColumn('status', function ($product) {
                    return getStatusHTML($product, '#198754', $product->status == 'active' ? '26px' : '2px');
                })
                ->addColumn('action', function ($product) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" onclick="viewProduct('.$product->id.')" class="btn btn-soft-primary btn-sm" data-bs-toggle="tooltip" title="View Details">
                                <i class="mdi mdi-eye fs-14"></i>
                            </button>
                            <a href="'.route('backend.product.edit', $product->id).'" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.product.destroy', $product->id) . '\')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'image', 'name', 'price', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.products.index");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brandData', 'features', 'ingredients', 'nutrition', 'usages']);
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view("backend.layout.products.form", compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name', 'short_description', 'full_description', 'price', 'old_price', 'discount_percent',
                'brand_id', 'category_id', 'form', 'servings', 'quantity'
            ]);

            // Logic to calculate price based on discount_percent if provided
            if ($request->filled('old_price') && $request->filled('discount_percent')) {
                $data['price'] = $request->old_price - ($request->old_price * $request->discount_percent / 100);
            }

            
            $data['slug'] = makeSlug(Product::class, $request->name);
            $data['is_vegan'] = $request->has('is_vegan');
            $data['in_stock'] = $request->has('in_stock');
            $data['is_popular'] = $request->has('is_popular');
            $data['status'] = 'active';

            if ($request->hasFile('main_image')) {
                $data['main_image'] = fileUpload($request->file('main_image'), 'products');
            }

            if ($request->hasFile('gallery_images')) {
                $gallery = [];
                foreach ($request->file('gallery_images') as $file) {
                    $gallery[] = fileUpload($file, 'products');
                }
                $data['gallery_images'] = $gallery;
            }

            $product = Product::create($data);

            if ($request->has('features')) {
                foreach ($request->features as $feature) {
                    if (!empty($feature['title'])) {
                        $product->features()->create($feature);
                    }
                }
            }

            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $ingredient) {
                    if (!empty($ingredient['title'])) {
                        $product->ingredients()->create($ingredient);
                    }
                }
            }

            if ($request->has('nutrition')) {
                foreach ($request->nutrition as $nutrition) {
                    if (!empty($nutrition['name'])) {
                        $product->nutrition()->create($nutrition);
                    }
                }
            }

            if ($request->has('usages')) {
                foreach ($request->usages as $usage) {
                    if (!empty($usage['content'])) {
                        $product->usages()->create($usage);
                    }
                }
            }

            DB::commit();
            return redirect()->route('backend.product.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['features', 'ingredients', 'nutrition', 'usages']);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('backend.layout.products.form', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name', 'short_description', 'full_description', 'price', 'old_price', 'discount_percent',
                'brand_id', 'category_id', 'form', 'servings', 'quantity'
            ]);

            // Logic to calculate price based on discount_percent if provided
            if ($request->filled('old_price') && $request->filled('discount_percent')) {
                $data['price'] = $request->old_price - ($request->old_price * $request->discount_percent / 100);
            }


            if ($product->name != $request->name) {
                $data['slug'] = makeSlug(Product::class, $request->name);
            }
            
            $data['is_vegan'] = $request->has('is_vegan');
            $data['in_stock'] = $request->has('in_stock');
            $data['is_popular'] = $request->has('is_popular');

            if ($request->hasFile('main_image')) {
                $data['main_image'] = fileUpdate($request->file('main_image'), 'products', $product->main_image);
            }

            if ($request->hasFile('gallery_images')) {
                $gallery = $product->gallery_images ?? [];
                foreach ($request->file('gallery_images') as $file) {
                    $gallery[] = fileUpload($file, 'products');
                }
                $data['gallery_images'] = $gallery;
            }

            $product->update($data);

            $product->features()->delete();
            if ($request->has('features')) {
                foreach ($request->features as $feature) {
                    if (!empty($feature['title'])) {
                        $product->features()->create($feature);
                    }
                }
            }

            $product->ingredients()->delete();
            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $ingredient) {
                    if (!empty($ingredient['title'])) {
                        $product->ingredients()->create($ingredient);
                    }
                }
            }

            $product->nutrition()->delete();
            if ($request->has('nutrition')) {
                foreach ($request->nutrition as $nutrition) {
                    if (!empty($nutrition['name'])) {
                        $product->nutrition()->create($nutrition);
                    }
                }
            }

            $product->usages()->delete();
            if ($request->has('usages')) {
                foreach ($request->usages as $usage) {
                    if (!empty($usage['content'])) {
                        $product->usages()->create($usage);
                    }
                }
            }

            DB::commit();
            return redirect()->route('backend.product.index')->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->main_image) {
                fileDelete($product->main_image);
            }
            if ($product->gallery_images) {
                foreach ($product->gallery_images as $img) {
                    fileDelete($img);
                }
            }
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Data.',
            ]);
        }
    }

    public function status($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status == 'active' ? 'inactive' : 'active';
        $product->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated',
        ]);
    }
}
