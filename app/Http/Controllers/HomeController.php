<?php

namespace App\Http\Controllers;

use App\Models\Edit;
use App\Parsers\WikiParser;
use Illuminate\Http\Request;

use Storage;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!config('security.general.view') || !config('security.home_page.view'))
        {
            $this->middleware('auth');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Storage::disk('local')->exists('static/welcome.md'))
        {
            $file = Storage::get('static/welcome.md');

            $parser = new WikiParser();

            $html = $parser->parse($file);

        }
        else{
            $html = "";
        }

        $last_edit = Edit::homeEdits()->where('action', 'edit')->get()->last();

        return view('home', ['welcome_text' => $html, 'edit' => $last_edit]);
    }

    public function edit()
    {
        // Permissions
        if ( Auth::guest() && (!config('security.general.edit') || !config('security.home_page.edit') || !config('security.general.view')))
        {
            abort(403, "You are not authorised to edit this page.");
        }

        if (Storage::disk('local')->exists('static/welcome.md'))
        {
            $file = Storage::get('static/welcome.md');
        }
        else{
            $file = "";
        }

        return view('home-edit', ['markdown' => $file]);
    }

    public function store(Request $request)
    {
        // Permissions
        if ( Auth::guest() && (!config('security.general.edit') || !config('security.home_page.edit') || !config('security.general.view')))
        {
            abort(403, "You are not authorised to edit this page.");
        }

        Storage::put('static/welcome.md', $request->markdown);
        
        // Record the Edit
        $edit = new Edit();
        $edit->user_id = Auth::id();
        $edit->parent_id = 0;
        $edit->parent_type = "special:home";
        $edit->action = "edit";
        $edit->save();

        return redirect(route('home'));
    }
}
