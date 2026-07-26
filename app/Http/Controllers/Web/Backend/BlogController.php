<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blogs = Blog::latest()->get();
            return DataTables::of($blogs)
                ->addColumn('checkbox', function ($blog) {
                    return '<input type="checkbox" class="form-check-input row-checkbox" value="' . $blog->id . '">';
                })
                ->addIndexColumn()
                ->addColumn('image', function ($blog) {
                    if ($blog->image) {
                        return '<img src="' . asset($blog->image) . '" alt="Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
                    }
                    return '—';
                })
                ->addColumn('title', fn($blog) => $blog->title)
                ->addColumn('published_at', fn($blog) => $blog->published_at->format('M d, Y h:i A'))
                ->addColumn('status', function ($blog) {
                    return '<div class="form-check form-switch mb-2">
                                <input class="form-check-input" onclick="statusBlog(' . $blog->id . ')" type="checkbox" ' . ($blog->status == 1 ? 'checked' : '') . '>
                            </div>';
                })
                ->addColumn('action', function ($blog) {
                    return '
                        <a href="' . route('backend.blog.show', $blog->id) . '" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-eye"></i>
                        </a>
                        <a href="' . route('backend.blog.edit', $blog->id) . '" class="btn btn-info btn-sm">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <button type="button" onclick="deleteData(\'' . route('backend.blog.destroy', $blog->id) . '\')" class="btn btn-danger btn-sm del">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    ';
                })
                ->rawColumns(['checkbox', 'image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.blog.index');
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('backend.layout.blog.show', compact('blog'));
    }

    public function create()
    {
        return view('backend.layout.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:4096',
            'content'      => 'required|string',
            'status'       => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Ensure unique slug
        $count = Blog::where('slug', 'LIKE', "{$data['slug']}%")->count();
        if ($count > 0) {
            $data['slug'] .= '-' . ($count + 1);
        }

        if ($request->hasFile('image')) {
            $data['image'] = fileUpload($request->file('image'), 'blogs');
        }

        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()->route('backend.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('backend.layout.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:4096',
            'content'      => 'required|string',
            'status'       => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();
        if ($request->title !== $blog->title) {
            $data['slug'] = Str::slug($request->title);
            $count = Blog::where('slug', 'LIKE', "{$data['slug']}%")->where('id', '!=', $blog->id)->count();
            if ($count > 0) {
                $data['slug'] .= '-' . ($count + 1);
            }
        }

        if ($request->hasFile('image')) {
            if ($blog->image) {
                $data['image'] = fileUpdate($request->file('image'), 'blogs', $blog->image);
            } else {
                $data['image'] = fileUpload($request->file('image'), 'blogs');
            }
        }

        $blog->update($data);

        return redirect()->route('backend.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        try {
            if ($blog->image) {
                // If there's a fileDelete helper, use it:
                if (function_exists('fileDelete')) {
                    fileDelete($blog->image);
                } else if (file_exists(public_path($blog->image))) {
                    unlink(public_path($blog->image));
                }
            }
            $blog->delete();
            return response()->json(['success' => true, 'message' => 'Blog post deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete blog post.']);
        }
    }

    public function status($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->status = $blog->status == 1 ? 0 : 1;
        $blog->save();

        return response()->json(['success' => true, 'message' => 'Blog status updated successfully.']);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        try {
            $blogs = Blog::whereIn('id', $ids)->get();
            foreach ($blogs as $blog) {
                if ($blog->image) {
                    if (function_exists('fileDelete')) {
                        fileDelete($blog->image);
                    } else if (file_exists(public_path($blog->image))) {
                        unlink(public_path($blog->image));
                    }
                }
                $blog->delete();
            }
            return response()->json(['success' => true, 'message' => 'Selected blogs deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete selected blogs.']);
        }
    }
}
