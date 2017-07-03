@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Error</a>
@endsection

@section('content')
    <header>
        <h1>404: Page not found</h1>
    </header>

    <div class="main">
        <p>
            The page you are looking for cannot be found.
        </p>
    </div>
@endsection