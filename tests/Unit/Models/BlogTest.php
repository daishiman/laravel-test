<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test user  */
    public function userリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(User::class, $blog->user);
    }

    /** @test comments */
    public function commentsリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(Collection::class, $blog->comments);
    }

    /** @test scopeOnlyOpen  */
    public function ブログの公開・非公開のscope()
    {
        $blog1 = Blog::factory()
            ->closed()
            ->create([
                'title'  => 'ブログA',
            ]);
        $blog2 = Blog::factory()->create(['title' => 'ブログB']);
        $blog3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();

        $this->assertFalse($blogs->contains($blog1));
        $this->assertTrue($blogs->contains($blog2));
        $this->assertTrue($blogs->contains($blog3));
    }

    /** @test show */
    public function クリスマスの日は、メリークリスマス！と表示される()
    {
        $blog = Blog::factory()->create();

        Carbon::setTestNow('2020-12-24');

        $response = $this->get('blogs/' . $blog->id);

        $response->assertOk();
        $response->assertDontSee('メリークリスマス！');

        Carbon::setTestNow('2020-12-25');

        $response = $this->get('blogs/' . $blog->id);

        $response->assertOk();
        $response->assertSee('メリークリスマス！');
    }

    /** @test isClosed */
    public function ブログで非公開時はtrueを返し、公開時はfalseを返す()
    {
        $blog = Blog::factory()->make();

        $this->assertFalse($blog->isClosed());

        $blog = Blog::factory()->closed()->make();

        $this->assertTrue($blog->isClosed());
    }
}
