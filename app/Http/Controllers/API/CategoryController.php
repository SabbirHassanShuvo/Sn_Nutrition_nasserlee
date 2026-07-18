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
        
        $formattedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'title' => $category->name,
                'query' => $category->slug,
                'image' => $category->image ? asset($category->image) : 'https://ui-avatars.com/api/?name=' . urlencode($category->name),
                'color' => $category->color ?? '#FF8000',
            ];
        });

        return $this->sendResponse($formattedCategories, 'Categories retrieved successfully.');
    }
}
