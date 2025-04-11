@extends('app')

@section('title', '記事更新')

@include('nav')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-3 shadow-sm">
          <div class="card-body pt-0">
            @include('error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('articles.update', ['article' => $article]) }}"
                enctype="multipart/form-data" data-loading>
                @csrf
                @method('PATCH')
                @include('articles.form')
                <button type="submit"
                  class="btn btn-primary btn-block d-flex justify-content-center align-items-center gap-2"
                  data-loading-text="更新中...">
                  <span class="spinner-border spinner-border-sm text-light d-none" role="status"
                    aria-hidden="true"></span>
                  <span class="button-text">更新する</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@include('articles.loading')
