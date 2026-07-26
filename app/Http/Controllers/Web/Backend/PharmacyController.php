<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class PharmacyController extends Controller
{
    public function __construct()
    {
        // Auto-create table & seed sample data matching screenshot
        if (!Schema::hasTable('pharmacies')) {
            Schema::create('pharmacies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address');
                $table->string('phone')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->decimal('rating', 2, 1)->default(4.8);
                $table->string('opening_hours')->nullable()->default('08:00 - 22:00');
                $table->json('services')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            Pharmacy::create([
                'name' => 'Pharmacie Centrale',
                'address' => '5 Rue Allal Ben Abdellah, Casablanca',
                'phone' => '+212 522 123 456',
                'latitude' => 33.5731104,
                'longitude' => -7.5898434,
                'rating' => 4.9,
                'opening_hours' => '08:00 - 22:00',
                'services' => ['24h Available', 'Delivery', 'Vaccines'],
                'is_active' => true,
            ]);

            Pharmacy::create([
                'name' => 'Green Pharma',
                'address' => '22 Avenue Mers Sultan, Casablanca',
                'phone' => '+212 522 654 321',
                'latitude' => 33.5801104,
                'longitude' => -7.5958434,
                'rating' => 4.6,
                'opening_hours' => '08:00 - 21:00',
                'services' => ['Supplements', 'Homeopathy'],
                'is_active' => true,
            ]);

            Pharmacy::create([
                'name' => 'Pharmacie du Peuple',
                'address' => '10 Rue Ibn Battouta, Casablanca',
                'phone' => '+212 522 789 012',
                'latitude' => 33.5881104,
                'longitude' => -7.6058434,
                'rating' => 4.4,
                'opening_hours' => '09:00 - 20:00',
                'services' => ['Delivery', 'Cosmetics'],
                'is_active' => true,
            ]);

            Pharmacy::create([
                'name' => 'MediPlus Pharmacy',
                'address' => '67 Boulevard Zerktouni, Casablanca',
                'phone' => '+212 522 345 678',
                'latitude' => 33.5951104,
                'longitude' => -7.6158434,
                'rating' => 4.7,
                'opening_hours' => '07:30 - 23:00',
                'services' => ['24h Available', 'Lab Tests', 'Delivery'],
                'is_active' => true,
            ]);
        }
    }

    /**
     * Display listing of pharmacies.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pharmacies = Pharmacy::latest();
            return DataTables::of($pharmacies)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('info', function ($row) {
                    $image = $row->image ? asset($row->image) : asset('assets/images/small/img-2.jpg');
                    $phoneHtml = $row->phone ? '<small class="text-primary d-block mt-1"><i class="ri-phone-line me-1"></i>' . e($row->phone) . '</small>' : '';
                    return '<div class="d-flex align-items-center">
                        <img src="' . $image . '" class="avatar-sm rounded me-2" style="width:48px;height:48px;object-fit:cover;" alt="">
                        <div>
                            <h6 class="mb-0 fs-14 fw-semibold text-dark">' . e($row->name) . '</h6>
                            <small class="text-muted"><i class="ri-map-pin-line text-danger me-1"></i>' . e($row->address) . '</small>
                            ' . $phoneHtml . '
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
                ->addColumn('services_tags', function ($row) {
                    if (empty($row->services) || !is_array($row->services)) {
                        return '<span class="text-muted fs-12">None</span>';
                    }
                    $html = '';
                    foreach ($row->services as $tag) {
                        $html .= '<span class="badge bg-warning-subtle text-warning me-1 mb-1">' . e($tag) . '</span>';
                    }
                    return $html;
                })
                ->addColumn('rating_hours', function ($row) {
                    return '<div class="fs-12">
                        <span class="text-warning fw-semibold"><i class="ri-star-fill me-1"></i>' . e($row->rating) . '</span><br>
                        <small class="text-muted"><i class="ri-time-line me-1"></i>' . e($row->opening_hours ?? '08:00 - 22:00') . '</small>
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
                        <a href="' . route('backend.pharmacies.edit', $row->id) . '" class="btn btn-sm btn-soft-primary" title="Edit Pharmacy"><i class="ri-pencil-line"></i></a>
                        <button type="button" class="btn btn-sm btn-soft-danger delete-btn" data-id="' . $row->id . '" title="Delete Pharmacy"><i class="ri-delete-bin-line"></i></button>
                    </div>';
                })
                ->rawColumns(['checkbox', 'info', 'coordinates', 'services_tags', 'rating_hours', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.pharmacies.index');
    }

    /**
     * Show form for creating a pharmacy.
     */
    public function create()
    {
        $defaultServices = ['24h Available', 'Delivery', 'Vaccines', 'Supplements', 'Homeopathy', 'Cosmetics', 'Lab Tests', 'Baby Care'];
        return view('backend.layout.pharmacies.create', compact('defaultServices'));
    }

    /**
     * Store new pharmacy.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'rating' => 'nullable|numeric|between:1,5',
            'opening_hours' => 'nullable|string|max:255',
            'services' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = fileUpload($request->file('image'), 'pharmacies');
        }

        $servicesArr = $request->has('services') && is_array($request->services) 
            ? array_values(array_filter($request->services)) 
            : ['24h Available', 'Delivery', 'Vaccines'];

        Pharmacy::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating' => $request->rating ?? 4.8,
            'opening_hours' => $request->opening_hours ?? '08:00 - 22:00',
            'services' => $servicesArr,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('backend.pharmacies.index')->with('t-success', 'Pharmacy added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $defaultServices = ['24h Available', 'Delivery', 'Vaccines', 'Supplements', 'Homeopathy', 'Cosmetics', 'Lab Tests', 'Baby Care'];
        return view('backend.layout.pharmacies.edit', compact('pharmacy', 'defaultServices'));
    }

    /**
     * Update pharmacy.
     */
    public function update(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'rating' => 'nullable|numeric|between:1,5',
            'opening_hours' => 'nullable|string|max:255',
            'services' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
        ]);

        $imagePath = $pharmacy->image;
        if ($request->hasFile('image')) {
            $imagePath = fileUpdate($request->file('image'), 'pharmacies', $pharmacy->image);
        }

        $servicesArr = $request->has('services') && is_array($request->services) 
            ? array_values(array_filter($request->services)) 
            : [];

        $pharmacy->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating' => $request->rating ?? 4.8,
            'opening_hours' => $request->opening_hours ?? '08:00 - 22:00',
            'services' => $servicesArr,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : false,
        ]);

        return redirect()->route('backend.pharmacies.index')->with('t-success', 'Pharmacy updated successfully.');
    }

    /**
     * Toggle status.
     */
    public function status($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->is_active = !$pharmacy->is_active;
        $pharmacy->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'is_active' => $pharmacy->is_active
        ]);
    }

    /**
     * Delete pharmacy.
     */
    public function destroy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        if ($pharmacy->image) {
            fileDelete($pharmacy->image);
        }
        $pharmacy->delete();

        return response()->json(['status' => 'success', 'message' => 'Pharmacy deleted successfully']);
    }

    /**
     * Bulk destroy pharmacies.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids) && count($ids) > 0) {
            $pharmacies = Pharmacy::whereIn('id', $ids)->get();
            foreach ($pharmacies as $p) {
                if ($p->image) {
                    fileDelete($p->image);
                }
                $p->delete();
            }
            return response()->json(['status' => 'success', 'message' => 'Selected pharmacies deleted successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'No records selected'], 400);
    }
}
