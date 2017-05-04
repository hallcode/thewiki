<?php

namespace App\Observers;


use App\Models\Edit;
use App\Models\Interlink;
use App\Models\Page;

use Auth;
use DB;

class PageObserver
{
    public function created(Page $page)
    {
        $edit = new Edit();
        $edit->user_id = Auth::id();
        $edit->action = 'created';
        $page->edits()->save($edit);

        // Search interlinks for name matches and update.
        DB::table('interlinks')
            ->where('link_reference', $page->title)
            ->orWhere('link_reference', $page->reference)
            ->update([
                'target_page_id' => $page->id
            ]);
    }

    public function saved(Page $page)
    {
        // Make sure the interlinks are recorded!
        if (isset($_POST["markdown"]))
        {
            preg_match_all('/\[\[(.+?)\]\]/', $_POST["markdown"], $matches);
            $existing_links = $page->links_to;
            foreach ($matches[1] as $link_text)
            {
                if ($existing_links->where('link_reference', $link_text)->count() === 0 && $existing_links->where(
                        'link_reference',
                        str_replace($link_text, ' ', '_')
                    )->count() === 0
                )
                {
                    // The link is not listed in the interlinks table, so create it.
                    // Find out if the page exists.
                    $target_page = Page::findByTitle($link_text);
                    $target_page->count() !== 0 ? $target_page_id = $target_page->id : $target_page_id = null;

                    // Save to DB
                    Interlink::firstOrCreate(
                        [
                            'page_id' => $page->id,
                            'link_reference' => $link_text,
                            'target_page_id' => $target_page_id
                        ]
                    );
                }
            }

            foreach ($page->links_to->whereNotIn('link_reference', $matches[1]) as $old_link)
            {
                $old_link->delete();
            }
        }
    }

    public function deleted(Page $page)
    {
        $edit = new Edit();
        $edit->user_id = Auth::id();
        $edit->action = 'trashed';
        $page->edits()->save($edit);

        // Search interlinks table for matches and make sure they are cleared
        DB::table('interlinks')
            ->where('link_reference', $page->title)
            ->orWhere('link_reference', $page->reference)
            ->update([
                'target_page_id' => null
            ]);
    }
}