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

    /** factory の観察  */
    public function factoryの観察()
    {
        $blog = Blog::factory()->create();

        var_dump($blog->toArray());

        var_dump(User::get()->toArray());

        $this->assertTrue(true);
    }
}
