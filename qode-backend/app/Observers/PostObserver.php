<?php

namespace App\Observers;

use App\Jobs\PostsCacheJob;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->refreshSinglePostCache($post);
        PostsCacheJob::dispatch();
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $this->refreshSinglePostCache($post);
        PostsCacheJob::dispatch();
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        Cache::forget('post_'.$post->id);
        PostsCacheJob::dispatch();
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
    }

    protected function refreshSinglePostCache($post)
    {
        $cacheKey = "post_{$post->id}";
        Cache::forget($cacheKey);
        Cache::tags(['post'])->put($cacheKey, $post);
    }
    public function invalidateCacheAndRebuildIndividual() {
        $chunkSize = 5000;
        Post::chunk($chunkSize, function ($posts) {
            foreach ($posts as $post) {
                $this->refreshSinglePostCache($post);
            }
        });
    }
}
