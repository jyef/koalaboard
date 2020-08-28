<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');
        return view('articles.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request, Article $article)
    {
        $this->processImage($request, $article);

        $article->title = $request->title;
        $article->body = $request->body;
        $article->save();
        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    public function edit(Article $article)
    {
        if(isset($article->image)){
            preg_match('/\w+\.(\w+)/', $article->image, $matches);
            $extension = $matches[1];
            $picture = Storage::disk('image')->get($article->image);
            $picture_base64 = 'data:image/' . $extension . ';base64,' . base64_encode($picture);
        } else {
            $picture_base64 = null;
        }
        return view('articles.edit', ['article' => $article, 'picture_base64' => $picture_base64]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        Storage::disk('image')->delete($article->image);
        $this->processImage($request, $article);

        $article->title = $request->title;
        $article->body = $request->body;
        $article->save();
        return redirect()->route('articles.show', ['article' => $article]);
    }

    public function destroy(Article $article)
    {
        if(isset($article->image)){
            Storage::disk('image')->delete($article->image);
        }
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function processImage($request, $article)
    {
        if(isset($request->image)){
            preg_match('/data:image\/(\w+);base64,/', $request->image, $matches);
            $extension = $matches[1];

            $img = preg_replace('/^data:image.*base64,/', '', $request->image);
            $img = str_replace(' ', '+', $img);
            $fileData = base64_decode($img);

            $fileName = md5($img).'.'.$extension;
            Storage::disk('image')->put($fileName, $fileData);
            $article->image = $fileName;
        } else {
            $article->image = null;
        }
    }
}
