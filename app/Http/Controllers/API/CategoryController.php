<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Get all categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status', 'active')->latest()->get();
        
        // Append full image url if image exists
        $categories->map(function ($category) {
            $category->image_url = $category->image ? asset($category->image) : 'https://ui-avatars.com/api/?name=' . urlencode($category->name);
            return $category;
        });

        return $this->sendResponse($categories, 'Categories retrieved successfully.');
    }
}
