<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\OnboardingOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OnboardingOptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $options = OnboardingOption::latest();
            return DataTables::of($options)
                ->addIndexColumn()
                ->addColumn('type', function ($option) {
                    return ucfirst($option->type);
                })
                ->addColumn('status', function ($option) {
                    return getStatusHTML($option, '#198754', $option->status ? '26px' : '2px');
                })
                ->addColumn('action', function ($option) {
                    return '
                        <div class="d-flex gap-2">
                            <a href="'.route('backend.onboarding-option.edit', $option->id).'" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.onboarding-option.destroy', $option->id) . '\')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("backend.layout.onboarding_options.index");
    }

    public function create()
    {
        return view("backend.layout.onboarding_options.form");
    }

    public function store(Request $request)
    {
        $request->validate([
            'options' => 'required|array',
            'options.*.name' => 'required|string|max:255',
            'options.*.type' => 'required|in:specialty,certification',
        ]);

        foreach ($request->options as $optionData) {
            OnboardingOption::create([
                'name' => $optionData['name'],
                'type' => $optionData['type'],
                'status' => true,
            ]);
        }

        return redirect()->route('backend.onboarding-option.index')->with('success', 'Options created successfully');
    }

    public function edit(OnboardingOption $onboardingOption)
    {
        return view('backend.layout.onboarding_options.form', compact('onboardingOption'));
    }

    public function update(Request $request, OnboardingOption $onboardingOption)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:specialty,certification',
        ]);

        $onboardingOption->update($request->only(['name', 'type']));

        return redirect()->route('backend.onboarding-option.index')->with('success', 'Option updated successfully');
    }

    public function destroy(OnboardingOption $onboardingOption)
    {
        try {
            $onboardingOption->delete();
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
        $option = OnboardingOption::findOrFail($id);
        $option->status = !$option->status;
        $option->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated',
        ]);
    }
}
