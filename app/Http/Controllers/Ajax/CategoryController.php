<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function search(Request $request)
    {
        $categories = Category::where('title', 'like', '%' . $request->q . '%')
            ->addSelect('id', 'title as text')
            ->limit(20)
            ->get();

        $results['results'][] = [];

        foreach($categories as $cat)
        {
            $results['results'][] = $cat['attributes'];
        }

        return json_encode($results);
    }
}
