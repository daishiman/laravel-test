<?php

namespace Tests\Feature\Controllers\Mypage;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see App\Http\Controllers\Mypage\BlogMypageController
 */
class BlogMypageControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test index
     */
    public function 認証している場合に限りマイページを開ける()
    {
        // 認証していない場合
        $this->get('mypage/blogs')
            ->assertRedirect('mypage/login');

        // 認証済みの場合
        // $user = User::factory()->create();

        // $this->actingAs($user)
        //     ->get('mypage/blogs')
        //     ->assertOk();
        $this->login();

        $this->get('mypage/blogs')
            ->assertOk();
    }
}
