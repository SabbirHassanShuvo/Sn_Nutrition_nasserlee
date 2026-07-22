<?php

namespace App\Http\Controllers\Web\Backend\Cms;

use App\Models\BannerSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BannerSectionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banners = BannerSection::latest('priority')->get();
            return DataTables::of($banners)
                ->addColumn('checkbox', function ($banner) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $banner->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('image', function ($banner) {
                    $img = $banner->image ? asset($banner->image) : 'https://placehold.co/100x50?text=No+Image';
                    return '<img src="' . $img . '" alt="' . htmlspecialchars($banner->title) . '" width="80" height="40" class="rounded shadow-sm border">';
                })
                ->addColumn('title', function ($banner) {
                    return '<div>
                        <div class="mb-0 fs-14">' . $banner->title . '</div>
                        <small class="text-muted">' . htmlspecialchars($banner->small_badge) . '</small>
                    </div>';
                })
                ->addColumn('priority', function ($banner) {
                    return $banner->priority;
                })
                ->addColumn('status', function ($banner) {
                    return '<div class="form-check form-switch mb-2">
                                <input class="form-check-input" onclick="statusBanner(' . $banner->id . ')" type="checkbox" ' . ($banner->status == BannerSection::STATUS['ACTIVE'] ? 'checked' : '') . '>
                            </div>';
                })
                ->addColumn('action', function ($banner) {
                    return '
                        <div class="d-flex gap-2">
                            <a href="' . route('backend.banner-section.edit', $banner->id) . '" class="btn btn-info btn-sm">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.banner-section.destroy', $banner->id) . '\')" class="btn btn-danger btn-sm">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'image', 'title', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layout.banner_sections.index");
    }

    public function create()
    {
        $status = BannerSection::STATUS;
        return view("backend.layout.banner_sections.form", compact('status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'small_badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'point_1' => 'nullable|string|max:255',
            'point_2' => 'nullable|string|max:255',
            'point_3' => 'nullable|string|max:255',
            'priority' => 'required|integer|min:1',
            'status' => 'required|integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = fileUpload($request->file('image'), 'banners');
        }

        BannerSection::create($data);

        return redirect()
            ->route('backend.banner-section.index')
            ->with('success', 'New Banner successfully created.');
    }

    public function edit(BannerSection $bannerSection)
    {
        $status = BannerSection::STATUS;
        return view('backend.layout.banner_sections.form', compact('bannerSection', 'status'));
    }

    public function update(Request $request, BannerSection $bannerSection)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'small_badge' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'point_1' => 'nullable|string|max:255',
            'point_2' => 'nullable|string|max:255',
            'point_3' => 'nullable|string|max:255',
            'priority' => 'required|integer|min:1',
            'status' => 'required|integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($bannerSection->image) {
                $data['image'] = fileUpdate($request->file('image'), 'banners', $bannerSection->image);
            } else {
                $data['image'] = fileUpload($request->file('image'), 'banners');
            }
        }

        $bannerSection->update($data);

        return redirect()
            ->route('backend.banner-section.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(BannerSection $bannerSection)
    {
        try {
            if ($bannerSection->image) {
                fileDelete($bannerSection->image);
            }
            $bannerSection->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the banner.',
            ]);
        }
    }

    public function status($id)
    {
        $banner = BannerSection::findOrFail($id);
        $banner->status = $banner->status == BannerSection::STATUS['ACTIVE'] ? BannerSection::STATUS['INACTIVE'] : BannerSection::STATUS['ACTIVE'];
        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $banners = BannerSection::whereIn('id', $ids)->get();
            foreach ($banners as $banner) {
                if ($banner->image) {
                    fileDelete($banner->image);
                }
                $banner->delete();
            }
            return response()->json(['success' => true, 'message' => 'Selected banners deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected banners.']);
        }
    }
}
