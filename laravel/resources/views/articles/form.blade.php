@csrf
<div class="md-form">
  <!-- タイトル入力フィールド -->
  <label for="title" class="font-weight-bold">タイトル</label>
  <input type="text" id="title" name="title" class="form-control" required
    value="{{ $article->title ?? old('title') }}">
</div>

<div class="form-group">
  <!-- タグ入力コンポーネント -->
  <label for="tags" class="font-weight-bold">タグ</label>
  <article-tags-input id="tags" :initial-tags='@json($tagNames ?? [])'
    :autocomplete-items='@json($allTagNames ?? [])'>
  </article-tags-input>
</div>

<div class="form-group">
  <!-- 本文入力フィールド -->
  <label for="body" class="font-weight-bold">本文</label>
  <textarea name="body" id="body" required class="form-control" rows="16" placeholder="本文">{{ $article->body ?? old('body') }}</textarea>
</div>

<!-- 画像アップロードコンポーネント -->
<image-upload :existing-image-urls="{{ $article->image_paths ?? json_decode('') }}"></image-upload>
