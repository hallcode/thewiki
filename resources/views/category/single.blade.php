@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Category</a>
@endsection

@section('rightTabs')
    <a href="{{ route('page.show', ["reference" => 'Category:'.$category->title]) }}" class="active tab">Read</a>
    <a href="{{ route('page.edit', ['reference' => 'Category:'.$category->title]) }}" class="tab">Edit</a>
@endsection
@section('content')
    <header>
        <h1>Category:{{ $category->title }}</h1>
        <nav>
            <a class="btn btn-success" href="{{ route('page.create') }}">
                <i class="fa fa-fw fa-plus"></i> Create Page
            </a>
        </nav>
    </header>

    @if ($category_page->count() !== 0)
        @if (session('redirectedFrom'))
            <p class="meta">Redirected from {{ session('redirectedFrom') }}.</p>
        @endif

        <section>
            @foreach ($category_page->current_version->sections as $section)
                {!! $section->renderHTML() !!}
            @endforeach
        </section>
    @else
        <p>
            This page shows a list of all the pages in the <em>{{ $category->title }}</em> category.
        </p>
    @endif

    <h2>Pages</h2>
    @if ($pages->total() !== 0)
        <dl class="text-columns">
            @foreach($pages->groupBy('first_letter') as $first_letter=>$page_list)
                <dt>
                    {{ $first_letter }}
                </dt>
                <dd>
                    <ul>
                        @foreach($page_list as $page)
                            <li>
                                @if ($page->namespace !== null || !empty($page->namespace))
                                    <a href="{{ route('page.show', ['reference' => ucfirst($page->namespace).':'.$page->reference]) }}">{{ $page->title }}</a>
                                @else
                                    <a href="{{ route('page.show', ['reference' => $page->reference]) }}">{{ $page->title }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </dd>
            @endforeach
        </dl>

        {{ $pages->links() }} <em>Total pages: {{ $pages->total() }}</em>
    @else
        <p>There are no pages in this category.</p>
    @endif
@endsection

