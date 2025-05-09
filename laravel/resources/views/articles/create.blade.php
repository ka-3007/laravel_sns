@extends('app')

@section('title', '記事投稿')

@include('nav')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-3 shadow-sm">
          <div class="card-body pt-0">
            @include('error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" data-loading>
                @csrf
                @include('articles.form')

                <button type="submit"
                  class="btn btn-primary btn-block d-flex justify-content-center align-items-center gap-2"
                  data-loading-text="投稿中...">
                  <span class="spinner-border spinner-border-sm text-light d-none" role="status"
                    aria-hidden="true"></span>
                  <span class="button-text">投稿する</span>
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
