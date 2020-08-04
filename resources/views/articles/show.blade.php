@extends('app')

@section('title', 'トップページ')

@section('content')
  @include('nav')
  <div class="container mt-5">
    <div class="row">
      <div class="col">
        <h2 class="font-weight-bold">{{ $article->title }}</h2>
      </div>
      <div class="col">
        <div class="d-flex float-right">
          <a href="{{ route('articles.edit', ['article' => $article]) }}">
            <button class="editdelbutton btn-sm btn-danger"><i class="fas fa-wrench mr-1"></i>Edit</button>
          </a>
          <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="editdelbutton btn-sm btn-danger"><i class="fas fa-wrench mr-1"></i>Delete</button>
          </form>
        </div>
      </div>
    </div>
    <hr class="mb-5">
    <!--Section: Content-->
    <section class="dark-grey-text">
      @include('articles.article')
    </section>
    <!--Section: Content-->

  </div>
@endsection