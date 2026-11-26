<?php

namespace App\View\Composers;

use App\Models\BlogModel;
use App\Models\HotelsModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Footer data shared by every page. Cached so pages don't re-query it on every
 * request (previously each Livewire component loaded it in render()).
 */
class FooterComposer
{
    private const TTL = 600;

    public function compose(View $view): void
    {
        $view->with('footerProperties', Cache::remember('footer.properties', self::TTL, fn () =>
            HotelsModel::approved()->orderBy('TrendRank')->limit(5)->get(['IDs', 'Place'])));

        $view->with('footerArticles', Cache::remember('footer.articles', self::TTL, fn () =>
            BlogModel::orderByDesc('id')->limit(4)->get(['id', 'BlogTitle'])));
    }
}
