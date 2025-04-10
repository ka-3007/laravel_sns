<div class="card mt-3 shadow-sm">
  <div class="card-body d-flex flex-row align-items-center">
    <!-- ユーザーアイコン -->
    <a href="{{ route('users.show', ['name' => $article->user->name]) }}" class="text-dark">
      <i class="fas fa-user-circle fa-2x mr-2"></i>
    </a>

    <div>
      <!-- ユーザー名と投稿日時 -->
      <div class="font-weight-bold">
        <a href="{{ route('users.show', ['name' => $article->user->name]) }}" class="text-dark">
          {{ $article->user->name }}
        </a>
      </div>
      <div class="text-muted small">{{ $article->created_at->diffForHumans() }}</div>
    </div>

    @if (Auth::id() === $article->user_id)
      <div class="ml-auto">
        <!-- 3点リーダー (メニュー) -->
        <div class="dropdown">
          <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('articles.edit', ['article' => $article]) }}">
              <i class="fas fa-pen mr-1"></i> 編集
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $article->id }}">
              <i class="fas fa-trash-alt mr-1"></i> 削除
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>

  <div class="card-body pt-0">
    <!-- 投稿タイトル -->
    <h5 class="card-title">
      <a href="{{ route('articles.show', ['article' => $article]) }}" class="text-dark">
        {{ $article->title }}
      </a>
    </h5>

    <!-- 本文 -->
    <p class="card-text">{{ $article->body }}</p>

    <!-- 画像 -->
    @if ($article->image_paths)
      @php
        $imagePaths = json_decode($article->image_paths, true);
      @endphp

      <div class="mt-3"
        style="display: grid; grid-template-columns: repeat({{ count($imagePaths) > 1 ? 2 : 1 }}, 1fr); gap: 8px;">
        @foreach ($imagePaths as $imagePath)
          <img src="{{ $imagePath }}" alt="記事画像" class="img-fluid rounded">
        @endforeach
      </div>
    @endif
  </div>

  <div class="card-body pt-0 pb-2">
    <!-- いいねボタン -->
    <div class="card-text">
      <article-like :initial-is-liked-by='@json($article->isLikedBy(Auth::user()))'
        :initial-count-likes='@json($article->count_likes)' :authorized='@json(Auth::check())'
        endpoint="{{ route('articles.like', ['article' => $article]) }}">
      </article-like>
    </div>
  </div>

  <!-- タグ表示 -->
  @if ($article->tags->isNotEmpty())
    <div class="card-body pt-0 pb-4 pl-3">
      <div class="card-text  line-height">
        @foreach ($article->tags as $tag)
          <a href="{{ route('tags.show', ['name' => $tag->name]) }}"
            class="badge badge-light text-muted mr-2 mt-1 p-2">
            {{ $tag->hashtag }}
          </a>
        @endforeach
      </div>
    </div>
  @endif
</div>
