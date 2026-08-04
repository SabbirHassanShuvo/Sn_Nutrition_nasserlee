<?php

namespace App\Http\Controllers\Api\Frontend\Cms;

use App\Http\Controllers\Api\BaseController;
use App\Models\BannerSection;
use App\Models\QualityControlSection;
use Illuminate\Http\Request;

class HomePageController extends BaseController
{
    /**
     * Build title HTML: wraps the highlight word with span and adds <br> after it.
     * Input:  "Better Health Starts Here", highlight: "Health"
     * Output: "Better <span class='font-play-fair text-primary'>Health</span> <br class='hidden sm:block' /> Starts Here"
     */
    private function buildTitleHtml(?string $title, ?string $highlight): string
    {
        if (!$title) return '';
        if (!$highlight || !str_contains($title, $highlight)) return $title;

        $pos = strpos($title, $highlight);
        $before = substr($title, 0, $pos);
        $after  = substr($title, $pos + strlen($highlight));

        return trim($before)
            . " <span class='font-play-fair text-primary'>" . $highlight . "</span>"
            . " <br class='hidden sm:block' />"
            . $after;
    }

    /**
     * Build description HTML: wraps the highlight phrase with span.
     * Input:  "...support your energy, immunity, and daily well-being — with...", highlight: "energy, immunity, and daily well-being"
     * Output: "...support your <span class='text-primary'>energy, immunity, and daily well-being</span> — with..."
     */
    private function buildDescriptionHtml(?string $description, ?string $highlight): string
    {
        if (!$description) return '';
        if (!$highlight || !str_contains($description, $highlight)) return $description;

        return str_replace(
            $highlight,
            "<span class='text-primary'>" . $highlight . "</span>",
            $description
        );
    }

    /**
     * Get all active banner sections for the homepage slider.
     */
    public function getBanners()
    {
        try {
            $banners = BannerSection::where('status', BannerSection::STATUS['ACTIVE'])
                ->orderBy('priority', 'asc')
                ->get()
                ->map(function ($banner) {
                    return [
                        'id'          => $banner->id,
                        'subtitle'    => $banner->small_badge,
                        'title'       => $this->buildTitleHtml($banner->title, $banner->title_highlight),
                        'description' => $this->buildDescriptionHtml($banner->description, $banner->description_highlight),
                        'button_text' => $banner->button_text,
                        'image'       => $banner->image ? asset($banner->image) : null,
                        'point_1'     => $banner->point_1,
                        'point_2'     => $banner->point_2,
                        'point_3'     => $banner->point_3,
                    ];
                });

            $section = QualityControlSection::first();
            $qualityControl = [
                'title_one'       => $section->title,
                'title_two'       => $section->title_highlight,
                'description'     => $section->description,
                'image'       => $section->image ? asset($section->image) : null,
                'cards'       => [
                    [
                        'title'       => $section->card1_title,
                        'description' => $section->card1_description,
                    ],
                    [
                        'title'       => $section->card2_title,
                        'description' => $section->card2_description,
                    ],
                    [
                        'title'       => $section->card3_title,
                        'description' => $section->card3_description,
                    ],
                ],
            ];


            return $this->sendResponse([
                'banners' => $banners,
                'qualityControl' => $qualityControl
            ], 'Banner sections fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch banner sections.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the Quality Control section data for the homepage.
     */
    // public function getQualityControl()
    // {
    //     try {
    //         $section = QualityControlSection::first();

    //         if (!$section) {
    //             return $this->sendResponse(null, 'Quality Control section not configured yet.');
    //         }

    //         $data = [
    //             'title'       => $this->buildTitleHtml($section->title, $section->title_highlight),
    //             'description' => $section->description,
    //             'image'       => $section->image ? asset($section->image) : null,
    //             'cards'       => [
    //                 [
    //                     'title'       => $section->card1_title,
    //                     'description' => $this->buildDescriptionHtml($section->card1_description, $section->card1_description_highlight),
    //                 ],
    //                 [
    //                     'title'       => $section->card2_title,
    //                     'description' => $this->buildDescriptionHtml($section->card2_description, $section->card2_description_highlight),
    //                 ],
    //                 [
    //                     'title'       => $section->card3_title,
    //                     'description' => $this->buildDescriptionHtml($section->card3_description, $section->card3_description_highlight),
    //                 ],
    //             ],
    //         ];

    //         return $this->sendResponse($data, 'Quality Control section fetched successfully.');
    //     } catch (\Exception $e) {
    //         return $this->sendError('Failed to fetch Quality Control section.', ['error' => $e->getMessage()]);
    //     }
    // }
}
