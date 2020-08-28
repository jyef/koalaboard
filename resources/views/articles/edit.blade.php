@extends('app')

@section('title', 'トップページ')

@section('content')
  @include('nav')
  <div class="container mt-5">

    <!--Section: Content-->
    <section class="px-md-5 mx-md-5 text-center text-lg-left dark-grey-text">

      <!--Grid row-->
      <div class="row d-flex justify-content-center">

        <!--Grid column-->
        <div class="col-md-6">

          <!-- Default form subscription -->
          <form method="POST" class="text-center" action="{{ route('articles.update', ['article' => $article]) }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <p class="h2 mb-4">Edit post</p>

            @include('error_list')

            <!-- Title -->
            <input type="text" name="title" required class="form-control mb-4" placeholder="Title" value="{{ $article->title }}">

            <!-- Body -->
            <textarea name="body" required class="form-control mb-4" rows="10" placeholder="Body">{{ $article->body }}</textarea>

            <!-- Image Upload -->
            <image-upload
              picture-base64='{{ $picture_base64 }}'
            >
            </image-upload>

            <!-- Sign in button -->
            <button class="btn btn-info btn-block" type="submit">Update</button>


          </form>
          <!-- Default form subscription -->

        </div>
        <!--Grid column-->

      </div>
      <!--Grid row-->

    </section>
    <!--Section: Content-->

  </div>
@endsection