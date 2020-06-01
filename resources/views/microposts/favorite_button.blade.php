<div class="btn-group" role="group" aria-label="favorite&delete group">
    @if (Auth::user()->is_favorites($micropost->id))
        {{-- お気に入りを外すフォームボタン --}}
        {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfavorite', ['class' => 'btn btn-outline-warning btn-sm']) !!}
        {!! Form::close() !!}
    @else
        {{--お気に入りのフォームボタン --}}
        {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
            {!! Form::submit('Favorite', ['class' => 'btn btn-outline-success btn-sm']) !!}
        {!! Form::close() !!}
    @endif
    @if (Auth::id() == $micropost->user_id)
        {{-- 投稿削除ボタンのフォーム --}}
        {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
        {!! Form::close() !!}
    @endif
</div>