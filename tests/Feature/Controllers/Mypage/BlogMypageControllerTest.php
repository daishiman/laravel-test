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

        $this->get('mypage/blogs/edit/1')
            ->assertRedirect($urlLogin);

        $this->post('mypage/blogs/edit/1')
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

    /**
     * @test edit
     */
    public function 他人のブログの編集画面は開けない()
    {
        $blog = Blog::factory()->create();

        $this->login();

        $this->get('mypage/blogs/edit/' . $blog->id)
            ->assertForbidden();
    }

    /**
     * @test update
     */
    public function 他人のブログは更新できない()
    {
        $validData = [
            'title'  => '新タイトル',
            'body'   => '新本文',
            'status' => '1',
        ];

        $blog = Blog::factory()->create();

        $this->login();

        $this->post('mypage/blogs/edit/' . $blog->id, $validData)
            ->assertForbidden();

        $this->assertDatabaseMissing('blogs', $validData);

        $this->assertCount(1, Blog::all());
        $this->assertEquals($blog->toArray(), Blog::first()->toArray());
    }

    /**
     * @test destroy
     */
    public function 他人のブログは削除できない()
    {
        $this->markTestIncomplete('まだ');
    }

    /**
     * @test edit
     */
    public function 自分のブログの編集画面は開ける()
    {
        $blog = Blog::factory()->create();

        $this->login($blog->user);

        $this->get('mypage/blogs/edit/' . $blog->id)
            ->assertOK();
    }

    /**
     * @test update
     */
    public function 自分のブログは更新できる()
    {
        $validData = [
            'title'  => '新タイトル',
            'body'   => '新本文',
            'status' => '1',
        ];

        $blog = Blog::factory()->create();

        $this->login($blog->user);

        $this->post('mypage/blogs/edit/' . $blog->id, $validData)
            ->assertRedirect('mypage/blogs/edit/' . $blog->id);

        $this->get('mypage/blogs/edit/' . $blog->id)
            ->assertSee('ブログを更新しました');

        $this->assertDatabaseHas('blogs', $validData);

        $this->assertCount(1, Blog::all());

        // 項目が少ないときは fresh()を使う
        $this->assertEquals('新タイトル', $blog->fresh()->title);
        $this->assertEquals('新本文', $blog->fresh()->body);

        // 項目が多いときはrefresh()を使う
        $blog->refresh();
        $this->assertEquals('新タイトル', $blog->title);
        $this->assertEquals('新本文', $blog->body);
    }
}
