@extends('layouts.app')

@section('leftTabs')
    <a href="/" class="active tab">All pages</a>
@endsection

@section('content')
    <header>
        @if ($namespace !== null)
            <h1>Pages in <em>{{ ucfirst($namespace) }}</em> namespace</h1>
        @else
            <h1>All Pages</h1>
        @endif
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
        <p>No pages. Make one now.</p>
    @endif
@endsection

