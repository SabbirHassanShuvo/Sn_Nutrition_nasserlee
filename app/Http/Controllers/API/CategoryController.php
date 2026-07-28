<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Get all categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::where('status', 'active')->latest();

        $limit = $request->input('limit', $request->input('per_page'));
        if ($limit !== null || $request->has('paginate')) {
            $limitInt = (int) ($limit ?? 10);
            if ($limitInt <= 0) {
                $limitInt = 10;
            }
            $categories = $query->paginate($limitInt);
            $categories->getCollection()->transform(function ($category) {
                return [
                    'id' => $category->id,
                    'title' => $category->name,
                    'query' => $category->slug,
                    'image' => $category->image ? asset($category->image) : 'https://ui-avatars.com/api/?name=' . urlencode($category->name),
                    'color' => $category->color ?? '#FF8000',
                ];
            });

            return $this->sendResponse($categories, 'Categories retrieved successfully.');
        }

        $categories = $query->get();
        
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
