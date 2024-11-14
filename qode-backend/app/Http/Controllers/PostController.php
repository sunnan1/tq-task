<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\PublishPostJob;
use App\Models\Post;
use App\Services\ElasticsearchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    protected $elasticsearch;

    public function __construct(ElasticsearchService $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index(Request $request) {
        $page = $request->get('page', 1);
        $perPage = 50;
        $offset = ($page - 1) * $perPage;
        $cacheKey = "post_page_{$page}";
        $posts = Cache::tags(['posts_list'])->rememberForever($cacheKey, function () use ($perPage , $offset) {
            return Post::orderBy('created_at', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();
        });
        return response()->json(['message' => 'Data Fetched' , 'data' => PostResource::collection($posts)], 200 , [] , JSON_UNESCAPED_UNICODE);
    }
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            $fields = ['title', 'keywords', 'meta_title', 'meta_description'];
            $results = $this->elasticsearch->searchAllColumns('posts', $query, $fields);
            $hits = $results['hits']['hits'] ?? [];
            $posts = [];
            foreach ($hits as $hit) {
                $posts[] = $hit['_id'];
            }
            $posts = Post::whereIn('id' , $posts)->get()->reverse();
            return response()->json(['message' => 'Data Fetched' , 'data' => PostResource::collection($posts)], 200);
        }catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
    public function store(PostRequest $request)
    {
        try {
            if ($request->hasFile('image')) {
                $request->image = $request->file('image')->store('posts', 'public');
            }
            $post = Post::create([
                'title' => $request->title,
                'excerpt' => $request->excerpt,
                'description' => $request->description,
                'image' => $request->image ?? null,
                'keywords' => $request->keywords ?? null,
                'meta_title' => $request->meta_title ?? null,
                'meta_description' => $request->meta_description ?? null,
                'published_at' => $request->publish_at ?? now(),
                'author_id' => $request->user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $publishAt = $post->published_at;
            if ($publishAt > now()) {
                PublishPostJob::dispatch($post)->delay(Carbon::parse($publishAt));
            } else {
                PublishPostJob::dispatch($post);
            }
            return response()->json(['message' => 'Post Added Successfully' , 'data' => $post], 200);
        }catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }


    public function update(PostRequest $request, Post $post)
    {
        try {
            if ($request->hasFile('image')) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $request->image = $request->file('image')->store('posts', 'public');
            }
            $data = [
                'title' => $request->title,
                'excerpt' => $request->excerpt,
                'description' => $request->description,
                'image' => $request->image ?? null,
                'keywords' => $request->keywords ?? null,
                'meta_title' => $request->meta_title ?? null,
                'meta_description' => $request->meta_description ?? null,
                'published_at' => $request->publish_at ?? now(),
                'updated_at' => now(),
            ];
            $post->update($data);
            $publishAt = $post->published_at;
            if ($publishAt > now()) {
                PublishPostJob::dispatch($post)->delay(Carbon::parse($publishAt));
            } else {
                PublishPostJob::dispatch($post);
            }
            return response()->json(['message' => 'Post Updated Successfully' , 'data' => $post], 200);

        }catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return response()->json(['message' => 'Post Deleted Successfully'], 200);
    }
}
