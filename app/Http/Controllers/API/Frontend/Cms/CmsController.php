<?php

namespace App\Http\Controllers\Api\Frontend\Cms;

use App\Http\Controllers\Api\BaseController;
use App\Models\AboutSection;
use App\Models\WebSetting;
use App\Models\BannerSection;
use App\Models\HowItWorksSection;
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
                            'title'       => $section->standard1_title,
                            'description' => $section->standard1_description,
                        ],
                        [
                            'title'       => $section->standard2_title,
                            'description' => $section->standard2_description,
                        ],
                        [
                            'title'       => $section->standard3_title,
                            'description' => $section->standard3_description,
                        ],
                        [
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
                            'title'       => $section->stand1_title,
                            'description' => $section->stand1_description,
                        ],
                        [
                            'title'       => $section->stand2_title,
                            'description' => $section->stand2_description,
                        ],
                        [
                            'title'       => $section->stand3_title,
                            'description' => $section->stand3_description,
                        ],
                        [
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

    /**
     * Get the Contact Us section data for the frontend.
     */
    public function getContactPage()
    {
        try {
            $settings = WebSetting::first();

            if (!$settings) {
                return $this->sendResponse(null, 'Contact Us section not configured yet.');
            }

            $titleHtml = trim($settings->contact_title);
            if ($settings->contact_title_highlight) {
                $titleHtml .= " <span class='font-play-fair text-primary'>" . trim($settings->contact_title_highlight) . "</span>";
            }

            $data = [
                'badge'       => $settings->contact_badge,
                'title'       => $titleHtml,
                'description' => $settings->contact_subtitle,
                'phone'       => $settings->footer_phone,
                'hours'       => $settings->contact_phone_hours,
                'email'         => $settings->footer_email,
                'response_time' => $settings->contact_email_response,
                'address' => $settings->footer_address,
                'details' => $settings->contact_address_details,
                'map_iframe' => $settings->contact_map_iframe,
                'follow_title'    => $settings->contact_follow_title,
                'follow_subtitle' => $settings->contact_follow_subtitle,
                'facebook'  => $settings->facebook_url,
                'instagram' => $settings->instagram_url,
                'twitter'   => $settings->twitter_url,
                'whatsapp'  => $settings->whatsapp_url,
                'linkedin'  => $settings->linkedin_url,
             
            ];

            return $this->sendResponse($data, 'Contact Us details fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch Contact Us details.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get a specific page details by its slug.
     */
    public function getPageBySlug(string $slug)
    {
        try {
            $page = \App\Models\Page::where('slug', $slug)
                ->where('status', \App\Models\Page::STATUS['ACTIVE'])
                ->first();

            if (!$page) {
                return $this->sendError('Page not found or is inactive.', [], 404);
            }

            $data = [
                'id'           => $page->id,
                'page_title'   => $page->page_title,
                'slug'         => $page->slug,
                'page_content' => $page->page_content,
                'updated_at'   => $page->updated_at->toIso8601String(),
            ];

            return $this->sendResponse($data, 'Page details fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch page details.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get the How It Works page data for the frontend.
     */
    public function getHowItWorksPage()
    {
        try {
            $section = HowItWorksSection::first();

            $bannerData = null;
            if ($section) {
                $formattedTitle = $section->banner_title;
                if ($section->banner_title_highlight_1 && str_contains($formattedTitle, $section->banner_title_highlight_1)) {
                    $formattedTitle = str_replace(
                        $section->banner_title_highlight_1,
                        "<span class='font-play-fair text-primary'>" . $section->banner_title_highlight_1 . "</span>",
                        $formattedTitle
                    );
                }
                if ($section->banner_title_highlight_2 && str_contains($formattedTitle, $section->banner_title_highlight_2)) {
                    $formattedTitle = str_replace(
                        $section->banner_title_highlight_2,
                        "<span class='font-play-fair text-primary'>" . $section->banner_title_highlight_2 . "</span>",
                        $formattedTitle
                    );
                }

                $bannerData = [
                    'subtitle'    => $section->banner_small_badge,
                    'title'       => $formattedTitle,
                    'description' => $section->banner_description,
                    'button_text' => $section->banner_button_text,
                    'point_1'     => $section->banner_point_1,
                    'point_2'     => $section->banner_point_2,
                    'point_3'     => $section->banner_point_3,
                    'mockup'      => [
                        'earnings_value'      => $section->banner_earnings_value,
                        'earnings_comparison' => $section->banner_earnings_comparison,
                        'earnings_change'     => $section->banner_earnings_change,
                        'categories'          => [
                            [
                                'name'    => $section->banner_category1_name,
                                'percent' => $section->banner_category1_percent,
                            ],
                            [
                                'name'    => $section->banner_category2_name,
                                'percent' => $section->banner_category2_percent,
                            ],
                            [
                                'name'    => $section->banner_category3_name,
                                'percent' => $section->banner_category3_percent,
                            ],
                        ],
                    ],
                ];
            }

            $data = [
                'banner' => $bannerData,
                'stats' => $section ? [
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
                ] : [],
                'features' => $section ? [
                    'title'       => $this->buildTitleHtml($section->features_title, $section->features_title_highlight),
                    'description' => $section->features_description,
                    'items' => [
                        [
                            'title'       => $section->feature1_title,
                            'description' => $section->feature1_description,
                            'icon'        => 'ri-link-m',
                        ],
                        [
                            'title'       => $section->feature2_title,
                            'description' => $section->feature2_description,
                            'icon'        => 'ri-bar-chart-line',
                        ],
                        [
                            'title'       => $section->feature3_title,
                            'description' => $section->feature3_description,
                            'icon'        => 'ri-wallet-3-line',
                        ],
                        [
                            'title'       => $section->feature4_title,
                            'description' => $section->feature4_description,
                            'icon'        => 'ri-medal-line',
                        ],
                        [
                            'title'       => $section->feature5_title,
                            'description' => $section->feature5_description,
                            'icon'        => 'ri-shield-check-line',
                        ],
                        [
                            'title'       => $section->feature6_title,
                            'description' => $section->feature6_description,
                            'icon'        => 'ri-shopping-bag-line',
                        ],
                    ]
                ] : null,
                'steps' => $section ? [
                    'title'       => $this->buildTitleHtml($section->steps_title, $section->steps_title_highlight),
                    'description' => $section->steps_description,
                    'items' => [
                        [
                            'step'        => 1,
                            'title'       => $section->step1_title,
                            'description' => $section->step1_description,
                        ],
                        [
                            'step'        => 2,
                            'title'       => $section->step2_title,
                            'description' => $section->step2_description,
                        ],
                        [
                            'step'        => 3,
                            'title'       => $section->step3_title,
                            'description' => $section->step3_description,
                        ],
                        [
                            'step'        => 4,
                            'title'       => $section->step4_title,
                            'description' => $section->step4_description,
                        ],
                    ]
                ] : null,
                'tiers' => $section ? [
                    'title'       => $this->buildTitleHtml($section->tiers_title, $section->tiers_title_highlight),
                    'description' => $section->tiers_description,
                    'items' => [
                        [
                            'name'        => $section->tier1_name,
                            'commission'  => $section->tier1_commission,
                            'sales'       => $section->tier1_sales,
                        ],
                        [
                            'name'        => $section->tier2_name,
                            'commission'  => $section->tier2_commission,
                            'sales'       => $section->tier2_sales,
                        ],
                        [
                            'name'        => $section->tier3_name,
                            'commission'  => $section->tier3_commission,
                            'sales'       => $section->tier3_sales,
                        ],
                        [
                            'name'        => $section->tier4_name,
                            'commission'  => $section->tier4_commission,
                            'sales'       => $section->tier4_sales,
                        ],
                    ]
                ] : null,
            ];

            return $this->sendResponse($data, 'How It Works page details fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch How It Works page details.', ['error' => $e->getMessage()]);
        }
    }
}
