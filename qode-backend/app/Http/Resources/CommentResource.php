<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>p
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => htmlspecialchars_decode($this->content),
            'user' => $this->user->name,
            'created_at' => date('Y-m-d H:i:s' , strtotime($this->created_at))
        ];
    }
}
