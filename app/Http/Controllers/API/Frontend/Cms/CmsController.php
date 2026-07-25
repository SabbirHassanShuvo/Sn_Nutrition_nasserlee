<?php

namespace App\Http\Controllers\API\Frontend\Cms;

use App\Http\Controllers\API\BaseController;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class CmsController extends BaseController
{
    /**
     * Build title HTML: wraps the highlight word with span and adds <br> after it.
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
     * Get the About Us section data for the frontend.
     */
    public function getAboutPage()
    {
        try {
            $section = AboutSection::first();

            if (!$section) {
                return $this->sendResponse(null, 'About Us section not configured yet.');
            }

            $data = [
                'story' => [
                    'badge'       => $section->story_badge,
                    'title'       => $this->buildTitleHtml($section->story_title, $section->story_title_highlight),
                    'description' => $section->story_description,
                    'image'       => $section->story_image ? asset($section->story_image) : null,
                ],
                'mission' => [
                    'title'       => $this->buildTitleHtml($section->mission_title, $section->mission_title_highlight),
                    'description' => $section->mission_description,
                    'stats' => [
                        [
                            'value' => $section->stat1_value,
                            'label' => $section->stat1_label,
                        ],
                        [
                            'value' => $section->stat2_value,
                            'label' => $section->stat2_label,
                        ],
                        [
                            'value' => $section->stat3_value,
                            'label' => $section->stat3_label,
                        ],
                        [
                            'value' => $section->stat4_value,
                            'label' => $section->stat4_label,
                        ],
                    ]
                ],
                'standards' => [
                    'title'       => $this->buildTitleHtml($section->standards_title, $section->standards_title_highlight),
                    'description' => $section->standards_description,
                    'items' => [
                        [
                            'icon'        => $section->standard1_icon,
                            'title'       => $section->standard1_title,
                            'description' => $section->standard1_description,
                        ],
                        [
                            'icon'        => $section->standard2_icon,
                            'title'       => $section->standard2_title,
                            'description' => $section->standard2_description,
                        ],
                        [
                            'icon'        => $section->standard3_icon,
                            'title'       => $section->standard3_title,
                            'description' => $section->standard3_description,
                        ],
                        [
                            'icon'        => $section->standard4_icon,
                            'title'       => $section->standard4_title,
                            'description' => $section->standard4_description,
                        ],
                    ]
                ],
                'stand_for' => [
                    'title'       => $this->buildTitleHtml($section->stand_title, $section->stand_title_highlight),
                    'description' => $section->stand_description,
                    'items' => [
                        [
                            'icon'        => $section->stand1_icon,
                            'title'       => $section->stand1_title,
                            'description' => $section->stand1_description,
                        ],
                        [
                            'icon'        => $section->stand2_icon,
                            'title'       => $section->stand2_title,
                            'description' => $section->stand2_description,
                        ],
                        [
                            'icon'        => $section->stand3_icon,
                            'title'       => $section->stand3_title,
                            'description' => $section->stand3_description,
                        ],
                        [
                            'icon'        => $section->stand4_icon,
                            'title'       => $section->stand4_title,
                            'description' => $section->stand4_description,
                        ],
                    ]
                ]
            ];

            return $this->sendResponse($data, 'About Us section details fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch About Us section details.', ['error' => $e->getMessage()]);
        }
    }
}
