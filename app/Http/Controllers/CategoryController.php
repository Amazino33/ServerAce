<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $gigs = $category->gigs()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('pages.category', compact('category','gigs'));
    }
}
