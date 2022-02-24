<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
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
        Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title'  => 'ブログA',
        ]);
        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $response = $this->get('/');

        $response->assertDontSee('ブログA');
        $response->assertSee('ブログB');
        $response->assertSee('ブログC');
    }

    /** @test factory の観察  */
    public function factoryの観察()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue(true);
    }

    /** @test scopeOnlyOpen  */
    public function ブログの公開・非公開のscope()
    {
        $blog1 = Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title'  => 'ブログA',
        ]);
        $blog2 = Blog::factory()->create(['title' => 'ブログB']);
        $blog3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();

        $this->assertFalse($blogs->contains($blog1));
        $this->assertTrue($blogs->contains($blog2));
        $this->assertTrue($blogs->contains($blog3));
    }
}
