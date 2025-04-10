<nav class="navbar navbar-expand navbar-dark" style="background-color: #1DA1F2;"> <!-- Twitterの青色 -->
  <a class="navbar-brand" href="/" style="font-weight: bold;">
    <i class="fas fa-comment-dots mr-2"></i>laravel-sns
  </a>

  <ul class="navbar-nav ml-auto">
    @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}" style="font-weight: 500;">ユーザー登録</a>
      </li>
    @endguest

    @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}" style="font-weight: 500;">ログイン</a>
      </li>
    @endguest

    @auth
      <li class="nav-item">
        <a class="nav-link" href="{{ route('articles.create') }}" style="font-weight: 500;">
          <i class="fas fa-pen mr-1"></i>投稿する
        </a>
      </li>
    @endauth

    @auth
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false" style="font-weight: 500;">
          <i class="fas fa-user-circle"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
          <button class="dropdown-item" type="button"
            onclick="location.href='{{ route('users.show', ['name' => Auth::user()->name]) }}'" style="font-weight: 500;">
            マイページ
          </button>
          <div class="dropdown-divider"></div>
          <button form="logout-button" class="dropdown-item" type="submit" style="font-weight: 500;">
            ログアウト
          </button>
        </div>
      </li>
      <form id="logout-button" method="POST" action="{{ route('logout') }}">
        @csrf
      </form>
    @endauth
  </ul>
</nav>
