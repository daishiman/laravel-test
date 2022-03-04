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

    public function store(Request $request)
    {
        // $data = $request->all(['title', 'body']);

        $data = $this->validateInput();

        $data['status'] = $request->boolean('status');

        $blogs = auth()->user()->blogs()->create($data);

        return redirect('mypage/blogs/edit/' . $blogs->id);
    }

    public function edit(Blog $blog, Request $request)
    {
        if ($request->user()->isNot($blog->user)) {
            abort(403);
        }

        $data = old() ?: $blog;

        return view('mypage.blog.edit', compact('blog', 'data'));
    }

    public function update(Blog $blog, Request $request)
    {
        if ($request->user()->isNot($blog->user)) {
            abort(403);
        }

        $data = $this->validateInput();

        $data['status'] = $request->boolean('status');

        $blog->update($data);


        return redirect(route('mypage.blog.edit', $blog))
            ->with('status', 'ブログを更新しました');
    }

    public function validateInput()
    {
        return request()->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required', 'max:255'],
        ]);
    }
}
