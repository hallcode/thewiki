@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Error</a>
@endsection

@section('content')
    <header>
        <h1>403: Unauthorised</h1>
    </header>

    <div class="main">
        <p>
            You are not authorised to view this page.
        </p>
        <p>
            {{ $exception->getMessage() }}
        </p>
    </div>
@endsection