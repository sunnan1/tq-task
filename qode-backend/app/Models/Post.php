<?php

namespace App\Models;

use App\Services\ElasticsearchService;
use Illuminate\Database\Eloquent\Model;
class Post extends Model
{
    protected $fillable = [
        'title',
        'excerpt',
        'description',
        'image',
        'keywords',
        'meta_title',
        'meta_description',
        'published_at',
        'author_id',
        'created_at',
        'updated_at',
    ];
    protected static function boot()
    {
        parent::boot();
        static::created(function ($post) {
            $post->indexInElasticsearch();
        });
        static::updated(function ($post) {
            $post->indexInElasticsearch();
        });
        static::deleted(function ($post) {
            $post->removeFromElasticsearch();
        });
    }
    public function indexInElasticsearch(): void
    {
        app(ElasticsearchService::class)->index('posts', $this->id, [
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);
    }

    public function removeFromElasticsearch(): void
    {
        app(ElasticsearchService::class)->delete('posts', $this->id);
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id');
    }

}
