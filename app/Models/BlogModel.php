<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    use HasFactory;
    protected $table="blogs";
    public function comments()
{
    return $this->hasMany(BlogCommentCount::class);
}
}
