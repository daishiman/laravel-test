<?php

namespace Tests\Feature\Controllers\Mypage;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see App\Http\Controllers\Mypage\UserLoginController
 */
class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test index
     */
    public function ログイン画面を開ける()
    {
        $this->get('mypage/login')
            ->assertOk();
    }

    /**
     * @test login
     */
    public function ログイン時の入力チェック()
    {
        $url = 'mypage/login';

        $this->from($url)
            ->post($url, [])
            ->assertRedirect($url);

        app()->setlocale('testing');

        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
        $this->post($url, ['email' => 'aaa@test@.com'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['email' => 'aaa@あああ@.com'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);
    }

    /**
     * @test login
     */
    public function ログインできる()
    {
        $postData = [
            'email' => 'aaa@test.com',
            'password' => 'abce1234',
        ];

        $dbData = [
            'email' => 'aaa@test.com',
            'password' => bcrypt('abce1234'),
        ];

        $user = User::factory()->create($dbData);

        $this->post('mypage/login', $postData)
            ->assertRedirect('mypage/blogs');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test login
     */
    public function IDを間違えているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'ccc@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $url = 'mypage/login';

        $this->from($url)
            ->post($url, $postData)
            ->assertRedirect($url);

        $this->get($url)
            ->assertSee('メールアドレスかパスワードが間違っています。');

        $this->from($url)
            ->followingRedirects()
            ->post($url, $postData)
            ->assertSee('メールアドレスかパスワードが間違っています。')
            ->assertSee('<h1>ログイン画面</h1>', false);
    }

    /**
     * @test login
     */
    public function パスワードを間違えているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('1234abcd'),
        ];

        $user = User::factory()->create($dbData);

        $url = 'mypage/login';

        $this->from($url)
            ->post($url, $postData)
            ->assertRedirect($url);

        $this->get($url)
            ->assertSee('メールアドレスかパスワードが間違っています。');

        $this->from($url)
            ->followingRedirects()
            ->post($url, $postData)
            ->assertSee('メールアドレスかパスワードが間違っています。')
            ->assertSee('<h1>ログイン画面</h1>', false);
    }
}
