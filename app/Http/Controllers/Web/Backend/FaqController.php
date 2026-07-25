<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $faq = Faq::with('faqCategory')->latest('priority')->get();
            return DataTables::of($faq)
                ->addColumn('checkbox', function ($faq) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $faq->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('question', fn($faq) => $faq->question)
                ->addColumn('answer', fn($faq) => Str::limit(strip_tags($faq->answer), 100))
                ->addColumn('category', fn($faq) => $faq->faqCategory ? $faq->faqCategory->name : '—')
                ->addColumn('priority', fn($faq) => $faq->priority)
                ->addColumn('status', function ($data) {
                    return '<div class="form-check form-switch mb-2">
                                <input class="form-check-input" onclick="statusFaq(' . $data->id . ')" type="checkbox" ' . ($data->status == Faq::STATUS['ACTIVE'] ? 'checked' : '') . '>
                            </div>';
                })
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="editFaq(' . $data->id . ')" type="button" class="btn btn-info btn-sm">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button type="button" onclick="deleteData(\'' . route('backend.feature.faq.destroy', $data->id) . '\')" class="btn btn-danger btn-sm del">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    ';
                })
                ->setRowAttr([
                    'data-id' => fn($data) => $data->id,
                ])
                ->rawColumns(['checkbox', 'question', 'category', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.cms.faqs.index');
    }

    public function create()
    {
        $data['status']     = Faq::STATUS;
        $data['categories'] = FaqCategory::active()->orderBy('priority')->get();
        return view('backend.layout.cms.faqs.form', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question'        => 'required',
            'answer'          => 'required',
            'faq_category_id' => 'required|exists:faq_categories,id',
            'priority'        => 'required|min:1',
            'status'          => 'required',
        ]);

        Faq::create($validated);

        return redirect()
            ->route('backend.feature.faq.index')
            ->with('success', 'New FAQ successfully created.');
    }

    public function edit(Faq $faq)
    {
        return view('backend.layout.cms.faqs.form', [
            'faq'        => $faq,
            'status'     => Faq::STATUS,
            'categories' => FaqCategory::active()->orderBy('priority')->get(),
        ]);
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question'        => 'required',
            'answer'          => 'required',
            'faq_category_id' => 'required|exists:faq_categories,id',
            'priority'        => 'required|min:1',
            'status'          => 'required',
        ]);

        $faq->update($validated);

        return redirect()->route('backend.feature.faq.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Failed to delete the Data.']);
        }
    }

    public function status($id)
    {
        $faq         = Faq::findOrFail($id);
        $faq->status = !$faq->status;
        $faq->save();

        return response()->json(['success' => true, 'message' => 'Status updated.']);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            Faq::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected items.']);
        }
    }
}
