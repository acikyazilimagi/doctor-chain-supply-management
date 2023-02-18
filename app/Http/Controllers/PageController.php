<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function __invoke(string $slug)
    {
        $page = Page::select(['title', 'slug', 'content', 'meta'])->where(['slug' => $slug])->first();

        return response()->json([
            "status" => true ,
            "data" => $page,
        ]);
    }
}
