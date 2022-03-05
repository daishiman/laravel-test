<?php

namespace App\Http\Controllers;

use App\StrRandom;
use App\Models\Blog;
use Illuminate\Http\Request;
use Facades\Illuminate\Support\Str;

class BlogViewController extends Controller
{
    public function index()
    {
        // $blogs = Blog::get();
        $blogs = Blog::with('user')
            // ->where('status', Blog::OPEN)
            ->onlyOpen()
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->get();

        return view('index', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        // if ($blog->status == Blog::CLOSED) {
        //     abort(403);
        // }
        if ($blog->isClosed()) {
            abort(403);
        }

        // $random = Str::random(10);

        // $random = (new StrRandom)->random(10);

        $random = app(StrRandom::class)->random(10);

        return view('blog.show', compact('blog', 'random'));
    }
}
