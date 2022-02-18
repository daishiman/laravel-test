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
        $blog1 = Blog::factory()->create();
        $blog2 = Blog::factory()->create();
        $blog3 = Blog::factory()->create();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('index');
        $response->assertSee($blog1->title);
        $response->assertSee($blog2->title);
        $response->assertSee($blog3->title);

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
    }
}
