<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BlogModel */
class BlogPostResource extends JsonResource
{
    public bool $detail = false;

    public function detailed(): static
    {
        $this->detail = true;

        return $this;
    }

    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->BlogTitle,
            'date' => Present::date($this->Date),
            'summary' => $this->BlogShortDescription,
            'photo' => Present::photo('blog', $this->resource),
            'comments_count' => (int) ($this->approved_comments_count ?? $this->approvedComments()->count()),
        ];

        return $this->detail ? $data + [
            'body' => $this->Description,
            'comments' => $this->approvedComments()->orderByDesc('id')->get()->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->NameOfCommenter,
                'comment' => $c->Comment,
                'date' => Present::date($c->CommentDate ?: $c->created_at),
            ])->all(),
        ] : $data;
    }
}
