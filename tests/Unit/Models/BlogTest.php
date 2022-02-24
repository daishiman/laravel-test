<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class BlogTest extends TestCase
{
    use RefreshDatabase;
    /** @test user  */
    public function userリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(User::class, $blog->user);
    }
}
