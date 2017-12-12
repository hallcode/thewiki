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
            <a href="#" class="icon-button"><i class="fa fa-fw fa-star-o"></i> </a>
            @can('protect', $page)
            <a href="#" class="icon-button"><i class="fa fa-fw fa-shield"></i> </a>
            @endcan
            @can('delete', $page)
            <a href="#" class="icon-button"><i class="fa fa-fw fa-trash-o"></i> </a>
            @endcan
        </nav>
    </header>

    @if (session('redirectedFrom'))
        <p class="meta">Redirected from {{ session('redirectedFrom') }}.</p>
    @endif

    <section id="page-text" class="clearfix">
        {!! $page->infobox->renderHTML() !!}

        @foreach ($page->current_version->sections as $section)
            {!! $section->renderHTML() !!}

            @if ($loop->first && $page->current_version->sections->count() + $page->current_version->sections->pluck('children')->flatten()->count() >= 4)
                @include('page.blocks.contents')
            @endif
        @endforeach
    </section>

    <section id="page-info">
        <h3 class="light">Page Info</h3>
        @if ($page->categories->count() > 0)
            <p>Categories ({{ $page->categories->count() }})</p>
            <ul>
                @foreach ($page->categories as $category)
                    <li>
                        <a href="#">{{ $category->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($page->links_to->count() > 0)
            <p>Links to ({{ $page->links_to->count() }})</p>
            <ul>
                @foreach ($page->links_to as $link)
                    <li>
                        <a href="{{ route('page.show', ['reference' => $link->link_reference]) }}" class="{{ ( $link->target_page_id !== null ? 'blue-link' : 'red-link') }}">
                            {{ $link->link_reference }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($page->linked_from->count() > 0)
            <p>Links from ({{ $page->linked_from->count() }})</p>
            <ul>
                @foreach ($page->linked_from as $link)
                    <li>
                        <a href="{{ route('page.show', ['reference' => $link->page->reference]) }}" class="blue-link">{{ $link->page->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    @if ($page->edits->last())
        <footer>
            Last edited {{ $page->edits->last()->created_at->diffForHumans() }} by <a
                    href="/">{{ $page->edits->last()->user->name }}</a>.
        </footer>
    @endif
@endsection
