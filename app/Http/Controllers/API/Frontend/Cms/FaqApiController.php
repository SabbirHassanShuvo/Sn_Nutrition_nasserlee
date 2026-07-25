<?php

namespace App\Http\Controllers\API\Frontend\Cms;

use App\Http\Controllers\API\BaseController;
use App\Models\FaqCategory;
use App\Models\Faq;

class FaqApiController extends BaseController
{
    /**
     * GET /api/cms/faqs
     *
     * Returns FAQs grouped by category, matching the frontend FAQS constant structure:
     * [
     *   { sectionTitle: "...", faqs: [{ id: "...", question: "...", answer: "..." }] }
     * ]
     */
    public function getFaqs()
    {
        try {
            $categories = FaqCategory::active()
                ->orderBy('priority')
                ->with(['activeFaqs'])
                ->get();

            $data = $categories->map(function ($category) {
                return [
                    'sectionTitle' => $category->name,
                    'faqs'         => $category->activeFaqs->map(function ($faq) {
                        return [
                            'id'       => $faq->id,
                            'question' => $faq->question,
                            'answer'   => $faq->answer,
                        ];
                    })->values(),
                ];
            })->filter(fn($section) => $section['faqs']->isNotEmpty())->values();

            return $this->sendResponse($data, 'FAQs fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch FAQs.', ['error' => $e->getMessage()]);
        }
    }
}
