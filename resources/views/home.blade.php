@extends('layouts.app')

@section('leftTabs')
<a href="/" class="active tab">Home</a>
<a href="{{ route('page.create') }}" class="tab">Create page</a>
@endsection

@section('rightTabs')
    <a href="{{ route('resource', ['namespace' => 'Special', "reference" => "home"]) }}" class="active tab">Read</a>
    <a href="{{ route('home.edit') }}" class="tab">Edit</a>
@endsection

@section('content')
    <header>
        <h1>Welcome to {{ config('app.name') }}</h1>
    </header>

    <div class="main">
        {!! $welcome_text !!}
    </div>

    @if ($edit)
        <footer>
            Last edited {{ $edit->created_at->diffForHumans() }} by <a href="/">{{ $edit->user->name }}</a>.
        </footer>
    @endif
@endsection
