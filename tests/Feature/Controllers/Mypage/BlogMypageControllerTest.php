<?php

namespace Tests\Feature\Controllers\Mypage;

use Tests\TestCase;
use App\Models\Blog;
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
    public function ゲストはブログを管理できない()
    {
        $urlBlogs = 'mypage/blogs';
        $urlLogin = 'mypage/login';

        $this->get($urlBlogs)
            ->assertRedirect($urlLogin);

        $this->get('mypage/blogs/create')
            ->assertRedirect($urlLogin);

        $this->post('mypage/blogs/create', [])
            ->assertRedirect($urlLogin);
    }

    /**
     * @test index
     */
    public function マイページ、ブログ一覧で自分のデータのみ表示される()
    {
        $user = $this->login();

        $otherBlog = Blog::factory()->create();
        $myBlog    = Blog::factory()->create(['user_id' => $user]);

        $this->get('mypage/blogs')
            ->assertOk()
            ->assertDontSee($otherBlog->title)
            ->assertSee($myBlog->title);
    }

    /**
     * @test create
     */
    public function マイページ、ブログの新規登録画面を開ける()
    {
        $this->login();

        $this->get('mypage/blogs/create')
            ->assertOk();
    }

    /**
     * @test store
     */
    public function マイページ、ブログを新規登録できる（公開の場合）()
    {
        $this->login();
        $validData = Blog::factory()->validData();

        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1');

        $this->assertDatabaseHas('blogs', $validData);
    }

    /**
     * @test store
     */
    public function マイページ、ブログを新規登録できる（非公開の場合）()
    {
        $this->login();
        $validData = Blog::factory()->validData();

        unset($validData['status']);

        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1');

        $validData['status'] = 0;

        $this->assertDatabaseHas('blogs', $validData);
    }

    /**
     * @test store
     */
    public function マイページ、ブログの新規登録のチェック()
    {
        $urlCreate = 'mypage/blogs/create';

        $this->login();

        // $this->from($urlCreate)
        //     ->post($urlCreate, [])
        //     ->assertRedirect($urlCreate);

        app()->setlocale('testing');

        $this->post($urlCreate, ['title' => ''])->assertSessionHasErrors(['title' => 'required']);
        $this->post($urlCreate, ['title' => str_repeat('a', 256)])->assertSessionHasErrors(['title' => 'max']);
        $this->post($urlCreate, ['title' => str_repeat('a', 255)])->assertSessionDoesntHaveErrors(['title' => 'max']);

        $this->post($urlCreate, ['body' => ''])->assertSessionHasErrors(['body' => 'required']);
        $this->post($urlCreate, ['body' => str_repeat('a', 256)])->assertSessionHasErrors(['body' => 'max']);
        $this->post($urlCreate, ['body' => str_repeat('a', 255)])->assertSessionDoesntHaveErrors(['body' => 'max']);
    }
}
