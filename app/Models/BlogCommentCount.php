<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogCommentCount extends Model
{
    use HasFactory;

    protected $table = 'blogcomments';

    protected $fillable = ['blog_model_id', 'BlogID', 'Comment', 'NameOfCommenter', 'CommentEmail'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogModel::class, 'blog_model_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('Approval', 'Approved');
    }
}
