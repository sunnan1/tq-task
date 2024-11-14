<?php

namespace App\Jobs;

use App\Mail\PostPublished;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class PublishPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;
    /**
     * Create a new job instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->post->indexInElasticsearch();
        Mail::to($this->post->author->email)->send(new PostPublished($this->post));
    }
    public function uniqueId(): string
    {
        return 'publish_post_' . $this->post->id;
    }
}
