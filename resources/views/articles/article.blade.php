<!-- Grid row -->
@if (isset($articles))
  <div class="row align-items-center">
@else
  <div class="row">
@endif

<!-- Grid column -->
<div class="col-lg-5 col-xl-4">

  <!-- Featured image -->
  <div class="view overlay rounded z-depth-1-half mb-lg-0 mb-4 img_outer">
    <img class="img-fluid inner_photo" src="{{ !empty($article->image) ? '/images/' . $article->image : asset('bubbles.png') }}" alt="Sample image">
    <a>
      <div class="mask rgba-white-slight"></div>
    </a>
  </div>

</div>
<!-- Grid column -->

<!-- Grid column -->
<div class="col-lg-7 col-xl-8">

  <!-- Post title -->
  @if (isset($articles))
    <h4 class="font-weight-bold mb-3"><strong>{{ $article->title }}</strong></h4>
  @endif
  <!-- Excerpt -->
  <p class="dark-grey-text">{{ $article->body }}</p>
  <!-- Post data -->
  <p>by <a class="font-weight-bold">Jyef</a>, {{ $article->created_at->format('d/m/Y') }}</p>
  <!-- Read more button -->
  @if (isset($articles))
    <a class="btn btn-primary btn-md mx-0 btn-rounded" href="{{ route('articles.show', ['article' => $article]) }}">Read more</a>
  @endif
</div>
<!-- Grid column -->

</div>
<!-- Grid row -->