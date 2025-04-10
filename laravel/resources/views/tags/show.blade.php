@extends('app')

@section('title', $tag->hashtag)

@section('content')
  @include('nav')
  <div class="container">
    <!-- タグ名カード -->
    <div class="card mt-3">
      <div class="card-body">
        <h2 class="h4 card-title m-0 font-weight-bold">{{ $tag->hashtag }}</h2>
        <div class="card-text text-right">
          {{ $tag->articles->count() }}件
        </div>
      </div>
    </div>

    <!-- 記事一覧 -->
    @foreach ($tag->articles as $article)
      @include('articles.card')
    @endforeach
  </div>
@endsection
