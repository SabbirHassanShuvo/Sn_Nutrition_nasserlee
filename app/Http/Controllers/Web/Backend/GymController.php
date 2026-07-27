<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class GymController extends Controller
{
    public function __construct()
    {
        // Auto-create table if missing
        if (!Schema::hasTable('gyms')) {
            Schema::create('gyms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address');
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->decimal('rating', 2, 1)->default(4.5);
                $table->string('opening_hours')->nullable()->default('06:00 - 22:00');
                $table->json('facilities')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default Casablanca sample gyms matching design
            Gym::create([
                'name' => 'FitZone Gym',
                'address' => '12 Rue Mohammed V, Casablanca',
                'latitude' => 33.5731104,
                'longitude' => -7.5898434,
                'rating' => 4.8,
                'opening_hours' => '06:00 - 22:00',
                'facilities' => ['Cardio', 'Weights', 'Classes'],
                'is_active' => true,
            ]);

            Gym::create([
                'name' => 'PowerHouse Fitness',
                'address' => '34 Boulevard Anfa, Casablanca',
                'latitude' => 33.5851104,
                'longitude' => -7.6018434,
                'rating' => 4.5,
                'opening_hours' => '07:00 - 23:00',
                'facilities' => ['CrossFit', 'Sauna', 'Pool'],
                'is_active' => true,
            ]);

            Gym::create([
                'name' => 'Elite Sports Club',
                'address' => '7 Rue Ibnou Sina, Casablanca',
                'latitude' => 33.5921104,
                'longitude' => -7.6128434,
                'rating' => 4.3,
                'opening_hours' => '06:30 - 21:00',
                'facilities' => ['Yoga', 'Boxing', 'Weights'],
                'is_active' => true,
            ]);

            Gym::create([
                'name' => 'Urban Athlete',
                'address' => '88 Avenue Hassan II, Casablanca',
                'latitude' => 33.5981104,
                'longitude' => -7.6208434,
                'rating' => 4.6,
                'opening_hours' => '06:00 - 22:30',
                'facilities' => ['HIIT', 'Cycling', 'Nutrition'],
                'is_active' => true,
            ]);
        }
    }

    /**
     * Display listing of gyms.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $gyms = Gym::latest();
            return DataTables::of($gyms)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('info', function ($row) {
                    $image = $row->image ? asset($row->image) : asset('assets/images/small/img-1.jpg');
                    return '<div class="d-flex align-items-center">
                        <img src="' . $image . '" class="avatar-sm rounded me-2" style="width:48px;height:48px;object-fit:cover;" alt="">
                        <div>
                            <h6 class="mb-0 fs-14 fw-semibold text-dark">' . e($row->name) . '</h6>
                            <small class="text-muted"><i class="ri-map-pin-line text-danger me-1"></i>' . e($row->address) . '</small>
                        </div>
                    </div>';
                })
                ->addColumn('coordinates', function ($row) {
                    if (!$row->latitude || !$row->longitude) {
                        return '<span class="text-muted fs-12">Not Set</span>';
                    }
                    return '<div class="fs-12">
                        <span class="badge bg-primary-subtle text-primary mb-1">Lat: ' . e($row->latitude) . '</span><br>
                        <span class="badge bg-info-subtle text-info">Lng: ' . e($row->longitude) . '</span>
                    </div>';
                })
                ->addColumn('facilities_tags', function ($row) {
                    if (empty($row->facilities) || !is_array($row->facilities)) {
                        return '<span class="text-muted fs-12">None</span>';
                    }
                    $html = '';
                    foreach ($row->facilities as $tag) {
                        $html .= '<span class="badge bg-warning-subtle text-warning me-1 mb-1">' . e($tag) . '</span>';
                    }
                    return $html;
                })
                ->addColumn('rating_hours', function ($row) {
                    return '<div class="fs-12">
                        <span class="text-warning fw-semibold"><i class="ri-star-fill me-1"></i>' . e($row->rating) . '</span><br>
                        <small class="text-muted"><i class="ri-time-line me-1"></i>' . e($row->opening_hours ?? '06:00 - 22:00') . '</small>
                    </div>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->is_active ? 'checked' : '';
                    return '<div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input status-toggle" type="checkbox" data-id="' . $row->id . '" ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-2 justify-content-center">
                        <a href="' . route('backend.gym.edit', $row->id) . '" class="btn btn-sm btn-soft-primary" title="Edit Gym"><i class="ri-pencil-line"></i></a>
                        <button type="button" class="btn btn-sm btn-soft-danger delete-btn" data-id="' . $row->id . '" title="Delete Gym"><i class="ri-delete-bin-line"></i></button>
                    </div>';
                })
                ->rawColumns(['checkbox', 'info', 'coordinates', 'facilities_tags', 'rating_hours', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.gyms.index');
    }

    /**
     * Show form for creating a gym.
     */
    public function create()
    {
        $defaultFacilities = ['Cardio', 'Weights', 'Classes', 'CrossFit', 'Sauna', 'Pool', 'Yoga', 'Boxing', 'HIIT', 'Cycling', 'Nutrition'];
        return view('backend.layout.gyms.create', compact('defaultFacilities'));
    }

    /**
     * Store new gym.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'rating' => 'nullable|numeric|between:1,5',
            'opening_hours' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = fileUpload($request->file('image'), 'gyms');
        }

        $facilitiesArr = $request->has('facilities') && is_array($request->facilities) 
            ? array_values(array_filter($request->facilities)) 
            : ['Cardio', 'Weights', 'Classes'];

        Gym::create([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating' => $request->rating ?? 4.5,
            'opening_hours' => $request->opening_hours ?? '06:00 - 22:00',
            'facilities' => $facilitiesArr,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('backend.gym.index')->with('t-success', 'Gym added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $gym = Gym::findOrFail($id);
        $defaultFacilities = ['Cardio', 'Weights', 'Classes', 'CrossFit', 'Sauna', 'Pool', 'Yoga', 'Boxing', 'HIIT', 'Cycling', 'Nutrition'];
        return view('backend.layout.gyms.edit', compact('gym', 'defaultFacilities'));
    }

    /**
     * Update gym.
     */
    public function update(Request $request, $id)
    {
        $gym = Gym::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'rating' => 'nullable|numeric|between:1,5',
            'opening_hours' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
        ]);

        $imagePath = $gym->image;
        if ($request->hasFile('image')) {
            $imagePath = fileUpdate($request->file('image'), 'gyms', $gym->image);
        }

        $facilitiesArr = $request->has('facilities') && is_array($request->facilities) 
            ? array_values(array_filter($request->facilities)) 
            : [];

        $gym->update([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating' => $request->rating ?? 4.5,
            'opening_hours' => $request->opening_hours ?? '06:00 - 22:00',
            'facilities' => $facilitiesArr,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : false,
        ]);

        return redirect()->route('backend.gym.index')->with('t-success', 'Gym updated successfully.');
    }

    /**
     * Toggle status.
     */
    public function status($id)
    {
        $gym = Gym::findOrFail($id);
        $gym->is_active = !$gym->is_active;
        $gym->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'is_active' => $gym->is_active
        ]);
    }

    /**
     * Delete gym.
     */
    public function destroy($id)
    {
        $gym = Gym::findOrFail($id);
        if ($gym->image) {
            fileDelete($gym->image);
        }
        $gym->delete();

        return response()->json(['status' => 'success', 'message' => 'Gym deleted successfully']);
    }

    /**
     * Bulk destroy gyms.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            $gyms = Gym::whereIn('id', $ids)->get();
            foreach ($gyms as $g) {
                if ($g->image) {
                    fileDelete($g->image);
                }
                $g->delete();
            }
            return response()->json(['status' => 'success', 'message' => 'Selected gyms deleted successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'No records selected'], 400);
    }
}
