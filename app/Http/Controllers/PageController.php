<?php

namespace App\Http\Controllers;

use App\Http\Resolvers\WikiResolver;
use App\Models\Category;
use App\Models\Edit;
use App\Models\Interlink;
use App\Models\Page;
use App\Models\Redirect;
use DB;
use Auth;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        if (config('security.permissions.page.view') !== true)
        {
            $this->middleware('auth');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('all', Page::class);

        if ($request->query('namespace'))
        {
            $namespace = $request->query('namespace');
        }
        else
        {
            $namespace = null;
        }

        $pages = DB::table('pages')
        ->select(DB::raw('*, LEFT(`title`, 1) as `first_letter`'))
        ->whereNull('deleted_at')
        ->where('namespace', $namespace)
        ->orderBy('title')
        ->paginate(100);

        return view('special.all', [
            'pages' => $pages,
            'namespace' => $namespace,
            '_title' => 'All Pages'
        ]);
    }

    /**
     * Show the form for creating a new page.
     *
     * @param null $reference
     *
     * @return \Illuminate\Http\Response
     */
    public function create($reference = null)
    {
        $this->authorize('create', Page::class);

        $resolver = new WikiResolver($reference);

        $page = $resolver->returnPageObject();

        $templates = Page::Namespace('template')->select('id','title')->get();

        if ($page->count() === 0)
        {
            $reference = str_replace('_', ' ', $reference);

            // There is no page by that name! Show the creation form
            $page = new Page();
            $page->title = $reference;

            return view('page.create', [
                'page' => $page,
                '_title' => 'Create Page ' . $page->title,
                'templates' => $templates
            ]);
        }

        return redirect(route('page.show', ['reference' => $page->combinedReference]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Page::class);

        // Validation
        $this->validate($request, [
            'title' => 'required|unique:pages,title',
        ]);
        
        // Not much to validate so lets start
        // Create the new page instance
        $resolver = new WikiResolver($request->title);

        if (str_contains($resolver->namespace, WikiResolver::$protectedNamespaces))
        {
            throw new \Exception("You are not allowed to use a restricted namespace");
            return null;
        }

        $page = new Page();
        $page->title = trim($resolver->title);

        if ($resolver->namespace !== null)
        {
            $page->namespace = $resolver->namespace;
        }

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

        return redirect(route('page.show', ['reference' => $page->combinedReference]));
        
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
        $resolver = new WikiResolver($reference);

        $page = $resolver->returnPageObject();

        if ($page->count() === 0)
        {
            // Check redirects
            $redirect = Redirect::where('title', $reference)->get();

            if ($redirect->count() !== 0)
            {
                $request->session()->flash('redirectedFrom', $reference);
                return redirect(route('page.show', ['reference' => $redirect->first()->page->combinedReference]));
            }

            return $this->suggestCreate($reference);
        }

        $this->authorize('view', $page);

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

        $resolver = new WikiResolver($reference);
        $page = $resolver->returnPageObject();

        if ($page->count() === 0)
        {
            return $this->suggestCreate($reference);
        }

        $this->authorize('update', $page);

        return view('page.edit', [
            'page' => $page,
            'tabsLeft' => $page->tabsLeft(),
            '_url' => route('page.show', ['reference' => $page->combinedReference])
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
        $resolver = new WikiResolver($reference);
        $page = $resolver->returnPageObject();

        $this->authorize('update', $page);

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

        // Update the edit table
        $edit = new Edit();
        $edit->user_id = Auth::id();
        $edit->action = 'edited';
        $page->edits()->save($edit);

        return redirect(route('page.show', ['reference' => $page->combinedReference]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Page::class);
    }

    protected function suggestCreate($reference)
    {
        $this->authorize('all', Page::class);

        $resolver = new WikiResolver($reference);

        // There is no page by that name in the specified namespace! Suggest the user creates a new one.
        $page = new Page();
        $page->title = $resolver->title;
        $page->namespace = $resolver->namespace;
        $interlinks = Interlink::forPage($page->title, $page->namespace)->get();

        return view('page.404', [
            'page' => $page,
            'linksFrom' => $interlinks,
            '_title' => 'Missing Page ' . $page->combinedReference
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

    public function getTemplate($id)
    {
        $template = Page::where('id', $id)
            ->where('namespace', 'template');

        if ($template->count() !== 1)
        {
            return response("Not found", 404);
        }

        return response($template->first()->current_version->markdown);
    }
}
