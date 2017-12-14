<?php

namespace App\Http\Controllers;

use App\Models\Edit;
use App\Models\Interlink;
use App\Models\Page;
use DB;
use Auth;

class SpecialController extends Controller
{
    public function needed()
    {
        $this->authorize('all', Page::class);

        $redlinks = Interlink::Redlinks()
            ->orderByDesc('count')
            ->orderBy('link_reference')
            ->paginate(40);

        return view('special.needed', ['links' => $redlinks, '_title' => 'Needed Pages']);
    }

    public function recent()
    {
        $this->authorize('all', Page::class);

        $recentChanges = Edit::select(
            '*',
            DB::raw('count(`id`) as `edit_count`'),
            DB::raw('DATE(`created_at`) as `date`'),
            DB::raw('max(`created_at`) as `latest_created_at`'))
            ->whereNotNull('parent_id')
            ->groupBy(DB::Raw('`parent_type`, `parent_id`, `action`, `user_id`, `date`'))
            ->orderByDesc('latest_created_at')
            ->limit(40)
            ->get();

        return view('special.recent', [
            'all_changes' => $recentChanges,
            '_title' => 'Recent Changes'
        ]);
    }

    public function random()
    {
        $this->authorize('all', Page::class);

        $page = Page::inRandomOrder()
            ->whereNull('namespace')
            ->limit(1)
            ->first();

        return redirect(route('page.show', ['reference' => $page->combinedReference]));
    }
}
