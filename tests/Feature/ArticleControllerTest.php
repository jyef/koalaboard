<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Article;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testResIndex()
    {
        $response = $this->get(route('articles.index'));

        $response->assertStatus(200)
            ->assertViewIs('articles.index');
    }

    public function testResArticles()
    {
        $response = $this->get('/articles');

        $response->assertStatus(302)->
            assertRedirect(route('articles.index'));
    }

    public function testResArticlesCreate()
    {
        $response = $this->get(route('articles.create'));

        $response->assertStatus(200)->
            assertViewIs('articles.create');
    }

    public function testResArticlesArticle()
    {
        // ファクトリで記事を生成
        $article = factory(Article::class)->create();

        // 生成した記事ページに対しgetメソッドを使い、レスポンスを取得
        $response = $this->get('/articles/' . $article->id);

        $response->assertStatus(200)->
            assertViewIs('articles.show');

        // 生成した記事ページのエディットページのレスポンスを取得
        $response = $this->get('/articles/' . $article->id . '/edit');

        $response->assertStatus(200)->
            assertViewIs('articles.edit');
    }

    public function testArticlesDatabase()
    {
        // あらかじめレコードを1件保存しておき、そのレコードの有無をチェックする
        factory(Article::class)->create([
            'title' => 'AAA',
            'body' => 'ABCABC',
        ]);
        factory(Article::class, 10)->create();

        $this->assertDatabaseHas('articles', [
            'title' => 'AAA',
            'body' => 'ABCABC',
        ]);
    }
}
