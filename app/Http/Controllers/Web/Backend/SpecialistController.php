<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\OnboardingOption;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class SpecialistController extends Controller
{
    /**
     * Get default and saved specialties list.
     */
    private function getAvailableSpecialties(): array
    {
        $saved = OnboardingOption::where('type', 'specialty')
            ->where('status', true)
            ->pluck('name')
            ->toArray();

        $defaults = [
            'Weight management',
            'Sports nutrition',
            'Pediatric Dietitian',
            'Clinical Nutritionist',
            'Keto & Muscle Building',
        ];

        return array_values(array_unique(array_merge($defaults, $saved)));
    }

    /**
     * Display a listing of specialists.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $specialists = Specialist::latest();
            return DataTables::of($specialists)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('info', function ($row) {
                    $avatar = $row->avatar ? asset($row->avatar) : asset('assets/images/users/avatar-1.jpg');
                    return '<div class="d-flex align-items-center">
                        <img src="' . $avatar . '" class="avatar-sm rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;" alt="">
                        <div>
                            <h6 class="mb-0 fs-14">' . e($row->name) . '</h6>
                            <small class="text-muted">' . e($row->title ?? 'Specialist') . '</small>
                        </div>
                    </div>';
                })
                ->addColumn('specialties_tags', function ($row) {
                    if (empty($row->specialties) || !is_array($row->specialties)) {
                        return '<span class="text-muted">None</span>';
                    }
                    $html = '';
                    foreach ($row->specialties as $tag) {
                        $html .= '<span class="badge bg-success-subtle text-success me-1 mb-1">' . e($tag) . '</span>';
                    }
                    return $html;
                })
                ->addColumn('slots', function ($row) {
                    if (empty($row->available_slots) || !is_array($row->available_slots)) {
                        return '<span class="text-muted">Default</span>';
                    }
                    return '<span class="badge bg-info-subtle text-info">' . count($row->available_slots) . ' slots</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->is_active ? 'checked' : '';
                    return '<div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input status-toggle" type="checkbox" data-id="' . $row->id . '" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-2 justify-content-center">
                        <a href="' . route('backend.specialist.edit', $row->id) . '" class="btn btn-sm btn-soft-primary" title="Edit"><i class="ri-pencil-line"></i></a>
                        <button type="button" class="btn btn-sm btn-soft-danger delete-btn" data-id="' . $row->id . '" title="Delete"><i class="ri-delete-bin-line"></i></button>
                    </div>';
                })
                ->rawColumns(['checkbox', 'info', 'specialties_tags', 'slots', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.specialists.index');
    }

    /**
     * Show the form for creating specialist(s).
     */
    public function create()
    {
        $specialtiesList = $this->getAvailableSpecialties();
        return view('backend.layout.specialists.create', compact('specialtiesList'));
    }

    /**
     * Store single or multiple specialists.
     */
    public function store(Request $request)
    {
        // Check if multi-specialist payload is sent
        if ($request->has('multi') && is_array($request->multi)) {
            $request->validate([
                'multi' => 'required|array|min:1',
                'multi.*.name' => 'required|string|max:255',
                'multi.*.title' => 'nullable|string|max:255',
                'multi.*.email' => 'nullable|email|max:255',
                'multi.*.phone' => 'nullable|string|max:50',
                'multi.*.specialties' => 'nullable|string',
                'multi.*.available_slots' => 'nullable|string',
            ]);

            $count = 0;
            foreach ($request->multi as $item) {
                if (empty($item['name'])) continue;

                $specialtiesArr = !empty($item['specialties']) 
                    ? array_map('trim', explode(',', $item['specialties'])) 
                    : ['Weight management', 'Sports nutrition'];

                $slotsArr = !empty($item['available_slots']) 
                    ? array_map('trim', explode(',', $item['available_slots'])) 
                    : ['12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM'];

                Specialist::create([
                    'name' => $item['name'],
                    'title' => $item['title'] ?? 'Clinical Nutritionist',
                    'email' => $item['email'] ?? null,
                    'phone' => $item['phone'] ?? null,
                    'specialties' => $specialtiesArr,
                    'bio' => $item['bio'] ?? null,
                    'available_slots' => $slotsArr,
                    'is_active' => true,
                ]);
                $count++;
            }

            return redirect()->route('backend.specialist.index')->with('t-success', "Successfully created {$count} specialists.");
        }

        // Single specialist creation
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
            'specialties' => 'nullable|array',
            'available_slots' => 'nullable|array',
            'bio' => 'nullable|string',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = fileUpload($request->file('avatar'), 'specialists');
        }

        $slots = $request->has('available_slots') && is_array($request->available_slots) 
            ? array_values(array_filter($request->available_slots)) 
            : [];

        $specialtiesArr = $request->has('specialties') && is_array($request->specialties) 
            ? array_values(array_filter($request->specialties)) 
            : [];

        Specialist::create([
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'phone' => $request->phone,
            'avatar' => $avatarPath,
            'specialties' => $specialtiesArr,
            'bio' => $request->bio,
            'available_slots' => $slots,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('backend.specialist.index')->with('t-success', 'Specialist created successfully.');
    }

    /**
     * Show the form for editing the specified specialist.
     */
    public function edit($id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialtiesList = $this->getAvailableSpecialties();
        return view('backend.layout.specialists.edit', compact('specialist', 'specialtiesList'));
    }

    /**
     * Update the specified specialist.
     */
    public function update(Request $request, $id)
    {
        $specialist = Specialist::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
            'specialties' => 'nullable|array',
            'available_slots' => 'nullable|array',
            'bio' => 'nullable|string',
        ]);

        $avatarPath = $specialist->avatar;
        if ($request->hasFile('avatar')) {
            $avatarPath = fileUpdate($request->file('avatar'), 'specialists', $specialist->avatar);
        }

        $slots = $request->has('available_slots') && is_array($request->available_slots) 
            ? array_values(array_filter($request->available_slots)) 
            : [];

        $specialtiesArr = $request->has('specialties') && is_array($request->specialties) 
            ? array_values(array_filter($request->specialties)) 
            : [];

        $specialist->update([
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'phone' => $request->phone,
            'avatar' => $avatarPath,
            'specialties' => $specialtiesArr,
            'bio' => $request->bio,
            'available_slots' => $slots,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : false,
        ]);

        return redirect()->route('backend.specialist.index')->with('t-success', 'Specialist updated successfully.');
    }

    /**
     * Store a new specialty tag dynamically via Modal.
     */
    public function storeSpecialty(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $name = trim($request->name);

        $option = OnboardingOption::firstOrCreate([
            'name' => $name,
            'type' => 'specialty',
        ], [
            'status' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'New specialty created successfully',
            'name' => $option->name
        ]);
    }

    /**
     * Toggle specialist active status.
     */
    public function status($id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->is_active = !$specialist->is_active;
        $specialist->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'is_active' => $specialist->is_active
        ]);
    }

    /**
     * Remove the specified specialist.
     */
    public function destroy($id)
    {
        $specialist = Specialist::findOrFail($id);
        if ($specialist->avatar) {
            fileDelete($specialist->avatar);
        }
        $specialist->delete();

        return response()->json(['status' => 'success', 'message' => 'Specialist deleted successfully']);
    }

    /**
     * Bulk destroy specialists.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            $specialists = Specialist::whereIn('id', $ids)->get();
            foreach ($specialists as $sp) {
                if ($sp->avatar) {
                    fileDelete($sp->avatar);
                }
                $sp->delete();
            }
            return response()->json(['status' => 'success', 'message' => 'Selected specialists deleted successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'No records selected'], 400);
    }
}
