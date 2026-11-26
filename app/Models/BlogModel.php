<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogModel extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    public function comments(): HasMany
    {
        return $this->hasMany(BlogCommentCount::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('Approval', 'Approved');
    }
}
