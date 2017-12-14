@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Home</a>
    <a href="/" class="tab">Create page</a>
@endsection

@section('rightTabs')
    <a href="{{ route('home') }}" class="tab">Read</a>
    <a href="{{ route('home.edit') }}" class="active tab">Edit</a>
@endsection

@section('content')
    <header>
        <h1>Edit: Welcome to {{ config('app.name') }}</h1>
        <nav>
            <button class="btn btn-primary" form="edit_form">Save</button>
            <a class="btn btn-default" href="{{ route('home') }}">Back</a>
        </nav>
    </header>

    <form id="edit_form" method="post" action="{{ route('home.store') }}">
        {!! csrf_field() !!}

        <p>
            Use this form to edit the text that appears on the home page.
        </p>
        <p>
            You can also select (at the bottom) which other modules you would like to display on the home page.
        </p>

        <div class="form-group">
            <textarea name="markdown" id="md-editor">{!! $markdown !!}</textarea>
        </div>

        <div class="form-group left-align">
            <button class="btn btn-primary">Save</button>
            <a class="btn btn-default" href="{{ route('home') }}">Back</a>
        </div>
    </form>
@endsection
