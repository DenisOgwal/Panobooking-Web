<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCommentCount extends Model
{
    use HasFactory;
    protected $table="blogcomments";
    protected $fillable = [
        'BlogID', 
        'Comment',
        'NameOfCommenter',
        'CommentEmail',
    ];
    public function post()
{
    return $this->belongsTo(BlogModel::class);
}
}
