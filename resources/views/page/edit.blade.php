@extends('layouts.app')

@section('leftTabs')
    @foreach($tabsLeft as $title=>$url)
        <a href="{{ $url }}" class="@if ($url == $_url) active @endif tab">{{ $title }}</a>
    @endforeach
@endsection

@section('rightTabs')
    <a href="{{ route('page.show', ["reference" => $page->reference]) }}" class="active tab">Read</a>
    <a href="{{ route('page.edit', ['reference' => $page->reference]) }}" class="tab">Edit</a>
    <a href="#" class="tab">View history</a>
@endsection

@section('content')
    <header>
        <h1>{{ $page->title }}</h1>
        <nav>
            <button class="btn btn-primary" form="edit_form">Save</button>
            <a class="btn btn-default" href="{{ route('page.show', ["reference" => $page->reference]) }}">Back</a>
        </nav>
    </header>

    <form id="edit_form" method="post" action="{{ route('page.update', ['reference' => $page->reference]) }}">
        {!! csrf_field() !!}

        <div class="form-group">
            <label>
                Title
            </label>
            <p class="form-control-static" name="title">{{ $page->title }}</p>
        </div>

        <div class="form-group">
            <label for="md-editor">Page text</label>
            <p>
                You can use markdown and {{ config('app.name') }}'s extended markup.
            </p>
            <textarea name="markdown" id="md-editor">{{ $page->current_version->markdown }}</textarea>
        </div>

        <div class="form-group wiki-box">
            <label for="categories">Categories</label>
            <p>
                Select any number of existing categories, or type and press enter to create new ones.
            </p>
            <select id="categories" name="categories[]" class="form-control" multiple>
                @foreach($page->categories as $category)
                    <option value="{{ $category->id }}" data-select2-tag="true" selected>{{ $category->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group left-align">
            <button class="btn btn-primary" form="edit_form">Save</button>
            <a class="btn btn-default" href="{{ route('page.show', ["reference" => $page->reference]) }}">Back</a>
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
