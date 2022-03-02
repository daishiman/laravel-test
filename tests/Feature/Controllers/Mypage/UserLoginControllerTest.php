<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see App\Http\Controllers\Mypage\UserLoginController
 */
class UserLoginControllerTest extends TestCase
{
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
}
