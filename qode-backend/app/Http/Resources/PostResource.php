<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = $this->title;
        if ($request->filled('locale')) {
            $title = $request->get('locale') == 'ar' ? $this->title_ar : $this->title;
        }
        return [
            'id' => $this->id,
            'title' => $title,
            'description' => $this->description,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : '',
            'keywords' => $this->keywords ?? '',
            'meta_title' => $this->meta_title ?? '',
            'meta_description' => $this->meta_description ?? '',
            'published_at' => $this->published_at,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
