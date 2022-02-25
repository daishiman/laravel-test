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
}
