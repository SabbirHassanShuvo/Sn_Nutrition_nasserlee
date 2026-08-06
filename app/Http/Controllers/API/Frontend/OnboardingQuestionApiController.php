<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OnboardingQuestion;
use App\Models\OnboardingAnswer;
use App\Models\OnboardingSetting;
use App\Models\UserOnboardingAnswer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingQuestionApiController extends Controller
{
    public function getQuestions()
    {
        $cmsSettings = OnboardingSetting::first() ?? new OnboardingSetting([
            'badge_text' => 'Tailored for you',
            'title' => 'Not sure where to start?',
            'description' => 'Take our 2-minute assessment created by nutritionists to get a personalized supplement routine based on your goals, lifestyle, and diet.',
            'footnote_text' => '2min • No account needed • 100% free',
        ]);

        $questions = OnboardingQuestion::with(['answers' => function ($query) {
            $query->select('id', 'onboarding_question_id', 'answer_text');
        }])
        ->where('status', true)
        ->orderBy('serial_number', 'asc')
        ->get(['id', 'question_text', 'serial_number']);

        return response()->json([
            'success' => true,
            'data' => [
                'cms' => [
                    'badge_text' => $cmsSettings->badge_text,
                    'title' => $cmsSettings->title,
                    'description' => $cmsSettings->description,
                    'footnote_text' => $cmsSettings->footnote_text,
                ],
                'questions' => $questions,
            ]
        ], 200);
    }

    public function submitAnswers(Request $request)
    {
        $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:onboarding_questions,id',
            'answers.*.answer_id' => 'required|exists:onboarding_answers,id',
        ]);

        $user = Auth::user();

        if ($user) {
            // Delete old answers
            UserOnboardingAnswer::where('user_id', $user->id)->delete();
        }

        $answerIds = [];
        // Save new answers if user is logged in
        foreach ($request->answers as $ans) {
            if ($user) {
                UserOnboardingAnswer::create([
                    'user_id' => $user->id,
                    'onboarding_question_id' => $ans['question_id'],
                    'onboarding_answer_id' => $ans['answer_id'],
                ]);
            }
            $answerIds[] = $ans['answer_id'];
        }

        // Fetch selected answers with questions to compile recommendation parameters
        $selectedAnswers = OnboardingAnswer::with('question')->whereIn('id', $answerIds)->get();

        $keywords = [];
        $excludeKeywords = [];
        $isVegan = false;

        foreach ($selectedAnswers as $ans) {
            $answerText = strtolower($ans->answer_text);
            $questionText = strtolower($ans->question->question_text);

            // Goal-based check
            if (str_contains($questionText, 'goal')) {
                if (str_contains($answerText, 'loss')) {
                    $keywords = array_merge($keywords, ['weight', 'loss', 'burn', 'fat', 'carnitine', 'slim', 'diet']);
                } elseif (str_contains($answerText, 'muscle') || str_contains($answerText, 'gain')) {
                    $keywords = array_merge($keywords, ['protein', 'creatine', 'muscle', 'mass', 'bcaa', 'gainer', 'amino']);
                } elseif (str_contains($answerText, 'energy') || str_contains($answerText, 'boost')) {
                    $keywords = array_merge($keywords, ['energy', 'boost', 'caffeine', 'pre-workout', 'coq10', 'focus']);
                } elseif (str_contains($answerText, 'wellness') || str_contains($answerText, 'general')) {
                    $keywords = array_merge($keywords, ['vitamin', 'multivitamin', 'daily', 'immune', 'health', 'zinc', 'omega', 'mineral']);
                } elseif (str_contains($answerText, 'sleep') || str_contains($answerText, 'stress')) {
                    $keywords = array_merge($keywords, ['sleep', 'melatonin', 'calm', 'stress', 'magnesium', 'ashwagandha', 'relax']);
                }
            }

            // Sleep quality check (if poor or fair, add wellness/sleep keywords)
            if (str_contains($questionText, 'sleep') && (str_contains($answerText, 'poor') || str_contains($answerText, 'fair'))) {
                $keywords = array_merge($keywords, ['sleep', 'melatonin', 'stress', 'magnesium', 'ashwagandha']);
            }

            // Diet check
            if (str_contains($answerText, 'vegan') || str_contains($answerText, 'vegetarian')) {
                $isVegan = true;
            }

            // Allergy check
            if (str_contains($questionText, 'allerg')) {
                if (str_contains($answerText, 'dairy') || str_contains($answerText, 'lactose')) {
                    $excludeKeywords = array_merge($excludeKeywords, ['milk', 'whey', 'dairy', 'lactose']);
                }
                if (str_contains($answerText, 'gluten') || str_contains($answerText, 'wheat')) {
                    $excludeKeywords = array_merge($excludeKeywords, ['wheat', 'gluten']);
                }
                if (str_contains($answerText, 'nut')) {
                    $excludeKeywords = array_merge($excludeKeywords, ['nut', 'peanut', 'almond', 'cashew']);
                }
            }
        }

        // Search for matching products
        $productsQuery = Product::query()
            ->with(['category', 'brandData', 'batch'])
            ->where('status', 'active')
            ->where('in_stock', true);

        if ($isVegan) {
            $productsQuery->where('is_vegan', true);
        }

        // Apply keyword match
        if (!empty($keywords)) {
            $productsQuery->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('short_description', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('full_description', 'LIKE', '%' . $keyword . '%');
                }
            });
        }

        // Apply exclusion keywords
        if (!empty($excludeKeywords)) {
            foreach ($excludeKeywords as $exclude) {
                $productsQuery->where('name', 'NOT LIKE', '%' . $exclude . '%')
                              ->where('short_description', 'NOT LIKE', '%' . $exclude . '%')
                              ->where('full_description', 'NOT LIKE', '%' . $exclude . '%');
            }
        }

        // Get up to 8 suggested products
        $products = $productsQuery->paginate(8);

        // If no products match recommendations, return a default list of active products
        if ($products->isEmpty()) {
            $products = Product::query()
                ->with(['category', 'brandData', 'batch'])
                ->where('status', 'active')
                ->where('in_stock', true)
                ->paginate(8);
        }

        $wishlistProductIds = [];
        if ($user) {
            $wishlistProductIds = \App\Models\Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
        }

        $products->getCollection()->transform(function ($product) use ($wishlistProductIds) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'price' => (float) $product->price,
                'old_price' => $product->old_price ? (float) $product->old_price : null,
                'image' => $product->main_image ? asset($product->main_image) : null,
                'in_stock' => (bool) $product->in_stock,
                'is_wishlist' => in_array($product->id, $wishlistProductIds),
                'quantity' => (int) $product->quantity,
                'rating' => (float) $product->rating,
                'category' => $product->category ? $product->category->name : null,
                'brand' => $product->brandData ? [
                    'name' => $product->brandData->name,
                    'specialty' => $product->brandData->specialty,
                    'rating' => (float) $product->brandData->rating,
                ] : null,
                'batch' => $product->batch ? [
                    'id' => $product->batch->id,
                    'name' => $product->batch->name,
                    'color' => $product->batch->color,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Onboarding answers submitted successfully.'
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
