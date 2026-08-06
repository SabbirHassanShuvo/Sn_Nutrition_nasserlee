<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data' => $batches
        ]);
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
        ]);

        try {
            $batch = Batch::create([
                'name' => $request->name,
                'color' => $request->color ?? '#3b82f6',
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Batch created successfully',
                'data' => $batch
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $batch = Batch::findOrFail($id);
            $batch->delete();

            return response()->json([
                'success' => true,
                'message' => 'Batch deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete batch: ' . $e->getMessage()
            ], 500);
        }
    }
}
