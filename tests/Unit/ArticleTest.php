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
        // 初期状態では空であることを確認する
        $this->assertEmpty($eloquent->get());
        // ファクトリーでレコード生成
        factory(Article::class)->create();
        // 再度getしたら中身が空でないことを確認し、ファクトリ可能であることを保証
        $this->assertNotEmpty($eloquent->get());
    }
}
