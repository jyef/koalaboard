@extends('app')

@section('title', 'トップページ')

@section('content')
  @include('nav')
  <div class="container mt-5">
    
    <!--Section: Content-->
    <section class="dark-grey-text">
      
      <!-- Section heading -->
      <h2 class="text-center font-weight-bold mb-4 pb-2">Yes!! Recent reviews</h2>
      <!-- Section description -->
      <p class="text-center mx-auto w-responsive mb-5">This site is posted reviews of The Powerpuff Girls episodes.</p>
      
      @foreach($articles as $article)
        @include('articles.article')
        <hr class="my-5">
      @endforeach
    </section>
    <!--Section: Content-->

  </div>
@endsection