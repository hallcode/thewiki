<?php

namespace App\Http\Controllers;

use App\Http\Resolvers\WikiResolver;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')
            ->select(DB::raw('*, LEFT(`title`, 1) as `first_letter`'))
            ->orderBy('title')
            ->paginate(100);

        return view('category.list', [
            'categories' => $categories,
            '_title' => 'All categories'
        ]);
    }

    public function show($title)
    {
        $resolver = new WikiResolver('Category:'.$title);
        $categoryPage = $resolver->returnPageObject();

        $title = str_replace('_', ' ', $title);

        $category = Category::where('title', $title)->first();

        $categoryChildren = $category->pages()
            ->select(DB::raw('*, LEFT(`title`, 1) as `first_letter`'))
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->paginate(100);

        return view('category.single', [
            'category' => $category,
            'category_page' => $categoryPage,
            'pages' => $categoryChildren,
            '_title' => 'Category:'.$category->title
        ]);
    }
}
