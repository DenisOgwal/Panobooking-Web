<?php

namespace App\Livewire;

use App\Models\BlogModel;
use Livewire\Component;
use Livewire\WithPagination;

/** /blog: latest travel guides. */
class BlogPage extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = BlogModel::withCount('approvedComments')->orderByDesc('Date')->orderByDesc('id')->paginate(9);

        return view('livewire.blog-page', ['posts' => $posts])
            ->layout('components.layouts.app', ['title' => 'Travel blog']);
    }
}
