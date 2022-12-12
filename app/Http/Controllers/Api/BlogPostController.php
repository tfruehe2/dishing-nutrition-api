<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Factory as Validator;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $blogPost = BlogPost::with(['tags']);

        $request->whenFilled('filters', function ($filters) use ($blogPost) {
            foreach($filters as $filter => $value)
            {
                switch($filter)
                {
                    case 'title':
                        $blogPost->where('title', 'Like', "%{$value}%");
                        break;

                    default:
                        break;
                }
            }
        });

        $request->whenFilled('sortby', function ($sortby) use ($blogPost) {
            foreach($sortby as $field => $order)
            {
                switch($field)
                {
                    case 'title':
                        $blogPost->orderBy('title', $order);
                        break;

                    default:
                        break;
                }
            }
        }, function() use ($blogPost) {
            $blogPost->orderBy('created_at', 'DESC');
        });
        
        return BlogPostResource::collection($blogPost->paginate(config('app.pagination'))->withQueryString())->response()->getData(true);
    }

    public function store(Request $request, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'title' => ['required','string', Rule::unique('blog_posts', 'title')],
            'description' => ['required', 'string'],
            'feature_image' => ['required', 'string'],
            'contentHTML' => ['required', 'string'],
            'contentJson' => ['nullable', 'json'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $blogPost = BlogPost::create([...$validate->validated(), 'author_id' => $request->user()->id]);

            return response()->json([
                'blog_id' => $blogPost->id,
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 422
            ], 422);
        }
    }

    public function show(BlogPost $blogPost)
    {
        $blogPost->load(['tags']);

        return new BlogPostResource($blogPost);
    }

    public function update(Request $request, BlogPost $blogPost, Validator $validator)
    {
        $validate = $validator->make($request->all(), [
            'title' => ['required','string', Rule::unique('blog_posts', 'title')->ignore($blogPost->id)],
            'description' => ['required', 'string'],
            'feature_image' => ['required', 'string'],
            'contentHTML' => ['required', 'string'],
            'contentJson' => ['nullable', 'json'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => implode(', ', $validate->errors()->all()),
                'status_code' => 400
            ], 400);
        }

        try {
            $blogPost->update($validate->validated());

            $blogPost->refresh();
            $blogPost->load(['tags']);
            return response()->json([
                'recipes' => new BlogPostResource($blogPost),
                'status_code' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 422
            ], 422);
        }
    }

    public function destroy($id)
    {
        BlogPost::findOrFail($id)->delete();
        return response()->json([
            'data' =>[
                'message' => "Blog with ID: {$id} has been deleted.",
                'id' => $id
            ],
            'status_code' => 200
        ], 200);
    }
}
