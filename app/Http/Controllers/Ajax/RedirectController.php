<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Page;
use App\Models\Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedirectController extends Controller
{
    public function get(Page $page)
    {
        return $page->redirects->toJson();
    }

    public function add(Page $page, $title)
    {
        $redirect = new Redirect();

        if ($title != "")
        {
            if ($this->isUnique($title) == false)
            {
                return response("Page or redirect with that title already exists.", 422);
            }

            $redirect->title = $title;
            $page->redirects()->save($redirect);

            return response($redirect->toJson(), 201);
        }

        return response("Error in title.", 400);
    }

    public function delete(Redirect $redirect)
    {
        $redirect->delete();

        return response("Redirect deleted.", 200);
    }

    public function isUnique($title)
    {
        $pages = Page::where('title', $title)->count();
        $redirects = Redirect::where('title', $title)->count();

        if ($pages == 0 && $redirects == 0)
        {
            return true;
        }

        return false;
    }
}
