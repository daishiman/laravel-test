<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test index */
    public function ブログのTOPページを開ける()
    {
        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(3)->create();
        $blog3 = Blog::factory()->hasComments(2)->create();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('index');
        $response->assertSee($blog1->title);
        $response->assertSee($blog2->title);
        $response->assertSee($blog3->title);

        $response->assertSee($blog1->user->name);
        $response->assertSee($blog2->user->name);
        $response->assertSee($blog3->user->name);

        $response->assertSee('(1件のコメント)');
        $response->assertSee('(3件のコメント)');
        $response->assertSee('(2件のコメント)');

        $response->assertSeeInOrder([$blog2->title, $blog3->title, $blog1->title]);
        // タイトルを上書きできる
        // Blog::factory()->create(['title' => 'abcde']);
        // Blog::factory()->create(['title' => 'fghij']);
        // Blog::factory()->create(['title' => 'klmno']);

        // $response = $this->get('/');
        // $response->assertOk();
        // $response->assertSee('abcde');
        // $response->assertSee('fghij');
        // $response->assertSee('klmno');
    }

    /** @test index  */
    public function ブログの一覧、非公開のブログは表示されない()
    {
        Blog::factory()
            ->closed()
            ->create([
                'title'  => 'ブログA',
            ]);
        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $response = $this->get('/');

        $response->assertDontSee('ブログA');
        $response->assertSee('ブログB');
        $response->assertSee('ブログC');
    }

    /** @test show */
    public function ブログの詳細画面が表示でき、コメントが古い順に表示される()
    {
        $blog = Blog::factory()->create();

        // Comment::factory()->create([
        //     'created_at' => now()->sub('2 days'),
        //     'name' => 'an',
        //     'blog_id' => $blog,
        // ]);
        // Comment::factory()->create([
        //     'created_at' => now()->sub('3 days'),
        //     'name' => 'bob',
        //     'blog_id' => $blog,
        // ]);
        // Comment::factory()->create([
        //     'created_at' => now()->sub('1 days'),
        //     'name' => 'chan',
        //     'blog_id' => $blog,
        // ]);

        $blog = Blog::factory()->withCommentsData([
            ['created_at' => now()->sub('2 days'), 'name' => 'an'],
            ['created_at' => now()->sub('3 days'), 'name' => 'bob'],
            ['created_at' => now()->sub('1 days'), 'name' => 'chan'],
        ])->create();
        // dd($blog->comments->toArray())；

        $response = $this->get('blogs/' . $blog->id);

        $response->assertOk();
        $response->assertSee($blog->title);
        $response->assertSee($blog->user->name);
        $response->assertSeeInOrder(['bob', 'an', 'chan']);
    }

    /** @test show */
    public function ブログの非公開は詳細画面が表示できない()
    {
        $blog = Blog::factory()->closed()->create();

        $response = $this->get('blogs/' . $blog->id);

        $response->assertForbidden();
    }

    /** @test factory の観察  */
    public function factoryの観察()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue(true);
    }
}
