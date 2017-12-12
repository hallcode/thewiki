<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function search(Request $request)
    {
        $categories = Category::where('title', 'like', '%' . $request->term . '%')
            ->addSelect('id', 'title as text')
            ->limit(20)
            ->orderBy('text')
            ->get();

        return response()->json(["results" => $categories]);
    }
}
