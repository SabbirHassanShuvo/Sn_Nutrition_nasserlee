<?php

namespace App\Http\Controllers\Web\Backend\Cms;

use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = FaqCategory::latest('priority')->get();
            return DataTables::of($categories)
                ->addColumn('checkbox', function ($cat) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $cat->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('name', fn($cat) => $cat->name)
                ->addColumn('priority', fn($cat) => $cat->priority)
                ->addColumn('faq_count', fn($cat) => $cat->faqs()->count())
                ->addColumn('status', function ($cat) {
                    $checked = $cat->status == FaqCategory::STATUS['ACTIVE'] ? 'checked' : '';
                    return '<div class="form-check form-switch mb-2">
                                <input class="form-check-input" onclick="statusFaqCategory(' . $cat->id . ')" type="checkbox" ' . $checked . '>
                            </div>';
                })
                ->addColumn('action', function ($cat) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <button onclick="editFaqCategory(' . $cat->id . ', \'' . addslashes($cat->name) . '\', ' . $cat->priority . ')" type="button" class="btn btn-soft-info btn-sm">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </button>
                            <button type="button" onclick="deleteData(\'' . route('backend.faq-category.destroy', $cat->id) . '\')" class="btn btn-soft-danger btn-sm">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.cms.faq_categories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:faq_categories,name',
            'priority' => 'required|integer|min:1',
        ]);

        $validated['slug']   = Str::slug($validated['name']);
        $validated['status'] = FaqCategory::STATUS['ACTIVE'];

        FaqCategory::create($validated);

        return redirect()->back()->with('success', 'FAQ Category created successfully.');
    }

    public function update(Request $request, FaqCategory $faqCategory)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:faq_categories,name,' . $faqCategory->id,
            'priority' => 'required|integer|min:1',
        ]);

        if ($faqCategory->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $faqCategory->update($validated);

        return redirect()->back()->with('success', 'FAQ Category updated successfully.');
    }

    public function destroy(FaqCategory $faqCategory)
    {
        try {
            $faqCategory->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete.']);
        }
    }

    public function status($id)
    {
        $cat = FaqCategory::findOrFail($id);
        $cat->status = $cat->status == FaqCategory::STATUS['ACTIVE']
            ? FaqCategory::STATUS['INACTIVE']
            : FaqCategory::STATUS['ACTIVE'];
        $cat->save();

        return response()->json(['success' => true, 'message' => 'Status updated.']);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            FaqCategory::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}
