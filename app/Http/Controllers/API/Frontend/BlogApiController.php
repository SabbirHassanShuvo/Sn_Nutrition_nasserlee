<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\API\BaseController;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogApiController extends BaseController
{
    /**
     * Get a paginated list of active blogs.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 12);
            $blogs = Blog::where('status', 1)
                ->latest('published_at')
                ->paginate($perPage);

            // Transform data structure to match frontend needs
            $blogs->getCollection()->transform(function ($blog) {
                return [
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'image'         => $blog->image ? asset($blog->image) : null,
                    'description'   => $blog->content,
                    'publishedDate' => $blog->published_at->format('M d, Y'),
                    'publishedTime' => $blog->published_at->format('g:i:s A'),
                ];
            });

            return $this->sendResponse($blogs, 'Blogs retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve blogs.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get a single blog post details by slug.
     */
    public function show($id)
    {
        try {
            $blog = Blog::where('id', $id)
                ->where('status', 1)
                ->first();

            if (!$blog) {
                return $this->sendError('Blog post not found.', [], 404);
            }

            $data = [
                'id'            => $blog->id,
                'title'         => $blog->title,
                'image'         => $blog->image ? asset($blog->image) : null,
                'description'   => $blog->content,
                'publishedDate' => $blog->published_at->format('M d, Y'),
                'publishedTime' => $blog->published_at->format('g:i:s A'),
            ];

            return $this->sendResponse($data, 'Blog details retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve blog details.', ['error' => $e->getMessage()]);
        }
    }
}
