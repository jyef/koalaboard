<?php

namespace Tests\Unit;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function testArticle()
    {
        $eloquent = app(Article::class);
        $this->assertEmpty($eloquent->get());
        factory(Article::class)->create();
        $this->assertNotEmpty($eloquent->get());
    }
}
