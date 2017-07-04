<?php

namespace App\Http\Controllers;

use App\Models\Edit;
use App\Models\Infobox;
use Illuminate\Http\Request;
use App\Models\Page;
use Auth;
use Symfony\Component\Yaml\Exception\ParseException;

class InfoboxController extends Controller
{
    public function edit($reference)
    {
        $page = Page::findByTitle($reference);

        $this->authorize('update', $page);

        if ($page->count() === 0)
        {
            return $this->suggestCreate($reference);
        }

        $this->authorize('view', $page);

        return view('page.infobox', [
            'page' => $page,
            'tabsLeft' => $page->tabsLeft(),
            '_url' => route('infobox.edit', ['reference' => $page->reference]),
            '_cm' => true
        ]);
    }

    public function save(Request $request, $reference)
    {
        // Get Page
        $page = Page::findByTitle($reference);

        $this->authorize('update', $page);

        if ($page->count() === 0)
        {
            return $this->suggestCreate($reference);
        }

        $page->infobox = $request->yaml;

        $page->save();

        // Update the edit table
        $edit = new Edit();
        $edit->user_id = Auth::id();
        $edit->action = 'edited';
        $page->edits()->save($edit);

        return redirect(route('page.show', ['reference' => $page->reference]));
    }

    public function preview(Request $request)
    {
        $infobox = new Infobox($request->yaml);

        try {
            $html = response($infobox->renderHTML(), 200);

            return $html;
        }
        catch (ParseException $exception)
        {
            return response($exception->getMessage(), 200);
        }

    }
}
