<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Batch;
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
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $product->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('image', function ($product) {
                    $img = $product->main_image ? asset($product->main_image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name);
                    return '<img src="' . $img . '" alt="' . $product->name . '" width="50" height="50" class="rounded">';
                })
                ->addColumn('name', function ($product) {
                    $brandName = 'No Brand';
                    if ($product->brandData) {
                        $brandName = $product->brandData->name . ' (<i class="ri-star-fill text-warning fs-11"></i> ' . $product->brandData->rating . ')';
                    }
                    return '<div>
                        <h6 class="mb-0 fs-14">' . $product->name . '</h6>
                        <p class="text-muted mb-0 fs-12">' . $brandName . '</p>
                    </div>';
                })
                ->addColumn('category', function ($product) {
                    return $product->category ? '<span class="badge bg-soft-success text-success fs-12">' . $product->category->name . '</span>' : '<span class="badge bg-soft-secondary text-secondary fs-12">No Category</span>';
                })
                ->addColumn('price', function ($product) {
                    return '<span class="fw-bold text-primary">' . $product->price . ' MAD</span>' . ($product->old_price ? ' <del class="text-muted fs-12">' . $product->old_price . '</del>' : '');
                })
                ->addColumn('stock', function ($product) {
                    if ($product->in_stock) {
                        return '<span class="badge bg-soft-info text-info fs-12">' . ($product->quantity ?? 0) . ' In Stock</span>';
                    } else {
                        return '<span class="badge bg-soft-danger text-danger fs-12">Out of Stock</span>';
                    }
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
                ->rawColumns(['checkbox', 'image', 'name', 'category', 'price', 'stock', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.products.index");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brandData', 'batch', 'features', 'ingredients', 'nutrition', 'usages']);
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $batches = Batch::where('status', 'active')->get();
        return view("backend.layout.products.form", compact('categories', 'brands', 'batches'));
    }

    public function store(Request $request)
    {
      
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'batches' => 'nullable|array',
            'batches.*.batch_id' => 'required|exists:batches,id',
            'batches.*.quantity' => 'required|integer|min:0',
            'batches.*.expiry_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name', 'short_description', 'full_description', 'price', 'old_price', 'discount_percent',
                'brand_id', 'category_id', 'batch_id', 'form', 'servings', 'quantity'
            ]);

            $data['discount_percent'] = $request->input('discount_percent') ?? 0;

            if ($request->filled('new_batch_name')) {
                $newBatch = Batch::create([
                    'name' => $request->new_batch_name,
                    'color' => $request->new_batch_color ?? '#3b82f6',
                    'status' => 'active',
                ]);
                $data['batch_id'] = $newBatch->id;
            }

            // Logic to calculate price based on discount_percent if provided
            if ($request->filled('old_price') && $request->filled('discount_percent')) {
                $data['price'] = $request->old_price - ($request->old_price * $request->discount_percent / 100);
            }

            
            $data['slug'] = makeSlug(Product::class, $request->name);
            $data['is_vegan'] = $request->has('is_vegan');
            $data['in_stock'] = $request->has('in_stock');
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

            // Calculate total quantity from batches array before creating product
            $totalQuantity = 0;
            if ($request->has('batches')) {
                foreach ($request->batches as $item) {
                    $totalQuantity += (int) ($item['quantity'] ?? 0);
                }
            }
            $data['quantity'] = $totalQuantity;

            $product = Product::create($data);

            // Sync batches
            $syncData = [];
            if ($request->has('batches')) {
                foreach ($request->batches as $item) {
                    if (!empty($item['batch_id'])) {
                        $qty = (int) ($item['quantity'] ?? 0);
                        $syncData[$item['batch_id']] = [
                            'quantity' => $qty,
                            'expiry_date' => !empty($item['expiry_date']) ? $item['expiry_date'] : null
                        ];
                    }
                }
            }
            $product->batches()->sync($syncData);

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
            \Illuminate\Support\Facades\Log::error('Product Store Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $product->load(['features', 'ingredients', 'nutrition', 'usages', 'batches']);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $batches = Batch::where('status', 'active')->get();
        return view('backend.layout.products.form', compact('product', 'categories', 'brands', 'batches'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'batches' => 'nullable|array',
            'batches.*.batch_id' => 'required|exists:batches,id',
            'batches.*.quantity' => 'required|integer|min:0',
            'batches.*.expiry_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name', 'short_description', 'full_description', 'price', 'old_price', 'discount_percent',
                'brand_id', 'category_id', 'batch_id', 'form', 'servings', 'quantity'
            ]);

            $data['discount_percent'] = $request->input('discount_percent') ?? 0;

            if ($request->filled('new_batch_name')) {
                $newBatch = Batch::create([
                    'name' => $request->new_batch_name,
                    'color' => $request->new_batch_color ?? '#3b82f6',
                    'status' => 'active',
                ]);
                $data['batch_id'] = $newBatch->id;
            }

            // Logic to calculate price based on discount_percent if provided
            if ($request->filled('old_price') && $request->filled('discount_percent')) {
                $data['price'] = $request->old_price - ($request->old_price * $request->discount_percent / 100);
            }


            if ($product->name != $request->name) {
                $data['slug'] = makeSlug(Product::class, $request->name);
            }
            
            $data['is_vegan'] = $request->has('is_vegan');
            $data['in_stock'] = $request->has('in_stock');

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

            // Calculate total quantity from batches array before updating product
            $totalQuantity = 0;
            if ($request->has('batches')) {
                foreach ($request->batches as $item) {
                    $totalQuantity += (int) ($item['quantity'] ?? 0);
                }
            }
            $data['quantity'] = $totalQuantity;

            $product->update($data);

            // Sync batches
            $syncData = [];
            if ($request->has('batches')) {
                foreach ($request->batches as $item) {
                    if (!empty($item['batch_id'])) {
                        $qty = (int) ($item['quantity'] ?? 0);
                        $syncData[$item['batch_id']] = [
                            'quantity' => $qty,
                            'expiry_date' => !empty($item['expiry_date']) ? $item['expiry_date'] : null
                        ];
                    }
                }
            }
            $product->batches()->sync($syncData);

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
            \Illuminate\Support\Facades\Log::error('Product Update Error: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'exception' => $e,
                'request_data' => $request->all()
            ]);
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

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $products = Product::whereIn('id', $ids)->get();
            foreach ($products as $product) {
                if ($product->main_image) {
                    fileDelete($product->main_image);
                }
                if ($product->gallery_images) {
                    foreach ($product->gallery_images as $img) {
                        fileDelete($img);
                    }
                }
                $product->delete();
            }
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}
