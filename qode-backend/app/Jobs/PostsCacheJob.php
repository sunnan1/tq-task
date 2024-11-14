<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class PostsCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Cache::tags(['posts_list'])->flush();
        $perPage = 50;
        $totalRecords = Post::count();
        $totalPages = ceil($totalRecords / $perPage);
        for ($page = 1; $page <= $totalPages; $page++) {
            $cacheKey = "post_page_{$page}";
            Cache::tags(['posts_list'])->put($cacheKey, Post::orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page));
        }
    }
}
