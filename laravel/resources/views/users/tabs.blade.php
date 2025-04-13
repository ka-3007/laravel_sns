<ul class="nav nav-tabs nav-justified mt-3" style="border-bottom: 1px solid #e1e8ed;">
  <li class="nav-item">
    <a class="nav-link {{ $hasArticles ? 'active' : '' }}" href="{{ route('users.show', ['name' => $user->name]) }}">
      記事
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ $hasLikes ? 'active' : '' }}" href="{{ route('users.likes', ['name' => $user->name]) }}">
      いいね
    </a>
  </li>
</ul>
