@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">Special Page</a>
@endsection

@section('content')
    <header>
        <h1>All Pages</h1>
        <nav>
            <a class="btn btn-success" href="{{ route('page.create') }}">
                <i class="fa fa-fw fa-plus"></i> Create Page
            </a>
        </nav>
    </header>

    <p>
        This page shows a list of all the pages on the wiki.
    </p>

    @if ($pages->count() !== 0)
        <dl class="text-columns">
            @foreach($pages->groupBy('first_letter') as $first_letter=>$page_list)
                <dt>
                    <h4>{{ $first_letter }}</h4>
                </dt>
                <dd>
                    <ul>
                        @foreach($page_list as $page)
                            <li>
                                <a href="{{ route('page.show', ['reference' => $page->reference]) }}">{{ $page->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </dd>
            @endforeach
        </dl>

        {{ $pages->links() }} <em>Total pages: {{ $pages->count() }}</em>
    @else
        <p>No pages. Make one now.</p>
    @endif
@endsection

