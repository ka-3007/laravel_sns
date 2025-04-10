@if ($errors->any())
  <div class="alert alert-danger rounded-3 p-3 mb-4">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
