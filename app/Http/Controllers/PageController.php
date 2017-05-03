<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Interlink;
use App\Models\Page;
use DB;
use Auth;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = DB::table('pages')
            ->select(DB::raw('*, LEFT(`title`, 1) as `first_letter`'))
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->paginate(100);

        return view('special.all', ['pages' => $pages, '_title' => 'All Pages']);
    }

    public function needed()
    {
        $redlinks = Interlink::select('link_reference', DB::raw('count(`link_reference`) as `count`'))
            ->whereNull('target_page_id')
            ->groupBy('link_reference')
            ->orderByDesc('count')
            ->paginate(25);

        return view('special.needed', ['links' => $redlinks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param null $reference
     *
     * @return \Illuminate\Http\Response
     */
    public function create($reference = null)
    {
        $page = Page::findByTitle($reference);

        if ($page->count() === 0)
        {
            $reference = str_replace('_', ' ', $reference);

            // There is no page by that name! Show the creation form
            $page = new Page();
            $page->title = $reference;

            return view('page.create', [
                'page' => $page,
                '_title' => 'Create Page ' . $page->title
            ]);
        }

        return redirect(route('page.show', ['reference' => $page->reference]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $reference = null)
    {
        // TODO: abort(403) if permissions fail
        
        // Validation
        $this->validate($request, [
            'title' => 'required|unique:pages,title',
        ]);
        
        // Not much to validate so lets start
        // Create the new page instance (not persisted)
        $page = new Page();

        $page->title = $request->title;
        $page->save();

        // Create the version
        $version = $page->versions()->create([
            'markdown' => $request->markdown,
            'user_id' => Auth::id()
        ]);

        // Update cuser created by
        $page->created_by_id = Auth::id();
        // Save version as current
        $page->current_version_id = $version->id;

        $this->setCategories($page, $request->categories);

        $page->save();



        return redirect(route('page.show', ['reference' => $page->reference]));
        
    }

    /**
     * Display the specified resource.
     *
     * @param $reference
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Request $request, $reference)
    {
        $page = Page::findByTitle($reference);

        if ($page->count() === 0)
        {
            return $this->suggestCreate($reference);
        }

        return view('page.show', [
            'page' => $page,
            'tabsLeft' => $page->tabsLeft(),
            '_url' => $request->url()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($reference)
    {
        $page = Page::findByTitle($reference);

        if ($page->count() === 0)
        {
            return $this->suggestCreate($reference);
        }

        return view('page.edit', [
            'page' => $page,
            'tabsLeft' => $page->tabsLeft(),
            '_url' => route('page.show', ['reference' => $page->reference])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $reference)
    {
        // Get Page
        $page = Page::findByTitle($reference);

        if ($page->count() === 0)
        {
            return $this->suggestCreate($reference);
        }

        // No validation, only two form fields and neither are required.

        // Do categories.
        $this->setCategories($page, $request->categories);

        // Only make a new version if the new text does not match the old text.
        if ($request->markdown !== $page->current_version->markdown)
        {
            // Create the version
            $version = $page->versions()->create([
                'markdown' => $request->markdown,
                'user_id' => Auth::id()
            ]);

            // Set it as current
            $page->current_version_id = $version->id;
        }

        $page->save();

        return redirect(route('page.show', ['reference' => $page->reference]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function suggestCreate($reference)
    {
        $reference = str_replace('_', ' ', $reference);

        // There is no page by that name! Suggest the user creates a new one.
        $page = new Page();
        $page->title = $reference;
        $interlinks = Interlink::where('link_reference', $page->title)
            ->orWhere('link_reference', $page->reference)
            ->get();

        return view('page.404', [
            'page' => $page,
            'linksFrom' => $interlinks,
        ]);
    }

    protected function setCategories(Page $page, $category_array)
    {
        // Loop through the provided categories and either get them or create them
        $categories = [];
        if (!empty($category_array))
        {
            foreach ($category_array as $category)
            {
                $search = Category::where('id', $category)->orWhere('title', $category)->limit(5);

                if ($search->count() === 0)
                {
                    // Okay so we know it's a new one because its a string. If they've created a category that's like a year
                    // or something, we'll have to hope there's less than 1000 categories.
                    $newCat = new Category();
                    $newCat->title = $category;
                    $newCat->colour = "grey";
                    $newCat->save();
                    $categories[] = $newCat->id;
                } else
                {
                    // So it's not a string, AND the existing ID does not exist. That means there's a category with this ID / name.
                    $categories[] = $search->get()->first()->id;
                }
            }
        }


        return $page->categories()->sync($categories);
    }
}
