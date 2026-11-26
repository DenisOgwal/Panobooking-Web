<?php

namespace App\Livewire;

use App\Models\BlogCommentCount;
use App\Models\BlogModel;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

/** /BlogDetail?blogslug={id}: article with moderated comments. */
class BlogDetailComponent extends Component
{
    #[Url(as: 'blogslug')]
    public $url_blogslug;

    #[Validate('required|min:3|max:120', as: 'name')]
    public string $inputname = '';

    #[Validate('required|email|max:190', as: 'email')]
    public string $email = '';

    #[Validate('required|min:20|max:3000', as: 'comment')]
    public string $comments = '';

    public function comment(): void
    {
        $this->validate();
        $post = BlogModel::find($this->url_blogslug);
        abort_if(! $post, 404);

        BlogCommentCount::create([
            'blog_model_id' => $post->id,
            'BlogID' => $post->id,
            'Comment' => $this->comments,
            'NameOfCommenter' => $this->inputname,
            'CommentEmail' => $this->email,
        ]);

        $this->reset('inputname', 'email', 'comments');
        $this->dispatch('alert', type: 'success', message: 'Thanks! Your comment will appear once it has been approved.');
    }

    public function render()
    {
        $post = BlogModel::find($this->url_blogslug);
        abort_if(! $post, 404);

        return view('livewire.blog-detail-component', [
            'post' => $post,
            'approvedComments' => $post->approvedComments()->orderByDesc('id')->get(),
            'more' => BlogModel::where('id', '!=', $post->id)->orderByDesc('Date')->limit(3)->get(),
        ])->layout('components.layouts.app', ['title' => $post->BlogTitle, 'description' => $post->BlogShortDescription]);
    }
}
