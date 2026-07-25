<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Page::latest()->get();
            return DataTables::of($data)
                ->addColumn('checkbox', fn($page) => '<input type="checkbox" class="form-check-input row-checkbox" value="' . $page->id . '">')
                ->addIndexColumn()
                ->addColumn('page_title', fn($page) => $page->page_title)
                ->addColumn('slug', fn($page) => '<code>' . $page->slug . '</code>')
                ->addColumn('status', function ($page) {
                    $checked = $page->status == Page::STATUS['ACTIVE'] ? 'checked' : '';
                    return '<div class="form-check form-switch mb-2">
                                <input class="form-check-input" onclick="statusChange(' . $page->id . ')" type="checkbox" ' . $checked . '>
                            </div>';
                })
                ->addColumn('action', function ($page) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="' . route('backend.page.show', $page->id) . '" class="btn btn-soft-success btn-sm" title="Preview">
                                <i class="mdi mdi-eye fs-14"></i>
                            </a>
                            <a href="' . route('backend.page.edit', $page->id) . '" class="btn btn-soft-info btn-sm" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.page.destroy', $page->id) . '\')" class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'slug', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.pages.index');
    }

    public function create()
    {
        return view('backend.layout.pages.form');
    }

    public function show(Page $page)
    {
        return view('backend.layout.pages.show', compact('page'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_title'   => 'required|string|max:255',
            'page_content' => 'required|string',
        ]);

        $validated['slug']   = makeSlug(Page::class, $validated['page_title']);
        $validated['status'] = Page::STATUS['ACTIVE'];

        Page::create($validated);

        return redirect()->route('backend.page.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('backend.layout.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'page_title'   => 'required|string|max:255',
            'page_content' => 'required|string',
        ]);

        if ($page->page_title !== $validated['page_title']) {
            $validated['slug'] = makeSlug(Page::class, $validated['page_title']);
        }

        $page->update($validated);

        return redirect()->route('backend.page.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        try {
            $page->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Failed to delete.']);
        }
    }

    public function status(int $id): JsonResponse
    {
        $page         = Page::findOrFail($id);
        $page->status = $page->status == Page::STATUS['ACTIVE']
            ? Page::STATUS['INACTIVE']
            : Page::STATUS['ACTIVE'];
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }
}
