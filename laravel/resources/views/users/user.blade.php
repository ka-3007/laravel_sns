<div class="card mt-3">
  <div class="card-body">
    <div class="d-flex flex-row align-items-center">
      <!-- プロフィール -->
      <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
        <i class="fas fa-user-circle fa-3x"></i>
      </a>

      <!-- フォローボタン -->
      @if (Auth::id() !== $user->id)
        <follow-button class="ml-auto" :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'
          :authorized='@json(Auth::check())' endpoint="{{ route('users.follow', ['name' => $user->name]) }}">
        </follow-button>
      @endif
    </div>

    <!-- ユーザー名 -->
    <h2 class="h5 card-title m-0">
      <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark"
        style="font-weight: 600; font-size: 18px;">
        {{ $user->name }}
      </a>
    </h2>
  </div>

  <!-- フォロー数・フォロワー数 -->
  <div class="card-body pt-0">
    <div class="card-text">
      <a href="{{ route('users.followings', ['name' => $user->name]) }}" class="text-muted mr-3"
        style="font-size: 14px;">
        <span id="followings-count" style="font-weight: 600;">{{ $user->count_followings }}</span> フォロー
      </a>
      <a href="{{ route('users.followers', ['name' => $user->name]) }}" class="text-muted" style="font-size: 14px;">
        <span id="followers-count" style="font-weight: 600;">{{ $user->count_followers }}</span> フォロワー
      </a>
    </div>
  </div>
</div>
