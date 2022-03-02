<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('mypage/login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email:filter'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($data)) {
            throw ValidationException::withMessages(['email' => 'メールアドレスかパスワードが間違っています。']);
        }

        return redirect('mypage/blogs');
    }
}
