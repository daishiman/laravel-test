<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @see \App\Http\Controllers\SignUpController
     * A basic feature test example.
     *
     * @return void
     */

    /**
     * @test index
     */
    public function ユーザー登録画面を開ける()
    {
        $response =  $this->get('signup');
        $response->assertOk();
    }

    /**
     * @test store
     */
    public function ユーザー登録できる()
    {
        // データ検証
        // DBに保存
        // ログインさせてからマイページにリダイレクト

        $validData = User::factory()->valid();

        $response = $this->post('signup', $validData);
        $response->assertOk();

        unset($validData['password']);

        $this->assertDatabaseHas('users', $validData);

        // パスワードの検証
        $user = User::FirstWhere($validData);
        $this->assertNotNull($user);

        $this->assertTrue(Hash::check('abcd1234', $user->password));
    }

    /**
     * @test store
     */
    public function 不正なデータでは登録できない()
    {
        $url = 'signup';

        $this->from($url)
            ->post($url, [])
            ->assertRedirect($url);

        app()->setlocale('testing');

        $this->post($url, ['name' => ''])->assertSessionHasErrors(['name' => 'required']);
        $this->post($url, ['name' => str_repeat('あ', 21)])->assertSessionHasErrors(['name' => 'max']);
        $this->post($url, ['name' => str_repeat('あ', 20)])->assertSessionDoesntHaveErrors(['name']);

        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
        $this->post($url, ['email' => 'aa@ff@com'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['email' => 'aa@ああ@com'])->assertSessionHasErrors(['email' => 'email']);

        User::factory()->create(['email' => 'aa@bbb.com']);
        $this->post($url, ['email' => 'aa@bbb.com'])->assertSessionHasErrors(['email' => 'unique']);

        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);
        $this->post($url, ['password' => 'abcd123'])->assertSessionHasErrors(['password' => 'min']);
        $this->post($url, ['password' => 'abcd1234'])->assertSessionDoesntHaveErrors('password');
    }
}
