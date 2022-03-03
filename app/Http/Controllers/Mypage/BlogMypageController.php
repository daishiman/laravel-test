<?php

namespace App\Http\Controllers\Mypage;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogMypageController extends Controller
{
    public function index()
    {
        // $blogs = Blog::where('user_id', auth()->user()->id)->get();
        $blogs = auth()->user()->blogs;

        return view('mypage.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('mypage.blog.create');
    }
}
