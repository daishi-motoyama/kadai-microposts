@extends('layouts.app')

@section('content')

    {{-- ユーザー一覧 --}}
    @include('users.users', $user = Auth::user())

@endsection
