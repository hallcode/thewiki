@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Empty Page</a>
@endsection

@section('rightTabs')
    @if (isset($page->reference) && $page->reference !== '')
        <a href="{{ route('page.show', ["reference" => $page->reference]) }}" class="tab">Read</a>
    @endif
    <a href="{{ route('page.createWithReference', ['reference' => $page->reference]) }}" class="active tab">Create</a>
@endsection

@section('content')
    <header>
        <h1>Create Page {{ $page->title }}</h1>
        <nav>
            <button class="btn btn-primary" form="edit_form">Create & Save</button>
            @if (isset($page->reference) && $page->reference !== '')
                <a class="btn btn-default" href="{{ route('page.show', ["reference" => $page->reference]) }}">Back</a>
            @endif
        </nav>
    </header>

    <form id="edit_form" method="post" action="{{ route('page.store') }}">
        {!! csrf_field() !!}

        <div class="form-group">
            <div class="form-group col-sm-6">
                <label>
                    Title
                </label>
                @if (isset($page->title))
                    <input class="form-control" name="title" value="{{ $page->title }}" required>
                @else
                    <input class="form-control" name="title" required>
                @endif
            </div>
            <div class="form-group col-sm-6">
                <label>
                    Select Template
                </label>
                <select class="form-control" name="template">
                    <option>Not yet implemented</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="md-editor">Page text</label>
            <p>
                You can use markdown and {{ config('app.name') }}'s extended markup.
            </p>
            <textarea name="markdown" id="md-editor"></textarea>
        </div>

        <div class="form-group wiki-box">
            <label for="categories">Categories</label>
            <p>
                Select any number of existing categories, or type and press enter to create new ones.
            </p>
            <select id="categories" name="categories[]" class="form-control" multiple>
            </select>
        </div>

        <div class="form-group left-align">
            <button class="btn btn-primary" form="edit_form">Create & Save</button>
            @if (isset($page->reference) && $page->reference !== '')
                <a class="btn btn-default" href="{{ route('page.show', ["reference" => $page->reference]) }}">Back</a>
            @endif
        </div>
    </form>
@endsection

@section ('script')
    <script>
        $("#categories").select2({
            placeholder: "Type to add categories...",
            tags: true,
            ajax: {
                url: "{{ url('/ajax/category/search') }}",
                dataType: "json",
                delay: 150,
                minimumInputLength: 2,
            }

        })
        $(window).resize(function () {
            $('.select2').css('width', '100%');
        })
    </script>
@endsection
