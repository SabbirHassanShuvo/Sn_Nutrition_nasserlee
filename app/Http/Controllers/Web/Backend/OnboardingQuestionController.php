<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\OnboardingQuestion;
use App\Models\OnboardingSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OnboardingQuestionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $questions = OnboardingQuestion::with('answers')->orderBy('serial_number', 'asc')->latest();
            return DataTables::of($questions)
                ->addColumn('checkbox', function ($question) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $question->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('answers', function ($question) {
                    return $question->answers->pluck('answer_text')->implode(', ');
                })
                ->addColumn('status', function ($question) {
                    return getStatusHTML($question, '#198754', $question->status ? '26px' : '2px');
                })
                ->addColumn('action', function ($question) {
                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="'.route('backend.onboarding-question.edit', $question->id).'" class="btn btn-soft-info btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="mdi mdi-pencil fs-14"></i>
                            </a>
                            <button type="button" onclick="deleteData(\'' . route('backend.onboarding-question.destroy', $question->id) . '\')" class="btn btn-soft-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                <i class="mdi mdi-delete fs-14"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }

        $cmsSettings = OnboardingSetting::first() ?? new OnboardingSetting([
            'badge_text' => 'Tailored for you',
            'title' => 'Not sure where to start?',
            'description' => 'Take our 2-minute assessment created by nutritionists to get a personalized supplement routine based on your goals, lifestyle, and diet.',
            'footnote_text' => '2min • No account needed • 100% free',
        ]);

        return view("backend.layout.onboarding_questions.index", compact('cmsSettings'));
    }

    public function create()
    {
        return view("backend.layout.onboarding_questions.form");
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'serial_number' => 'required|integer|min:0',
            'answers' => 'required|array|min:1|max:4',
            'answers.*' => 'required|string|max:255',
        ]);

        $question = OnboardingQuestion::create([
            'question_text' => $request->question_text,
            'serial_number' => $request->serial_number,
            'status' => true,
        ]);

        foreach ($request->answers as $answerText) {
            $question->answers()->create([
                'answer_text' => $answerText,
            ]);
        }

        return redirect()->route('backend.onboarding-question.index')->with('success', 'Onboarding Question created successfully');
    }

    public function edit(OnboardingQuestion $onboardingQuestion)
    {
        $onboardingQuestion->load('answers');
        return view('backend.layout.onboarding_questions.form', compact('onboardingQuestion'));
    }

    public function update(Request $request, OnboardingQuestion $onboardingQuestion)
    {
        $request->validate([
            'question_text' => 'required|string',
            'serial_number' => 'required|integer|min:0',
            'answers' => 'required|array|min:1|max:4',
            'answers.*' => 'required|string|max:255',
        ]);

        $onboardingQuestion->update([
            'question_text' => $request->question_text,
            'serial_number' => $request->serial_number,
        ]);

        // Recreate answers
        $onboardingQuestion->answers()->delete();
        foreach ($request->answers as $answerText) {
            $onboardingQuestion->answers()->create([
                'answer_text' => $answerText,
            ]);
        }

        return redirect()->route('backend.onboarding-question.index')->with('success', 'Onboarding Question updated successfully');
    }

    public function destroy(OnboardingQuestion $onboardingQuestion)
    {
        try {
            $onboardingQuestion->delete();
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
        $question = OnboardingQuestion::findOrFail($id);
        $question->status = !$question->status;
        $question->save();
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
            OnboardingQuestion::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected questions deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected questions.']);
        }
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'badge_text' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'footnote_text' => 'nullable|string|max:255',
        ]);

        $settings = OnboardingSetting::first() ?? new OnboardingSetting();
        $settings->fill($request->only(['badge_text', 'title', 'description', 'footnote_text']));
        $settings->save();

        return redirect()->route('backend.onboarding-question.index')->with('success', 'CMS Settings updated successfully');
    }
}
