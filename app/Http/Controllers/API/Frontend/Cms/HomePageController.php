<?php

namespace App\Http\Controllers\API\Frontend\Cms;

use App\Http\Controllers\Api\BaseController;
use App\Models\BannerSection;
use Illuminate\Http\Request;

class HomePageController extends BaseController
{
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
                        'id' => $banner->id,
                        'small_badge' => $banner->small_badge,
                        'title' => $banner->title,
                        'description' => $banner->description,
                        'button_text' => $banner->button_text,
                        'button_link' => $banner->button_link,
                        'image' => $banner->image ? asset($banner->image) : null,
                        'point_1' => $banner->point_1,
                        'point_2' => $banner->point_2,
                        'point_3' => $banner->point_3,
                        'priority' => (int) $banner->priority,
                    ];
                });

            return $this->sendResponse($banners, 'Banner sections fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch banner sections.', ['error' => $e->getMessage()]);
        }
    }
}
