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
        $response->assertStatus(200);
    }

    public function testCanSeeShow()
    {
        $article = factory(Article::class)->create();

        $response = $this->get(route('articles.show', ['article' => $article]));

        $response->assertStatus(200)
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
            ->assertSee('action="' . route('articles.store') . '"', false)
            ->assertSee('name="title"', false)
            ->assertSee('name="body"', false);

        $title = Str::random(50);
        $body = Str::random(500);
        $response = $this->from(route('articles.create'))->post(route('articles.store'), [
            'title' => $title,
            'body' => $body,
        ]);
        $response->assertRedirect(route('articles.index'));
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
            ->assertSee('action="' . route('articles.update', ['article' => $article]) . '"', false)
            ->assertSee('name="title"', false)
            ->assertSee('name="body"', false);

        $title = Str::random(50);
        $body = Str::random(500);
        $response = $this->from(route('articles.edit', ['article' => $article]))->patch(route('articles.update', ['article' => $article]), [
            'title' => $title,
            'body' => $body,
        ]);
        $response->assertRedirect(route('articles.show', ['article' => $article]));
        $this->assertDatabaseHas('articles', ['title' => $title, 'body' => $body]);
    }

    public function testCanDelete()
    {
        $article = factory(Article::class)->create();
        $this->assertDatabaseHas('articles', ['id' => $article->id]);

        $response = $this->from(route('articles.show', ['article' => $article]))->delete(route('articles.destroy', ['article' => $article]));
        $response->assertRedirect(route('articles.index'));

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
