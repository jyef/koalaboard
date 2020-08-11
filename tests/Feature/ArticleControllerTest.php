<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Article;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCanSeeIndex()
    {
        $response = $this->get(route('articles.index'));
        // インデックスにアクセスして、200が返ることを確認
        $response->assertStatus(200);
    }

    public function testCanSeeShow()
    {
        $article = factory(Article::class)->create();

        $response = $this->get(route('articles.show', ['article' => $article]));

        $response->assertStatus(200)
            // 詳細ページでレコード固有の文字列が閲覧できることを確認
            ->assertSee($article->title)
            ->assertSee($article->body)
            // showの画面にeditとdeleteへのリンクがあること
            ->assertSee('href="' . route('articles.edit', ['article' => $article]) . '"', false)
            ->assertSee('action="'. route('articles.destroy', ['article' => $article]) . '"', false);
    }

    public function testCanCreate()
    {
        $response = $this->get(route('articles.create'));

        $response->assertStatus(200)
            // 作成フォームがあること
            ->assertSee('action="' . route('articles.store') . '"', false)
            // titleとbodyの入力フォームがあること
            ->assertSee('name="title"', false)
            ->assertSee('name="body"', false);

        $title = Str::random(50);
        $body = Str::random(500);
        $response = $this->from(route('articles.create'))->post(route('articles.store'), [
            'title' => $title,
            'body' => $body,
        ]);
        // postした後インデックスにリダイレクトされることを確認
        $response->assertRedirect(route('articles.index'));
        // DBにデータが作成されていることで新規作成処理を保証する
        $this->assertDatabaseHas('articles', ['title' => $title, 'body' => $body]);
    }

    public function testCanUpdate()
    {
        $article = factory(Article::class)->create();
        $response = $this->get(route('articles.edit', ['article' => $article]));

        $response->assertStatus(200)
            // editの画面に変更前のtitleとbodyが表示されていること
            ->assertSee('value="' . $article->title . '"', false)
            ->assertSee($article->body)
            // 編集フォームがあること
            ->assertSee('action="' . route('articles.update', ['article' => $article]) . '"', false)
            // titleとbodyの入力フォームがあること
            ->assertSee('name="title"', false)
            ->assertSee('name="body"', false);

        $title = Str::random(50);
        $body = Str::random(500);
        $response = $this->from(route('articles.edit', ['article' => $article]))->patch(route('articles.update', ['article' => $article]), [
            'title' => $title,
            'body' => $body,
        ]);
        // patchした後詳細ページにリダイレクトされること
        $response->assertRedirect(route('articles.show', ['article' => $article]));
        // レコードに変更した後のレコードがあることで編集処理を保証する
        $this->assertDatabaseHas('articles', ['title' => $title, 'body' => $body]);
    }

    public function testCanDelete()
    {
        $article = factory(Article::class)->create();
        // テスト前にレコードがDBに存在することを確認
        $this->assertDatabaseHas('articles', ['id' => $article->id]);

        $response = $this->from(route('articles.show', ['article' => $article]))->delete(route('articles.destroy', ['article' => $article]));
        $response->assertRedirect(route('articles.index'));

        // deleteルートにアクセス後、DBからレコードがなくなっていることで削除処理を保証する
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
