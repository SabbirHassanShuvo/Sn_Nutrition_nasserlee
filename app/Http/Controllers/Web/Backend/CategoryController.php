<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::latest();
            return DataTables::of($categories)
                ->addColumn('checkbox', function ($category) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $category->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('image', function ($category) {
                    $img = $category->image ? asset($category->image) : 'https://ui-avatars.com/api/?name=' . urlencode($category->name);
                    return '<img src="' . $img . '" alt="' . $category->name . '" width="50" height="50" class="rounded shadow-sm border">';
                })
                ->addColumn('color', function ($category) {
                    $color = $category->color ?? '#FF8000';
                    return '<div style="width: 25px; height: 25px; border-radius: 50%; background-color: ' . $color . '; margin: 0 auto; border: 1px solid #ddd;" title="' . $color . '"></div>';
                })
                ->addColumn('status', function ($category) {
                    return getStatusHTML($category, '#198754', $category->status == 'active' ? '26px' : '2px');
                })
                ->addColumn('action', function ($category) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="'.route('backend.category.edit', $category->id).'" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.category.destroy', $category->id) . '\')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'image', 'color', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.categories.index");
    }

    public function create()
    {
        return view("backend.layout.categories.form");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'color']);
        $data['slug'] = makeSlug(Category::class, $request->name);
        $data['status'] = 'active';

        if ($request->hasFile('image')) {
            $data['image'] = fileUpload($request->file('image'), 'categories');
        }

        Category::create($data);

        return redirect()->route('backend.category.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }
        return view('backend.layout.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'color']);
        if ($category->name != $request->name) {
            $data['slug'] = makeSlug(Category::class, $request->name);
        }

        if ($request->hasFile('image')) {
            $data['image'] = fileUpdate($request->file('image'), 'categories', $category->image);
        }

        $category->update($data);

        return redirect()->route('backend.category.index')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->image) {
                fileDelete($category->image);
            }
            $category->delete();
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
        $category = Category::findOrFail($id);
        $category->status = $category->status == 'active' ? 'inactive' : 'active';
        $category->save();
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
            $categories = Category::whereIn('id', $ids)->get();
            foreach ($categories as $category) {
                if ($category->image) {
                    fileDelete($category->image);
                }
                $category->delete();
            }
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}